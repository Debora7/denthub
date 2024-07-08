<script setup>
import { ref, watch } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const selectedTab = ref("client");

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    cui: "",
    phone: "",
    tab: "client",
});

watch(
    () => selectedTab.value,
    (tab) => {
        form.tab = tab;
    }
);

const submit = () => {
    form.post(route("store"), {
        onFinish: () => {
            if (selectedTab.value === "client") {
                form.reset("password", "password_confirmation");
            } else {
                form.reset("password", "password_confirmation", "cui");
            }
        },
    });
};

const switchTab = (tab) => {
    form.reset();
    form.errors = {};
    selectedTab.value = tab;
};
</script>

<template>
    <GuestLayout>
        <Head title="Crează un cont nou" />

        <div class="tabs">
            <button
                @click="switchTab('client')"
                :class="{ active: selectedTab === 'client' }"
            >
                Persoană fizică
            </button>
            <button
                @click="switchTab('dentist')"
                :class="{ active: selectedTab === 'dentist' }"
            >
                Persoană juridică
            </button>
        </div>

        <form @submit.prevent="submit">
            <template v-if="selectedTab === 'client'">
                <div>
                    <InputLabel
                        for="name"
                        value="Nume complet"
                        :required="true"
                    />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" :required="true" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="phone" value="Telefon" />
                    <div class="flex items-center">
                        <TextInput
                            id="phone"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.phone"
                            maxlength="10"
                            @input="form.phone = form.phone.replace(/\D/g, '')"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.phone" />
                </div>

                <div class="mt-4">
                    <InputLabel
                        for="password"
                        value="Parolă"
                        :required="true"
                    />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel
                        for="password_confirmation"
                        value="Confirmă parola"
                        :required="true"
                    />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        autocomplete="new-password"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>
            </template>

            <template v-if="selectedTab === 'dentist'">
                <div>
                    <InputLabel
                        for="name"
                        value="Nume complet"
                        :required="true"
                    />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="cui" value="CUI" :required="true" />
                    <TextInput
                        id="cui"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.cui"
                        autofocus
                        autocomplete="cui"
                    />
                    <InputError class="mt-2" :message="form.errors.cui" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" :required="true" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="phone" value="Telefon" />
                    <div class="flex items-center">
                        <TextInput
                            id="phone"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.phone"
                            maxlength="10"
                            @input="form.phone = form.phone.replace(/\D/g, '')"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.phone" />
                </div>

                <div class="mt-4">
                    <InputLabel
                        for="password"
                        value="Parolă"
                        :required="true"
                    />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel
                        for="password_confirmation"
                        value="Confirmă parola"
                        :required="true"
                    />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        autocomplete="new-password"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>
            </template>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    style="text-decoration: none"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Ai cont deja? Conectează-te aici!
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Crează cont
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<style scoped>
.tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.tabs button {
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    background-color: #f3f4f6;
    border-radius: 5px;
}

.tabs button.active {
    background-color: #4f46e5;
    color: white;
}
</style>
