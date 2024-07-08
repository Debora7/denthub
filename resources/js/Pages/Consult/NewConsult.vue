<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import toastr from "toastr";
import "toastr/build/toastr.min.css";

const props = defineProps({
    status: {
        type: String,
    },
    counties: Array,
    doctors: Array,
});

const form = useForm({
    doctor: "",
    address: "",
    city: "",
    county: "",
    services: [
        {
            service: "",
            price: 0.0,
            description: "",
            time: "",
        },
    ],
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

const addService = () => {
    form.services.push({
        service: "",
        price: 0.0,
        description: "",
        time: "",
    });
};

const submit = () => {
    form.post(route("consult.store"), {
        onSuccess: () => {
            form.reset();
            showNotification("Serviciile au fost salvate");
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
    <Head title="Serviciu nou" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <!-- Medic -->
                            <div>
                                <InputLabel
                                    for="doctor"
                                    value="Medic"
                                    :required="true"
                                />
                                <select
                                    id="doctor"
                                    class="mt-1 block w-full"
                                    v-model="form.doctor"
                                    style="
                                        border-radius: 5px;
                                        border-color: lightgray;
                                    "
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

                            <!-- Service and Price -->
                            <div
                                v-for="(service, index) in form.services"
                                :key="index"
                                class="mt-4 flex flex-col space-y-4"
                            >
                                <div class="flex space-x-4">
                                    <div class="flex-1">
                                        <InputLabel
                                            :for="`service-${index}`"
                                            value="Serviciul oferit"
                                            :required="true"
                                        />
                                        <TextInput
                                            :id="`service-${index}`"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="service.service"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="
                                                form.errors[
                                                    `services.${index}.service`
                                                ]
                                            "
                                        />
                                    </div>

                                    <div class="flex-1">
                                        <InputLabel
                                            :for="`price-${index}`"
                                            value="Preț"
                                            :required="true"
                                        />
                                        <NumberInput
                                            :id="`price-${index}`"
                                            type="number"
                                            step="0.10"
                                            class="mt-1 block w-full"
                                            v-model="service.price"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="
                                                form.errors[
                                                    `services.${index}.price`
                                                ]
                                            "
                                        />
                                    </div>

                                    <div class="flex-1">
                                        <InputLabel
                                            :for="`time-${index}`"
                                            value="Timp (minute)"
                                            :required="true"
                                        />
                                        <select
                                            :id="`time-${index}`"
                                            class="mt-1 block w-full"
                                            v-model="service.time"
                                            style="
                                                border-radius: 5px;
                                                border-color: lightgray;
                                            "
                                        >
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
                                            :message="
                                                form.errors[
                                                    `services.${index}.time`
                                                ]
                                            "
                                        />
                                    </div>
                                </div>

                                <!-- Descrierea serviciului -->
                                <div>
                                    <InputLabel
                                        :for="`description-${index}`"
                                        value="Descrierea serviciului"
                                        :required="true"
                                    />
                                    <TextareaInput
                                        :id="`description-${index}`"
                                        type="textarea"
                                        class="mt-1 block w-full"
                                        v-model="service.description"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="
                                            form.errors[
                                                `services.${index}.description`
                                            ]
                                        "
                                    />
                                </div>
                            </div>

                            <!-- Add new service button -->
                            <div class="mt-4">
                                <button
                                    type="button"
                                    @click="addService"
                                    style="width: 100%"
                                    class="inline-flex items-center justify-center text-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:shadow-outline-blue disabled:opacity-25 transition ease-in-out duration-150"
                                >
                                    <i class="fas fa-plus mr-2"></i>
                                    Adaugă Serviciu
                                </button>
                            </div>

                            <!-- Adresa completa-->
                            <div class="mt-4">
                                <InputLabel
                                    for="address"
                                    value="Adresa completă"
                                    :required="true"
                                />

                                <TextInput
                                    id="address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.address"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.address"
                                />
                            </div>

                            <!-- Oraș și Județ -->
                            <div class="mt-4 flex space-x-4">
                                <div class="flex-1">
                                    <InputLabel
                                        for="county"
                                        value="Județ"
                                        :required="true"
                                    />
                                    <select
                                        id="county"
                                        class="mt-1 block w-full"
                                        v-model="form.county"
                                        style="
                                            border-radius: 5px;
                                            border-color: lightgray;
                                        "
                                    >
                                        <option value="">
                                            Selectează județul
                                        </option>
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
                                    <InputLabel
                                        for="city"
                                        value="Oraș"
                                        :required="true"
                                    />
                                    <select
                                        id="city"
                                        class="mt-1 block w-full"
                                        v-model="form.city"
                                        style="
                                            border-radius: 5px;
                                            border-color: lightgray;
                                        "
                                    >
                                        <option value="">
                                            Selectează orașul
                                        </option>
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
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
