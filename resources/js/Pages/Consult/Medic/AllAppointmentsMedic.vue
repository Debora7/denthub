<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, defineProps, computed, watch } from "vue";
import toastr from "toastr";
import "toastr/build/toastr.min.css";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    appointments: Array,
    allAppointments: Array,
    doctors: Array,
});

const formForActions = useForm({
    id: 0,
});

const form = useForm({
    name: "",
    email: "",
    phone: "",
    doctor: "",
    consult: "",
    date: "",
    time: "",
    consult_time: "",
});

const consults = ref([]);

const updateConsults = (selecteDoctorId) => {
    const doctor = props.doctors.find((c) => c.id === selecteDoctorId);
    consults.value = doctor ? doctor.consults : [];
};

watch(
    () => form.doctor,
    (newDoctor) => {
        updateConsults(newDoctor);
        form.consult = "";
        form.date = "";
        timeSlots.value = [];
    }
);

watch(
    () => form.consult,
    () => {
        form.date = "";
        timeSlots.value = [];
    }
);

const minDate = computed(() => {
    const today = new Date();
    return today.toISOString().split("T")[0];
});

const maxDate = computed(() => {
    const nextYear = new Date();
    nextYear.setFullYear(nextYear.getFullYear() + 1);
    return nextYear.toISOString().split("T")[0];
});

const isWorkingDay = (dateString) => {
    if (!dateString) return true;

    const date = new Date(dateString);

    const dayOfWeek = date.toLocaleDateString("ro-RO", { weekday: "long" });
    const dayKey = dayOfWeek.charAt(0).toUpperCase() + dayOfWeek.slice(1);

    const doctorId = form.doctor;
    const doctor = props.doctors.find((doc) => doc.id === doctorId);

    if (!doctor) {
        console.error("Doctor not found");
        return false;
    }

    const workingDays = doctor.working_days;

    return JSON.parse(workingDays)[dayKey]?.enabled || false;
};

const timeSlots = ref([]);

watch(
    () => form.date,
    (newDate) => {
        if (newDate) {
            const doctorId = form.doctor;
            const doctor = props.doctors.find((doc) => doc.id === doctorId);
            const consultId = form.consult;
            const consult = doctor.consults.find(
                (consult) => consult.id === consultId
            );
            form.consult_time = consult.consult_time;
            const consultTime = consult.consult_time;
            const workingHours = doctor.working_days;

            calculateTimeSlots(JSON.parse(workingHours), consultTime);
        }
    }
);

const calculateTimeSlots = (workingDays, consultTime) => {
    const selectedDate = new Date(form.date);
    const dayOfWeek = selectedDate.toLocaleDateString("ro-RO", {
        weekday: "long",
    });
    const dayKey = dayOfWeek.charAt(0).toUpperCase() + dayOfWeek.slice(1);
    const dayInfo = workingDays[dayKey];

    if (!dayInfo || !dayInfo.enabled) {
        timeSlots.value = [];
        return;
    }

    const slots = [];
    const startTime = parseTime(dayInfo.start_time);
    const endTime = parseTime(dayInfo.end_time);
    let currentTime = new Date(startTime);
    const consultDuration = parseDuration(consultTime);

    while (currentTime < endTime) {
        slots.push(formatTime(currentTime));
        currentTime.setMinutes(
            currentTime.getMinutes() + consultDuration.minutes
        );
    }

    timeSlots.value = filterTimeSlots(props.allAppointments, form, slots);
};

