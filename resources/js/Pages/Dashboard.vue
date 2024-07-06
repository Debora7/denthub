<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, defineProps, computed, watch } from "vue";
import Modal from "@/Components/Modal.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import toastr from "toastr";
import "toastr/build/toastr.min.css";

const props = defineProps({
    consults: Array,
    counties: Array,
    doctors: Array,
});

const deleteConsultModal = (consult) => {
    form.doctor = consult.doctor;
    form.address = consult.address;
    form.city = consult.city.name;
    form.county = consult.county.name;
    form.service = consult.service;
    form.price = Number(parseFloat(consult.price).toFixed(2));
    form.description = consult.description;
    form.id = consult.id;
    form.time = consult.consult_time.split(":")[1];

    modalDeleteConsult.value = true;
};

const openReviewModal = (consult) => {
    // To complete
    // modalDeleteConsult.value = true;
};

const modalEditConsult = ref(false);
const modalDeleteConsult = ref(false);
const currentPage = ref(1);
const itemsPerPageOptions = [15, 25, 50, 100];
const itemsPerPage = ref(itemsPerPageOptions[0]);

const paginatedConsults = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return props.consults.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(props.consults.length / itemsPerPage.value);
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

const editConsult = (consult) => {
    form.doctor = consult.doctor_id;
    form.address = consult.address;
    form.city = consult.city_id;
    form.county = consult.county_id;
    form.service = consult.service;
    form.price = Number(parseFloat(consult.price).toFixed(2));
    form.description = consult.description;
    form.time = consult.consult_time.split(":")[1];
    form.id = consult.id;

    modalEditConsult.value = true;
};

const closeModalEdit = () => {
    modalEditConsult.value = false;
};

const closeModalDelete = () => {
    modalDeleteConsult.value = false;
};

const form = useForm({
    id: 0,
    doctor: "",
    address: "",
    city: "",
    county: "",
    service: "",
    price: 0.0,
    description: "",
    time: "",
});

const cities = ref([]);

const updateCities = (selectedCountyId) => {
    const county = props.counties.find((c) => c.id === selectedCountyId);
    cities.value = county ? county.cities : [];
};

watch(
    () => form.county,
    (newCounty) => {
        updateCities(newCounty);
    }
);

const submit = () => {
    form.put(route("consult.update", { id: form.id }), {
        preserveScroll: true,
        data: {
            ...form,
        },
        onFinish: () => {
            form.reset();
            modalEditConsult.value = false;
            showNotification("Serviciul a fost editat");
        },
    });
};

const showNotification = (message, type = "success") => {
    toastr[type](message);
};

const deleteConsult = () => {
    form.delete(route("consult.destroy"), {
        onFinish: () => {
            form.reset();
            modalDeleteConsult.value = false;
            showNotification("Serviciul a fost șters");
        },
    });
};
</script>

