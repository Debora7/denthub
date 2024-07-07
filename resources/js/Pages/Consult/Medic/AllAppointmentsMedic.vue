<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, defineProps, computed } from "vue";
import axios from "axios";
import toastr from "toastr";
import "toastr/build/toastr.min.css";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps({
    appointments: Array,
});

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
    axios
        .post(`/consult/medic/honored/${id}`)
        .then((response) => {
            showNotification("Programarea a fost marcată ca fiind onorată");
        })
        .catch((error) => {
            showNotification("A apătur o eroare", "error");
        });
};

const showNotification = (message, type = "success") => {
    toastr[type](message);
};

const appointmentMissed = (id) => {
    axios
        .post(`/consult/medic/missed/${id}`)
        .then((response) => {
            showNotification("Programarea a fost marcată ca fiind anulată");
        })
        .catch((error) => {
            showNotification("A apărut o eroare", "error");
        });
};

const openNewAppointmentModal = () => {
    modalNewAppointment.value = true;
};

const closeModalNewAppointment = () => {
    modalNewAppointment.value = false;
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
            <h1>something</h1>
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
</style>