const filterTimeSlots = (appointments, appointmentDetails, timeSlots) => {
    let filteredTimeSlots = [...timeSlots]; // Start with the original time slots

    appointments.forEach((appointment) => {
        if (
            appointment.doctor_id === appointmentDetails.doctor &&
            appointment.consult_id === appointmentDetails.consult &&
            new Date(appointment.appointment_date)
                .toISOString()
                .split("T")[0] === appointmentDetails.date
        ) {
            const appointmentTime =
                new Date(appointment.appointment_date)
                    .toTimeString()
                    .split(":")[0] +
                ":" +
                new Date(appointment.appointment_date)
                    .toTimeString()
                    .split(":")[1];
            filteredTimeSlots = filteredTimeSlots.filter(
                (timeSlot) => timeSlot !== appointmentTime
            );
        } else if (
            appointment.doctor_id === appointmentDetails.doctor &&
            new Date(appointment.appointment_date)
                .toISOString()
                .split("T")[0] === appointmentDetails.date
        ) {
            const appointmentTimeStart =
                new Date(appointment.appointment_date)
                    .toTimeString()
                    .split(":")[0] +
                ":" +
                new Date(appointment.appointment_date)
                    .toTimeString()
                    .split(":")[1];
            const appointmentTimeEnd =
                new Date(appointment.appointment_date_end)
                    .toTimeString()
                    .split(":")[0] +
                ":" +
                new Date(appointment.appointment_date_end)
                    .toTimeString()
                    .split(":")[1];

            filteredTimeSlots = filteredTimeSlots.filter((timeSlot) => {
                return (
                    timeSlot < appointmentTimeStart ||
                    timeSlot >= appointmentTimeEnd
                );
            });
        }
    });

    return filteredTimeSlots;
};

const parseTime = (timeString) => {
    const [hours, minutes] = timeString.split(":").map(Number);
    const date = new Date();
    date.setHours(hours, minutes, 0, 0);
    return date;
};

const parseDuration = (duration) => {
    const [hours, minutes, seconds] = duration.split(":").map(Number);
    return { hours, minutes, seconds };
};

const formatTime = (date) => {
    return date.toTimeString().slice(0, 5);
};

const selectedSlot = ref(null);

const selectSlot = (slot) => {
    selectedSlot.value = slot;
    form.time = slot;
};

const modalNewAppointment = ref(false);

const getHour = (appointmentDate) => {
    const date = new Date(appointmentDate);
    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");
    return `${hours}:${minutes}`;
};

// Pagination and Search
const currentPage = ref(1);
const itemsPerPageOptions = [15, 25, 50, 100];
const itemsPerPage = ref(itemsPerPageOptions[0]);
const searchQuery = ref("");
const filterDate = ref(new Date().toISOString().substr(0, 10)); // Default to current date

const filteredAppointments = computed(() => {
    return props.appointments.filter((appointment) => {
        const appointmentDate = new Date(appointment.appointment_date)
            .toISOString()
            .substr(0, 10);
        return (
            appointmentDate === filterDate.value &&
            appointment.user.name
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase())
        );
    });
});

const paginatedAppointments = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredAppointments.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredAppointments.value.length / itemsPerPage.value);
});

const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

const changeItemsPerPage = (event) => {
    itemsPerPage.value = parseInt(event.target.value);
    currentPage.value = 1;
};

const appointmentHonored = (id) => {
    formForActions.id = id;
    formForActions.post(route("consult.medic.honored"), {
        onFinish: () => {
            showNotification("Programarea a fost marcată ca fiind onorată");
        },
    });
};

const showNotification = (message, type = "success") => {
    toastr[type](message);
};

const appointmentMissed = (id) => {
    formForActions.id = id;
    formForActions.post(route("consult.medic.missed"), {
        onFinish: () => {
            showNotification("Programarea a fost marcată ca fiind anulată");
        },
    });
};

const openNewAppointmentModal = () => {
    modalNewAppointment.value = true;
};

const closeModalNewAppointment = () => {
    modalNewAppointment.value = false;
    form.reset();
    timeSlots.value = [];
};

const submit = () => {
    form.post(route("consult.medic.consult.store"), {
        onFinish: () => {
            modalNewAppointment.value = false;
            form.reset();
            showNotification("Programarea a fost salvată");
        },
    });
};
</script>

