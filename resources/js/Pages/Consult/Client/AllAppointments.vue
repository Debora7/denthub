<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps(["appointments"]);

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

const appointments = computed(() =>
    props.appointments.map((appointment) => ({
        ...appointment,
        formatted_date: formatDate(appointment.appointment_date),
    }))
);
</script>

<template>
    <Head title="Programari" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="appointments.length === 0">
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6 text-gray-900">
                            Nu aveți programări făcute.
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div
                        v-for="appointment in appointments"
                        :key="appointment.id"
                        class="card mb-2"
                    >
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div>
                                        <h3 class="card-title">
                                            Dr. {{ appointment.consult.doctor }}
                                        </h3>
                                        <p></p>
                                        <h4
                                            class="card-subtitle mb-2 text-muted"
                                        >
                                            {{ appointment.consult.service }}
                                        </h4>
                                    </div>
                                    <div>
                                        <p class="card-text">
                                            {{
                                                appointment.consult.description
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="col-md-6 d-flex flex-column justify-content-between"
                                    style="text-align: left"
                                >
                                    <p
                                        class="card-text"
                                        style="font-size: 20px"
                                    >
                                        <strong
                                            >Data și ora programării:</strong
                                        >
                                        {{ appointment.formatted_date }}
                                    </p>
                                    <p
                                        class="card-text"
                                        style="font-size: 20px"
                                    >
                                        <strong>Preț:</strong>
                                        {{ appointment.consult.price }} Lei
                                    </p>
                                    <p class="card-text">
                                        <span
                                            :class="[
                                                'badge',
                                                appointment.status ===
                                                'Confirmată'
                                                    ? 'badge-success'
                                                    : 'badge-danger',
                                            ]"
                                            style="font-size: 20px"
                                        >
                                            {{ appointment.status }}
                                        </span>
                                    </p>
                                    <div class="mt-6 flex justify-end">
                                        <SecondaryButton>
                                            Editează
                                        </SecondaryButton>

                                        <DangerButton class="ms-3">
                                            Șterge
                                        </DangerButton>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style>
.badge {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.375rem;
}

.badge-success {
    color: #000;
    font-weight: normal;
    background-color: #28a745;
}

.badge-danger {
    color: #000;
    font-weight: normal;
    background-color: #dc3545;
}
</style>
