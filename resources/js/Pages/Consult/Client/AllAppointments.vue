<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { defineProps, defineEmits } from "vue";
import DangerButton from "@/Components/DangerButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import "@vuepic/vue-datepicker/dist/main.css";

const props = defineProps(["appointments"]);
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
                                        >Recenzie</PrimaryButton
                                    >
                                    <SecondaryButton class="ms-3 mt-2">
                                        Editează
                                    </SecondaryButton>
                                    <DangerButton class="ms-3 mt-2">
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
