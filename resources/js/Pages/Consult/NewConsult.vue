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
    service: "",
    description: "",
    price: 0,
    address: "",
    city: "",
    county: "",
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
                            <div class="mt-4 flex space-x-4">
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
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.price"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.price"
                                    />
                                </div>
                            </div>

                            <!-- Descrierea serviciului -->
                            <div class="mt-4">
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
