<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { defineProps, ref, computed } from "vue";
import Modal from "@/Components/Modal.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import toastr from "toastr";
import "toastr/build/toastr.min.css";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import FileInput from "@/Components/FileInput.vue";
import ImageDisplay from "@/Components/ImageDisplay.vue";

const props = defineProps({
    doctors: Array,
});

const modalEditDoctor = ref(false);
const modalDeleteDoctor = ref(false);
const currentPage = ref(1);
const itemsPerPageOptions = [15, 25, 50, 100];
const itemsPerPage = ref(itemsPerPageOptions[0]);

const form = useForm({
    doctor: "",
    image: null,
    description: "",
    workingDays: {
        Luni: { enabled: false, start_time: "", end_time: "" },
        Marți: { enabled: false, start_time: "", end_time: "" },
        Miercuri: { enabled: false, start_time: "", end_time: "" },
        Joi: { enabled: false, start_time: "", end_time: "" },
        Vineri: { enabled: false, start_time: "", end_time: "" },
        Sâmbătă: { enabled: false, start_time: "", end_time: "" },
        Duminică: { enabled: false, start_time: "", end_time: "" },
    },
    id: "",
    errors: {
        doctor: "",
        workingDays: "",
        image: "",
        description: "",
    },
});

const image = ref("");
const imageSrc = ref(null);

const onFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imageSrc.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const paginatedDoctors = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return props.doctors.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(props.doctors.length / itemsPerPage.value);
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

const closeModalEdit = () => {
    form.image = null;
    imageSrc.value = null;
    modalEditDoctor.value = false;
};

const closeModalDelete = () => {
    modalDeleteDoctor.value = false;
};

const editDoctor = (doctor) => {
    form.id = doctor.id;
    form.doctor = doctor.name;
    image.value = doctor.image;
    form.description = doctor.description;
    form.workingDays = { ...form.workingDays };

    Object.keys(form.workingDays).forEach((day) => {
        if (doctor.working_days_modified[day]) {
            form.workingDays[day] = {
                ...doctor.working_days_modified[day],
                enabled:
                    doctor.working_days_modified[day].start_time !== null &&
                    doctor.working_days_modified[day].end_time !== null,
            };
        }
    });

    modalEditDoctor.value = true;
};

const deleteDoctorModal = (doctor) => {
    form.id = doctor.id;
    form.doctor = doctor.name;
    form.description = doctor.description;
    image.value = doctor.image;
    form.workingDays = { ...form.workingDays };

    Object.keys(form.workingDays).forEach((day) => {
        if (doctor.working_days_modified[day]) {
            form.workingDays[day] = {
                ...doctor.working_days_modified[day],
                enabled:
                    doctor.working_days_modified[day].start_time !== null &&
                    doctor.working_days_modified[day].end_time !== null,
            };
        }
    });

    modalDeleteDoctor.value = true;
};

const submit = () => {
    form.post(route("consult.medic.update", { id: form.id }), {
        preserveScroll: true,
        data: {
            ...form,
        },
        onSuccess: () => {
            form.reset();
            modalEditDoctor.value = false;
            showNotification("Medicul a fost salvat");
        },
        onError: () => {
            showNotification("Rezolvă problemele înainte de a salva.", "error");
        },
    });
};

const showNotification = (message, type = "success") => {
    toastr[type](message);
};

