<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { defineProps, defineEmits } from "vue";
import DangerButton from "@/Components/DangerButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import "@vuepic/vue-datepicker/dist/main.css";
import toastr from "toastr";
import "toastr/build/toastr.min.css";
import Rating from 'primevue/rating';
import TextareaInput from "@/Components/TextareaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps(["appointments"]);
const emit = defineEmits(["consult-clicked"]);
const modalDeleteAppointment = ref(false);
const modalReview = ref(false);
const selectedAppointment = ref(null);
const form = useForm({
    id: null,
});

const formReview = useForm({
    id: null,
    rating: 0,
    review: "",
    doctor: "",
    service: "",
    consult_id: 0,
});

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

const formatDate = (dateString) => {
    const options = {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    };
    return new Date(dateString).toLocaleDateString("ro-RO", options);
};

const formattedAppointments = computed(() =>
    props.appointments.map((appointment) => ({
        ...appointment,
        formatted_date: formatDate(appointment.appointment_date),
    }))
);

const searchQuery = ref("");
const currentPage = ref(1);
const itemsPerPageOptions = [15, 25, 50, 100];
const itemsPerPage = ref(itemsPerPageOptions[0]);

const selectedDate = ref(null);
const selectedStatus = ref("");

const now = new Date();

const disabledDates = (date) => {
    return date < new Date(now.toDateString());
};

