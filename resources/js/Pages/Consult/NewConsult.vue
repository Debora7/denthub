<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    status: {
        type: String,
    },
    counties: Array,
});

const form = useForm({
    doctor: "",
    address: "",
    city: "",
    county: "",
    services: [
        {
            service: "",
            price: 0,
            description: "",
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
        price: 0,
        description: "",
    });
};

const submit = () => {
    form.post(route("consult.store"), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <!-- Medic -->
                            <div>
                                <InputLabel for="doctor" value="Medic" />

                                <TextInput
                                    id="doctor"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.doctor"
                                    required
                                    autofocus
                                />

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
                                        />
                                        <TextInput
                                            :id="`service-${index}`"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="service.service"
                                            required
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
                                        />
                                        <NumberInput
                                            :id="`price-${index}`"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="service.price"
                                            required
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
                                </div>

                                <!-- Descrierea serviciului -->
                                <div>
                                    <InputLabel
                                        :for="`description-${index}`"
                                        value="Descrierea serviciului"
                                    />
                                    <TextareaInput
                                        :id="`description-${index}`"
                                        type="textarea"
                                        class="mt-1 block w-full"
                                        v-model="service.description"
                                        required
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
                                />

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