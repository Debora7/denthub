<script setup>
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Șterge contul</h2>

            <p class="mt-1 text-sm text-gray-600">
                Ștergerea contului va duce la pierderea tuturor datelor asociate
                acestuia. Odată șters contul nu va mai putea fi recuperat.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Șterge contul</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Ești sigur că vrei să ștergi contul?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Ștergerea contului va duce la pierderea tuturor datelor
                    asociate acestuia. Odată șters contul nu va mai putea fi
                    recuperat.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Parola" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Parola"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <DangerButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Șterge
                    </DangerButton>

                    <SecondaryButton @click="closeModal" class="ms-3">
                        Închide
                    </SecondaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