<template>
    <Head title="Servicii" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="paginatedConsults.length > 0">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Medic
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Serviciul oferit
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Preț
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Adresa completă
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Oraș
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Județ
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
                                        v-for="consult in paginatedConsults"
                                        :key="consult.id"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ consult.doctor }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ consult.service }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ consult.price }} Lei
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ consult.address }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ consult.city.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ consult.county.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            <button
                                                type="button"
                                                @click="editConsult(consult)"
                                                class="inline-flex items-center justify-center text-center p-2 bg-yellow-500 border border-transparent rounded-full text-white hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:shadow-outline-yellow transition ease-in-out duration-150 ml-2"
                                            >
                                                <i class="fas fa-pencil"></i>
                                            </button>

                                            <button
                                                type="button"
                                                @click="
                                                    deleteConsultModal(consult)
                                                "
                                                class="inline-flex items-center justify-center text-center p-2 bg-red-500 border border-transparent rounded-full text-white hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:shadow-outline-red transition ease-in-out duration-150 ml-2"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <button
                                                type="button"
                                                @click="
                                                    openReviewModal(consult)
                                                "
                                                class="inline-flex items-center justify-center text-center p-2 bg-blue-500 border border-transparent rounded-full text-white hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:shadow-outline-blue transition ease-in-out duration-150 ml-2"
                                            >
                                                <i class="fas fa-star"></i>
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
                            Nu sunt înregistrate servicii momentan.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="modalEditConsult" @close="closeModalEdit" :maxWidth="lg">
            <div class="p-6">
                <form @submit.prevent="submit">
                    <!-- Medic -->
                    <div>
                        <InputLabel for="doctor" value="Medic" />
                        <select
                            id="doctor"
                            class="mt-1 block w-full"
                            v-model="form.doctor"
                            required
                            style="border-radius: 5px; border-color: lightgray"
                        >
                            <option value="">Selectează medicul</option>
                            <option
                                v-for="doctor in props.doctors"
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

                    <!-- Service, Price, and Time -->
                    <div class="mt-4 flex flex-col space-y-4">
                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <InputLabel
                                    for="service"
                                    value="Serviciul oferit"
                                />
                                <TextInput
                                    id="service"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.service"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.service"
                                />
                            </div>

                            <div class="flex-1">
                                <InputLabel for="price" value="Preț" />
                                <NumberInput
                                    id="price"
                                    type="number"
                                    step="0.10"
                                    class="mt-1 block w-full"
                                    v-model="form.price"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.price"
                                />
                            </div>

                            <!-- Time -->
                            <div class="flex-1">
                                <InputLabel for="time" value="Timp (minute)" />
                                <select
                                    id="time"
                                    class="mt-1 block w-full"
                                    v-model="form.time"
                                    required
                                    style="
                                        border-radius: 5px;
                                        border-color: lightgray;
                                    "
                                >
                                    <option value="">Selectează timpul</option>
                                    <option
                                        v-for="minute in 60"
                                        :key="minute"
                                        :value="minute"
                                    >
                                        {{ minute }}
                                    </option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.time"
                                />
                            </div>
                        </div>

                        <!-- Descrierea serviciului -->
                        <div>
                            <InputLabel
                                for="description"
                                value="Descrierea serviciului"
                            />
                            <TextareaInput
                                id="description"
                                type="textarea"
                                class="mt-1 block w-full"
                                v-model="form.description"
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.description"
                            />
                        </div>
                    </div>

                    <!-- Adresa completa-->
                    <div class="mt-4">
                        <InputLabel for="address" value="Adresa completă" />
                        <TextInput
                            id="address"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.address"
                            required
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.address"
                        />
                    </div>

                    <!-- Oraș și Județ -->
                    <div class="mt-4 flex space-x-4">
                        <div class="flex-1">
                            <InputLabel for="county" value="Județ" />
                            <select
                                id="county"
                                class="mt-1 block w-full"
                                v-model="form.county"
                                required
                                style="
                                    border-radius: 5px;
                                    border-color: lightgray;
                                "
                            >
                                <option value="">Selectează județul</option>
                                <option
                                    v-for="county in props.counties"
                                    :key="county.id"
                                    :value="county.id"
                                >
                                    {{ county.name }}
                                </option>
                            </select>
                            <InputError
                                class="mt-2"
                                :message="form.errors.county"
                            />
                        </div>

                        <div class="flex-1">
                            <InputLabel for="city" value="Oraș" />
                            <select
                                id="city"
                                class="mt-1 block w-full"
                                v-model="form.city"
                                required
                                style="
                                    border-radius: 5px;
                                    border-color: lightgray;
                                "
                            >
                                <option value="">Selectează orașul</option>
                                <option
                                    v-for="city in cities"
                                    :key="city.id"
                                    :value="city.id"
                                >
                                    {{ city.name }}
                                </option>
                            </select>
                            <InputError
                                class="mt-2"
                                :message="form.errors.city"
                            />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton
                            class="ms-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Salvează
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="modalDeleteConsult" @close="closeModalDelete">
            <header>
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Ești sigur că vrei să ștergi următorul serviciu?
                    </h2>

                    <form>
                        <!-- Medic -->
                        <div>
                            <InputLabel for="doctor" value="Medic" />
                            <TextInput
                                id="doctor"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.doctor"
                                readonly
                                autofocus
                            />
                        </div>

                        <!-- Service, Price, and Time -->
                        <div class="mt-4 flex flex-col space-y-4">
                            <div class="flex space-x-4">
                                <div class="flex-1">
                                    <InputLabel
                                        for="service"
                                        value="Serviciul oferit"
                                    />
                                    <TextInput
                                        id="service"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.service"
                                        readonly
                                    />
                                </div>

                                <div class="flex-1">
                                    <InputLabel for="price" value="Preț" />
                                    <NumberInput
                                        id="price"
                                        type="number"
                                        step="0.10"
                                        class="mt-1 block w-full"
                                        v-model="form.price"
                                        readonly
                                    />
                                </div>

                                <!-- Time -->
                                <div class="flex-1">
                                    <InputLabel
                                        for="time"
                                        value="Timp (minute)"
                                    />
                                    <TextInput
                                        id="time"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.time"
                                        readonly
                                    />
                                </div>
                            </div>

                            <!-- Descrierea serviciului -->
                            <div>
                                <InputLabel
                                    for="description"
                                    value="Descrierea serviciului"
                                />
                                <TextareaInput
                                    id="description"
                                    type="textarea"
                                    class="mt-1 block w-full"
                                    v-model="form.description"
                                    readonly
                                />
                            </div>
                        </div>

                        <!-- Adresa completa-->
                        <div class="mt-4">
                            <InputLabel for="address" value="Adresa completă" />
                            <TextInput
                                id="address"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.address"
                                readonly
                            />
                        </div>

                        <!-- Oraș și Județ -->
                        <div class="mt-4 flex space-x-4">
                            <div class="flex-1">
                                <InputLabel for="county" value="Județ" />
                                <TextInput
                                    id="address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.county"
                                    readonly
                                />
                            </div>

                            <div class="flex-1">
                                <InputLabel for="city" value="Oraș" />
                                <TextInput
                                    id="address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.city"
                                    readonly
                                />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <DangerButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                                @click="deleteConsult"
                            >
                                Șterge
                            </DangerButton>

                            <SecondaryButton
                                @click="closeModalDelete"
                                class="ms-3"
                            >
                                Închide
                            </SecondaryButton>
                        </div>
                    </form>
                </div>
            </header>
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