const deleteMedic = () => {
    form.delete(route("consult.medic.destroy"), {
        onFinish: () => {
            form.reset();
            modalDeleteDoctor.value = false;
            showNotification("Medicul a fost șters");
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
                        <div v-if="doctors.length > 0" class="overflow-x-auto">
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
                                            Program
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
                                        v-for="doctor in paginatedDoctors"
                                        :key="doctor.id"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ doctor.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            <p
                                                v-for="(
                                                    schedule, day
                                                ) in doctor.working_days_modified"
                                                :key="day"
                                            >
                                                {{ day }}:
                                                {{ schedule.start_time }} -
                                                {{ schedule.end_time }}
                                            </p>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            <button
                                                type="button"
                                                @click="editDoctor(doctor)"
                                                class="inline-flex items-center justify-center text-center p-2 bg-[#F4A261] border border-transparent rounded-full text-white hover:bg-[#F4A261] active:bg-[#F4A261] focus:outline-none focus:border-[#F4A261] focus:shadow-outline-[#F4A261] transition ease-in-out duration-150 ml-2"
                                            >
                                                <i class="fas fa-pencil"></i>
                                            </button>
                                            <button
                                                type="button"
                                                @click="
                                                    deleteDoctorModal(doctor)
                                                "
                                                class="inline-flex items-center justify-center text-center p-2 bg-[#dc3545] border border-transparent rounded-full text-white hover:bg-[#dc3545] active:bg-[#dc3545] focus:outline-none focus:border-[#dc3545] focus:shadow-outline-[#dc3545] transition ease-in-out duration-150 ml-2"
                                            >
                                                <i class="fas fa-trash-alt"></i>
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
                                            ><<</a
                                        >
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
                                            ><</a
                                        >
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
                                            >{{ page }}</a
                                        >
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
                                            >></a
                                        >
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
                                            >>></a
                                        >
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
                        <div v-else>Nu sunt înregistrați medici momentan.</div>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="modalEditDoctor" @close="closeModalEdit">
            <div class="p-6">
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <!-- Nume si prenume -->
                            <div>
                                <InputLabel for="doctor" value="Nume complet" />
                                <TextInput
                                    id="doctor"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.doctor"
                                    autofocus
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.doctor"
                                />
                            </div>

                            <!-- Programul de lucru -->
                            <div class="mt-4">
                                <InputLabel
                                    for="workingDays"
                                    value="Programul de lucru"
                                />
                                <div
                                    v-for="(schedule, day) in form.workingDays"
                                    :key="day"
                                    class="mt-4"
                                >
                                    <div class="flex items-center">
                                        <input
                                            type="checkbox"
                                            :id="day"
                                            v-model="
                                                form.workingDays[day].enabled
                                            "
                                            class="form-checkbox h-5 w-5 text-indigo-600"
                                        />
                                        <label
                                            :for="day"
                                            class="ml-3 block text-sm font-medium text-gray-700"
                                            >{{ day }}</label
                                        >
                                    </div>
                                    <div
                                        v-if="form.workingDays[day].enabled"
                                        class="flex mt-2 space-x-4"
                                    >
                                        <input
                                            type="time"
                                            v-model="
                                                form.workingDays[day].start_time
                                            "
                                            class="form-input block w-full sm:text-sm border-gray-300 rounded-md"
                                        />
                                        <input
                                            type="time"
                                            v-model="
                                                form.workingDays[day].end_time
                                            "
                                            class="form-input block w-full sm:text-sm border-gray-300 rounded-md"
                                        />
                                    </div>
                                </div>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.workingDays"
                                />
                            </div>
                        </div>

                        <div>
                            <!-- Description -->
                            <div>
                                <InputLabel
                                    for="description"
                                    value="Descriere"
                                />
                                <TextareaInput
                                    id="description"
                                    class="mt-1 block w-full"
                                    v-model="form.description"
                                    rows="4"
                                ></TextareaInput>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.description"
                                />
                            </div>

                            <!-- Image -->
                            <div class="mt-4">
                                <InputLabel for="image" value="Imagine" />
                                <FileInput
                                    id="image"
                                    v-model="form.image"
                                    @change="onFileChange"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.image"
                                />
                            </div>

                            <!-- Display Image if Exists -->
                            <div class="mt-4">
                                <ImageDisplay
                                    v-if="imageSrc || image"
                                    :src="
                                        imageSrc
                                            ? imageSrc
                                            : `/storage/${image}`
                                    "
                                    maxWidth="200px"
                                    maxHeight="200px"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton
                            class="ms-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            >Salvează</PrimaryButton
                        >
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="modalDeleteDoctor" @close="closeModalDelete">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Ești sigur că vrei să ștergi următorul medic?
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <!-- First Column -->
                    <div>
                        <!-- Doctor name -->
                        <div>
                            <p
                                class="mt-1 block w-full text-gray-700 font-semibold"
                            >
                                {{ form.doctor }}
                            </p>
                        </div>
                        <!-- Working Days -->
                        <div>
                            <div
                                v-for="(schedule, day) in form.workingDays"
                                :key="day"
                                class="mt-2"
                            >
                                <p class="text-gray-700">
                                    {{ day }}:
                                    <span v-if="form.workingDays[day].enabled">
                                        {{ form.workingDays[day].start_time }} -
                                        {{ form.workingDays[day].end_time }}
                                    </span>
                                    <span v-else>-</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Second Column -->
                    <div>
                        <!-- Description -->
                        <div v-if="form.description">
                            <p class="mt-1 block w-full text-gray-700">
                                {{ form.description }}
                            </p>
                        </div>
                        <!-- Display Image if Exists -->
                        <div v-if="image" class="mt-4">
                            <ImageDisplay
                                :src="`/storage/${image}`"
                                maxWidth="200px"
                                maxHeight="200px"
                            />
                        </div>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end">
                    <DangerButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteMedic"
                    >
                        Șterge
                    </DangerButton>
                    <SecondaryButton @click="closeModalDelete" class="ms-3">
                        Închide
                    </SecondaryButton>
                </div>
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
</style>