const filteredAppointments = computed(() => {
    const searchLower = searchQuery.value.toLowerCase();
    return formattedAppointments.value.filter((appointment) => {
        const matchesSearch =
            appointment.consult.doctor.toLowerCase().includes(searchLower) ||
            appointment.consult.service.toLowerCase().includes(searchLower);
        const matchesDate = selectedDate.value
            ? new Date(appointment.appointment_date).toDateString() ===
              new Date(selectedDate.value).toDateString()
            : true;
        const matchesStatus = selectedStatus.value
            ? appointment.status === selectedStatus.value
            : true;
        return matchesSearch && matchesDate && matchesStatus;
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

const openModalDeletAppointment = (appointment) => {
    selectedAppointment.value = appointment;
    form.id = appointment.id;
    modalDeleteAppointment.value = true;
};

const openReviewModal = (appointment) => {
    formReview.id = appointment.id;
    formReview.doctor = appointment.consult.doctor;
    formReview.service = appointment.consult.service;
    formReview.consult_id = appointment.consult.id;

    modalReview.value = true;
};

const closeModalDelete = () => {
    modalDeleteAppointment.value = false;
};

const closeReviewModal = () => {
    modalReview.value = false;
};

const deleteAppointment = () => {
    form.post(route("consult.client.appointment.destroy"), {
        onFinish: () => {
            modalDeleteAppointment.value = false;
            showNotification("Programarea a fost anulată");
        },
        onError: () => {
            showNotification("A apărut o eroare", "error");
        },
    });
};

const showNotification = (message, type = "success") => {
    toastr[type](message);
};

const submitReview = () => {
    formReview.post(route("consult.client.appointment.review.store"), {
        onFinish: () => {
            formReview.reset();
            modalReview.value = false;
            showNotification("Recenzia a fost salvată");
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
                            v-model="searchQuery"
                            type="text"
                            placeholder="Caută"
                            class="form-control"
                            aria-label="Search"
                            aria-describedby="basic-addon1"
                        />
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background-color: white"
                                >Data</span
                            >
                            <input
                                type="date"
                                v-model="selectedDate"
                                class="form-control"
                            />
                        </div>
                        <select
                            v-model="selectedStatus"
                            class="form-select ms-2"
                            aria-label="Select Status"
                        >
                            <option value="">Status</option>
                            <option value="Confirmată">Confirmată</option>
                            <option value="Anulată">Anulată</option>
                            <option value="Anulată">Onorată</option>
                        </select>
                    </div>
                </div>
                <div v-if="paginatedAppointments.length > 0">
                    <div
                        v-for="appointment in paginatedAppointments"
                        :key="appointment.id"
                        class="card mb-4 border-gray-200 shadow-lg"
                    >
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title">
                                        Dr. {{ appointment.consult.doctor }}
                                    </h3>
                                    <h4 class="card-subtitle mb-2 text-muted">
                                        {{ appointment.consult.service }}
                                    </h4>
                                    <p class="card-text text-gray-600 mt-2">
                                        <strong
                                            >Data și ora programării:</strong
                                        >
                                        {{ appointment.formatted_date }}
                                    </p>
                                    <p class="card-text text-gray-600 mt-2">
                                        <strong>Preț:</strong>
                                        {{ appointment.consult.price }} Lei
                                    </p>
                                    <p class="card-text text-gray-600 mt-2">
                                        <strong>Locație: </strong>
                                        <a
                                            class="text-muted"
                                            style="text-decoration: none"
                                            :href="
                                                getMapLink(appointment.consult)
                                            "
                                            target="_blank"
                                            @click="
                                                handleClick(appointment.consult)
                                            "
                                        >
                                            {{
                                                appointment.consult.county.name
                                            }},
                                            {{ appointment.consult.city.name }},
                                            {{ appointment.consult.address }}
                                        </a>
                                    </p>
                                    <p class="card-text mt-2">
                                        <span
                                            :class="[
                                                'badge',
                                                appointment.status ===
                                                'Confirmată'
                                                    ? 'badge-success'
                                                    : appointment.status ===
                                                      'Onorată'
                                                    ? 'badge-primary'
                                                    : 'badge-danger',
                                            ]"
                                        >
                                            {{ appointment.status }}
                                        </span>
                                    </p>
                                </div>
                                <div
                                    class="col-md-6 d-flex flex-column justify-content-end align-items-end"
                                >
                                    <PrimaryButton
                                        v-if="appointment.status === 'Onorată'"
                                        @click="openReviewModal(appointment)"
                                        >Recenzie</PrimaryButton
                                    >
                                    <DangerButton
                                        class="ms-3 mt-2"
                                        v-if="appointment.status === 'Confirmată'"
                                        @click="
                                            openModalDeletAppointment(
                                                appointment
                                            )
                                        "
                                    >
                                        Anulează
                                    </DangerButton>
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
                            Nu aveți programări făcute.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="modalDeleteAppointment" @close="closeModalDelete">
            <form @submit.prevent="deleteAppointment">
                <div v-if="selectedAppointment" class="p-6">
                    <h5>Dorești să anulezi următoarea programare?</h5>
                    <p>
                        <strong>Doctor:</strong>
                        {{ selectedAppointment.consult.doctor }}
                    </p>
                    <p>
                        <strong>Serviciu:</strong>
                        {{ selectedAppointment.consult.service }}
                    </p>
                    <p>
                        <strong>Data și ora:</strong>
                        {{ selectedAppointment.formatted_date }}
                    </p>
                    <p>
                        <strong>Preț:</strong>
                        {{ selectedAppointment.consult.price }} Lei
                    </p>
                    <p>
                        <strong>Locație:</strong>
                        {{ selectedAppointment.consult.county.name }},
                        {{ selectedAppointment.consult.city.name }},
                        {{ selectedAppointment.consult.address }}
                    </p>
                    <div class="mt-6 flex justify-end">
                        <DangerButton type="submit"> Șterge </DangerButton>
                        <SecondaryButton @click="closeModalDelete" class="ms-3">
                            Închide
                        </SecondaryButton>
                    </div>
                </div>
            </form>
        </Modal>

        <Modal :show="modalReview" @close="closeReviewModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Lasă o recenzie doctorului {{ formReview.doctor }} pentru
                    {{ formReview.service }}:
                </h2>

                <form @submit.prevent="submitReview">
                    <div class="mt-4">
                        <InputLabel
                            for="rating"
                            value="Câte stele ai da experienței tale?"
                        />
                        <Rating
                            class="mt-1 block w-full"
                            v-model="formReview.rating" 
                            :stars="5"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel
                            for="rating"
                            value="Exteriența ta"
                        />
                        <TextareaInput
                            v-model="formReview.review"
                            class="mt-1 block w-full"
                            id="review"
                        />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <PrimaryButton type="submit">Postează</PrimaryButton>
                        <SecondaryButton @click="closeReviewModal" class="ms-3"
                            >Închide</SecondaryButton
                        >
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<style>
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

.badge {
    display: inline-block;
    padding: 0.5em 0.75em;
    font-size: 0.875rem;
    font-weight: 600;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.375rem;
}

.badge-primary {
    color: #000;
    background-color: #28a745;
    font-weight: normal;
}

.badge-danger {
    color: #000;
    background-color: #dc3545;
    font-weight: normal;
}

.badge-success {
    color: #000;
    background-color: #007bff;
    font-weight: normal;
}

button {
    font-size: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
}

button + button {
    margin-left: 0.75rem;
}

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
</style>
