<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import FileInput from "@/Components/FileInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import toastr from "toastr";
import "toastr/build/toastr.min.css";
import { ref } from "vue";
import ImageDisplay from "@/Components/ImageDisplay.vue";

const imageSrc = ref(null);

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
    errors: {
        doctor: "",
        image: "",
        description: "",
        workingDays: "",
    },
});

const daysOfWeek = [
    "Luni",
    "Marți",
    "Miercuri",
    "Joi",
    "Vineri",
    "Sâmbătă",
    "Duminică",
];

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imageSrc.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

const submit = () => {
    form.post(route("consult.medic.store"), {
        onSuccess: () => {
            form.reset();
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
</script>
<template>
    <Head title="Medic nou" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="flex flex-col lg:flex-row lg:space-x-4">
                                <!-- Column 1 -->
                                <div class="flex-1">
                                    <!-- Nume si prenume -->
                                    <div>
                                        <InputLabel
                                            for="doctor"
                                            value="Nume și prenume"
                                            :required="true"
                                        />
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
                                            v-for="(day, index) in daysOfWeek"
                                            :key="index"
                                            class="mt-4"
                                        >
                                            <div class="flex items-center">
                                                <input
                                                    type="checkbox"
                                                    :id="day"
                                                    v-model="
                                                        form.workingDays[day]
                                                            .enabled
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
                                                v-if="
                                                    form.workingDays[day]
                                                        .enabled
                                                "
                                                class="flex mt-2 space-x-4"
                                            >
                                                <input
                                                    type="time"
                                                    v-model="
                                                        form.workingDays[day]
                                                            .start_time
                                                    "
                                                    class="form-input block w-full sm:text-sm border-gray-300 rounded-md"
                                                />
                                                <input
                                                    type="time"
                                                    v-model="
                                                        form.workingDays[day]
                                                            .end_time
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

                                <!-- Column 2 -->
                                <div class="flex-1">
                                    <!-- Description -->
                                    <div class="lg:mt-0">
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
                                        <InputLabel
                                            for="image"
                                            value="Imagine"
                                        />
                                        <FileInput
                                            id="image"
                                            @change="previewImage"
                                            v-model="form.image"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.image"
                                        />
                                    </div>

                                    <!-- Display Image if Exists -->
                                    <div class="mt-4">
                                        <ImageDisplay
                                            :src="
                                                imageSrc
                                                    ? imageSrc
                                                    : `/storage/no-image.jpg`
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
                                >
                                    Salvează
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