<template>
    <Head title="Programari" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-4">
                    <div class="input-group mb-3">
                        <span
                            class="input-group-text"
                            id="basic-addon1"
                            style="background-color: white"
                        >
                            <i class="fas fa-search"></i>
                        </span>
                        <input
                            type="text"
                            v-model="searchQuery"
                            class="form-control"
                            placeholder="Caută după numele pacientului"
                        />
                    </div>
                    <div class="input-group mb-3">
                        <span
                            class="input-group-text"
                            style="background-color: white"
                            >Data</span
                        >
                        <input
                            type="date"
                            v-model="filterDate"
                            class="form-control"
                        />
                        <PrimaryButton
                            class="ms-2"
                            style="border-radius: 7px"
                            @click="openNewAppointmentModal"
                        >
                            Adaugă o programare
                        </PrimaryButton>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="paginatedAppointments.length > 0">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr class="bg-gray-100">
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Nume pacient
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Telefon
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Ora programării
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Serviciul
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Suma de plată
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Acțiuni
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="appointment in paginatedAppointments"
                                        :key="appointment.id"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ appointment.user.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ appointment.user.phone ?? "-" }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{
                                                getHour(
                                                    appointment.appointment_date
                                                )
                                            }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ appointment.consult.service }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ appointment.consult.price }} Lei
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            <button
                                                class="bg-green-500 text-white px-2 py-1"
                                                style="
                                                    border-radius: 50%;
                                                    width: 32px;
                                                    height: 32px;
                                                "
                                                @click="
                                                    appointmentHonored(
                                                        appointment.id
                                                    )
                                                "
                                            >
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button
                                                class="bg-red-500 text-white px-2 py-1 ml-2"
                                                style="
                                                    border-radius: 50%;
                                                    width: 32px;
                                                    height: 32px;
                                                "
                                                @click="
                                                    appointmentMissed(
                                                        appointment.id
                                                    )
                                                "
                                            >
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <nav
                                class="d-flex justify-content-center align-items-center mt-4"
                            >
                                <ul class="pagination mb-0 custom-pagination">
                                    <li
                                        class="page-item"
                                        :class="{ disabled: currentPage === 1 }"
                                    >
                                        <a
                                            class="page-link"
                                            href="#"
                                            @click.prevent="changePage(1)"
                                        >
                                            <<
                                        </a>
                                    </li>
                                    <li
                                        class="page-item"
                                        :class="{ disabled: currentPage === 1 }"
                                    >
                                        <a
                                            class="page-link"
                                            href="#"
                                            @click.prevent="
                                                changePage(currentPage - 1)
                                            "
                                        >
                                            <
                                        </a>
                                    </li>
                                    <li
                                        class="page-item"
                                        v-for="page in totalPages"
                                        :key="page"
                                        :class="{
                                            active: page === currentPage,
                                        }"
                                    >
                                        <a
                                            class="page-link"
                                            href="#"
                                            @click.prevent="changePage(page)"
                                        >
                                            {{ page }}
                                        </a>
                                    </li>
                                    <li
                                        class="page-item"
                                        :class="{
                                            disabled:
                                                currentPage === totalPages,
                                        }"
                                    >
                                        <a
                                            class="page-link"
                                            href="#"
                                            @click.prevent="
                                                changePage(currentPage + 1)
                                            "
                                        >
                                            >
                                        </a>
                                    </li>
                                    <li
                                        class="page-item"
                                        :class="{
                                            disabled:
                                                currentPage === totalPages,
                                        }"
                                    >
                                        <a
                                            class="page-link"
                                            href="#"
                                            @click.prevent="
                                                changePage(totalPages)
                                            "
                                        >
                                            >>
                                        </a>
                                    </li>
                                </ul>
                                <select
                                    class="form-select form-select-sm ms-3"
                                    @change="changeItemsPerPage"
                                    :value="itemsPerPage"
                                    style="width: auto"
                                >
                                    <option
                                        v-for="option in itemsPerPageOptions"
                                        :key="option"
                                        :value="option"
                                    >
                                        {{ option }}
                                    </option>
                                </select>
                            </nav>
                        </div>
                        <div v-else>
                            <div class="p-6 text-gray-900">
                                Nu aveți programări făcute momentan. Aici veți
                                putea vedea toate programările făcute de dvs.
                                sau clienți.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="modalNewAppointment" @close="closeModalNewAppointment">
            <div class="p-6">
                <form @submit.prevent="submit">
                    <div>
                        <InputLabel
                            for="name"
                            value="Nume complet"
                            :required="true"
                        />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mt-4">
                        <InputLabel
                            for="email"
                            value="Email"
                            :required="true"
                        />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="phone" value="Telefon" />
                        <div class="flex items-center">
                            <TextInput
                                id="phone"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.phone"
                                maxlength="10"
                            />
                        </div>
                        <InputError class="mt-2" :message="form.errors.phone" />
                    </div>

                    <div class="mt-4">
                        <InputLabel
                            for="doctor"
                            value="Medic"
                            :required="true"
                        />
                        <select
                            id="doctor"
                            class="mt-1 block w-full"
                            v-model="form.doctor"
                            required
                            style="border-radius: 5px; border-color: lightgray"
                        >
                            <option value="">Selectează medicul</option>
                            <option
                                v-for="doctor in doctors"
                                :key="doctor.id"
                                :value="doctor.id"
                            >
                                {{ doctor.name }}
                            </option>
                        </select>
                        <InputError
                            class="mt-2"
                            :message="form.errors.doctor"
                        />
                    </div>

                    <div v-if="form.doctor" class="mt-4">
                        <InputLabel
                            for="consult"
                            value="Serviciu"
                            :required="true"
                        />
                        <select
                            id="consult"
                            class="mt-1 block w-full"
                            v-model="form.consult"
                            required
                            style="border-radius: 5px; border-color: lightgray"
                        >
                            <option value="">Selectează serviciul</option>
                            <option
                                v-for="consult in consults"
                                :key="consult.id"
                                :value="consult.id"
                            >
                                {{ consult.service }}
                            </option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.city" />
                    </div>

                    <div v-if="form.consult" class="mt-4">
                        <InputLabel
                            for="appointmentDate"
                            value="Selectează data programării"
                            :required="true"
                        />
                        <input
                            type="date"
                            id="appointmentDate"
                            v-model="form.date"
                            :min="minDate"
                            :max="maxDate"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                        />
                        <p v-if="!isWorkingDay(form.date)" class="text-danger">
                            Medicul nu are program în această zi.
                        </p>
                    </div>

                    <div
                        v-if="form.date && isWorkingDay(form.date)"
                        class="mt-4"
                    >
                        <InputLabel
                            value="Selectează ora programării"
                            :required="true"
                        />
                        <div class="mt-2">
                            <span
                                v-for="slot in timeSlots"
                                :key="slot"
                                :class="[
                                    'badge',
                                    { 'badge-selected': slot === selectedSlot },
                                ]"
                                @click="selectSlot(slot)"
                            >
                                {{ slot }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton type="submit">Salvează</PrimaryButton>

                        <SecondaryButton
                            @click="closeModalNewAppointment"
                            class="ms-3"
                        >
                            Închide
                        </SecondaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<style scoped>
.custom-pagination .page-item .page-link {
    border: none;
    background-color: transparent;
    color: #6c757d;
}

.custom-pagination .page-item.disabled .page-link {
    color: #6c757d;
}

.custom-pagination .page-item.active .page-link {
    color: #6c757d;
    font-weight: bold;
}

.custom-pagination .page-item:hover .page-link {
    text-decoration: none;
}

.badge {
    display: inline-block;
    padding: 0.5em;
    margin: 0.2em;
    background-color: #28a745;
    border-radius: 0.25em;
    cursor: pointer;
    color: #fff;
    font-weight: normal;
}

.badge-selected {
    background-color: #007bff;
}
</style>
