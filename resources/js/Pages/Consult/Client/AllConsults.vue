<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { defineProps, ref, computed, defineEmits } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import DateTime from "@/Components/DateTime.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import toastr from "toastr";
import "toastr/build/toastr.min.css";

const props = defineProps({
    consults: Array,
    appointments: Array,
});

const emit = defineEmits(["consult-clicked"]);

const getMapLink = (consult) => {
    const address = consult.address;
    const city = consult.city.name;
    const county = consult.county.name;
    return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
        address + ", " + city + ", " + county
    )}`;
};

const handleClick = (consult) => {
    emit("consult-clicked", consult);
};

const modalAppointment = ref(false);
const appointmentDetails = useForm({
    consult_id: 0,
    doctor: "",
    service: "",
    price: 0.0,
    consult_time: 0,
    date: new Date(new Date().setHours(8, 0, 0, 0)),
    duration: 0,
});
const now = new Date();
const searchQuery = ref("");
const currentPage = ref(1);
const itemsPerPageOptions = [15, 25, 50, 100];
const itemsPerPage = ref(itemsPerPageOptions[0]);

const selectedService = ref("");
const selectedCounty = ref("");
const selectedCity = ref("");
const sortOrder = ref("asc");

const uniqueServices = computed(() => {
    return [...new Set(props.consults.map((consult) => consult.service))];
});

const uniqueCounties = computed(() => {
    return [...new Set(props.consults.map((consult) => consult.county.name))];
});

const uniqueCities = computed(() => {
    return [...new Set(props.consults.map((consult) => consult.city.name))];
});

const filteredConsults = computed(() => {
    const searchLower = searchQuery.value.toLowerCase();
    let consults = props.consults.filter((consult) => {
        const matchesSearch =
            consult.doctor.name.toLowerCase().includes(searchLower) ||
            consult.service.toLowerCase().includes(searchLower) ||
            consult.city.name.toLowerCase().includes(searchLower) ||
            consult.county.name.toLowerCase().includes(searchLower);
        const matchesService = selectedService.value
            ? consult.service === selectedService.value
            : true;
        const matchesCounty = selectedCounty.value
            ? consult.county.name === selectedCounty.value
            : true;
        const matchesCity = selectedCity.value
            ? consult.city.name === selectedCity.value
            : true;
        return matchesSearch && matchesService && matchesCounty && matchesCity;
    });

    if (sortOrder.value === "asc") {
        consults = consults.sort((a, b) => a.price - b.price);
    } else {
        consults = consults.sort((a, b) => b.price - a.price);
    }

    return consults;
});

const paginatedConsults = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredConsults.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredConsults.value.length / itemsPerPage.value);
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

const toggleSortOrder = () => {
    sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
};

const disabledTimeSlots = computed(() => {
    return props.appointments
        .filter((app) => app.consult_id === appointmentDetails.consult_id)
        .map((app) => new Date(app.appointment_date).toISOString());
});

const parseDoctorSchedule = (schedule) => {
    const dayMapping = {
        Luni: 1,
        Marți: 2,
        Miercuri: 3,
        Joi: 4,
        Vineri: 5,
        Sâmbătă: 6,
        Duminică: 0,
    };
    const days = [];
    const timeSlots = {};

    if (typeof schedule === "string") {
        try {
            schedule = JSON.parse(schedule);
        } catch (e) {
            console.error("Invalid JSON string:", e);
            return { days, timeSlots };
        }
    }

    for (const [day, info] of Object.entries(schedule)) {
        if (info.enabled) {
            const dayNumber = dayMapping[day];
            days.push(dayNumber);
            timeSlots[dayNumber] = {
                start: info.start_time,
                end: info.end_time,
            };
        }
    }
    console.log(timeSlots);

    return { days, timeSlots };
};

const openModal = (consult) => {
    const schedule = parseDoctorSchedule(consult.doctor.working_days);

    appointmentDetails.consult_id = consult.id;
    appointmentDetails.doctor = consult.doctor.name;
    appointmentDetails.service = consult.service;
    appointmentDetails.price = consult.price;

    const consultTimeParts = consult.consult_time.split(":");
    appointmentDetails.consult_time = consultTimeParts[1]; // Get the minutes part

    appointmentDetails.schedule = schedule;

    modalAppointment.value = true;
};

const closeModal = () => {
    modalAppointment.value = false;
};

const handleTimeUpdate = (selected) => {
    const [day, month, year] = selected.date.split(".");
    const formattedDate = `${year}-${month}-${day}`;
    appointmentDetails.date = new Date(`${formattedDate}T${selected.time}:00`);
};

const submit = () => {
    appointmentDetails.post(route("consult.client.appointment.store"), {
        onFinish: () => {
            appointmentDetails.reset();
            modalAppointment.value = false;
            showNotification("Programarea a fost salvată");
        },
    });
};

const showNotification = (message, type = "success") => {
    toastr[type](message);
};
</script>
<template>
    <Head title="Servicii" />

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
                            v-model="searchQuery"
                            type="text"
                            placeholder="Caută"
                            class="form-control"
                            aria-label="Search"
                            aria-describedby="basic-addon1"
                        />
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <select
                            v-model="selectedService"
                            class="form-select me-2"
                            aria-label="Select Service"
                        >
                            <option value="">Serviciu</option>
                            <option
                                v-for="service in uniqueServices"
                                :key="service"
                                :value="service"
                            >
                                {{ service }}
                            </option>
                        </select>
                        <select
                            v-model="selectedCounty"
                            class="form-select me-2"
                            aria-label="Select County"
                        >
                            <option value="">Județ</option>
                            <option
                                v-for="county in uniqueCounties"
                                :key="county"
                                :value="county"
                            >
                                {{ county }}
                            </option>
                        </select>
                        <select
                            v-model="selectedCity"
                            class="form-select me-2"
                            aria-label="Select City"
                        >
                            <option value="">Oraș</option>
                            <option
                                v-for="city in uniqueCities"
                                :key="city"
                                :value="city"
                            >
                                {{ city }}
                            </option>
                        </select>
                        <button
                            class="btn btn-outline-secondary"
                            @click="toggleSortOrder"
                            :aria-label="`Sort by price ${
                                sortOrder === 'asc' ? 'ascending' : 'descending'
                            }`"
                        >
                            <i
                                :class="
                                    sortOrder === 'asc'
                                        ? 'fas fa-arrow-up'
                                        : 'fas fa-arrow-down'
                                "
                            ></i>
                        </button>
                    </div>
                </div>
                <div v-if="paginatedConsults.length > 0">
                    <div
                        class="card mb-4 border-gray-200 shadow-lg"
                        v-for="consult in paginatedConsults"
                        :key="consult.id"
                    >
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h3 class="card-title">
                                        Dr. {{ consult.doctor.name }}
                                    </h3>
                                    <h4 class="card-subtitle mb-2 text-muted">
                                        {{ consult.service }}
                                    </h4>
                                    <p class="card-text">
                                        {{ consult.description }}
                                    </p>
                                    <p class="card-text text-muted">
                                        <a
                                            class="text-muted"
                                            style="text-decoration: none"
                                            :href="getMapLink(consult)"
                                            target="_blank"
                                            @click="handleClick(consult)"
                                        >
                                            <i
                                                class="fas fa-map-marker-alt"
                                            ></i>
                                            {{ consult.county.name }},
                                            {{ consult.city.name }},
                                            {{ consult.address }}
                                        </a>
                                    </p>
                                </div>
                                <div
                                    class="col-md-4 d-flex flex-column justify-content-between"
                                    style="text-align: left"
                                >
                                    <p
                                        class="card-text"
                                        style="font-size: 20px"
                                    >
                                        <strong>Preț:</strong>
                                        {{ consult.price }} Lei
                                    </p>

                                    <div>
                                        <PrimaryButton
                                            @click="openModal(consult)"
                                        >
                                            Fă o programare
                                        </PrimaryButton>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <nav
                        class="d-flex justify-content-center align-items-center"
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
                                    @click.prevent="changePage(currentPage - 1)"
                                >
                                    <
                                </a>
                            </li>
                            <li
                                class="page-item"
                                v-for="page in totalPages"
                                :key="page"
                                :class="{ active: page === currentPage }"
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
                                    disabled: currentPage === totalPages,
                                }"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="changePage(currentPage + 1)"
                                >
                                    >
                                </a>
                            </li>
                            <li
                                class="page-item"
                                :class="{
                                    disabled: currentPage === totalPages,
                                }"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="changePage(totalPages)"
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
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6 text-gray-900">
                            Nu sunt înregistrate servicii momentan.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal
            :show="modalAppointment"
            @close="closeModal"
            style="height: 500px"
        >
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Dorești să faci o programare la Dr.
                    {{ appointmentDetails.doctor }} pentru
                    {{ appointmentDetails.service }}?
                </h2>

                <p class="mt-1 text-gray-600">
                    Prețul va fi de {{ appointmentDetails.price }} Lei. Plata nu
                    se face în avans.
                </p>

                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <DateTime
                            :days="appointmentDetails.schedule.days"
                            :timeSlots="appointmentDetails.schedule.timeSlots"
                            :startDate="now.toISOString().split('T')[0]"
                            :endDate="'2024-12-31'"
                            :consult_time="appointmentDetails.consult_time"
                            :disabledTimeSlots="disabledTimeSlots"
                            @update:time="handleTimeUpdate"
                        />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton type="submit">Salvează</PrimaryButton>

                        <SecondaryButton @click="closeModal" class="ms-3">
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
    margin: 0 5px;
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

.card {
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
}

.card-title {
    font-size: 1.25rem;
}

.card-subtitle {
    font-size: 1rem;
}

.card-text {
    font-size: 1rem;
}
</style>
