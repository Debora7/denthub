<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { defineProps } from "vue";

const props = defineProps({
    consults: Array,
});
</script>

<template>
    <Head title="Servicii" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="consults.length > 0">
                    <div
                        class="card mb-2"
                        v-for="consult in consults"
                        :key="consult.id"
                    >
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h3 class="card-title">
                                        {{ consult.doctor }}
                                    </h3>
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        {{ consult.service }}
                                    </h6>
                                    <p class="card-text">
                                        {{ consult.description }}
                                    </p>
                                    <p class="card-text text-muted">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ consult.county.name }},
                                        {{ consult.city.name }},
                                        {{ consult.address }}
                                    </p>
                                </div>
                                <div
                                    class="col-md-4"
                                    style="text-align: center"
                                >
                                    <p
                                        class="card-text"
                                        style="font-size: 20px"
                                    >
                                        <strong>Preț:</strong>
                                        {{
                                            parseFloat(consult.price).toFixed(2)
                                        }}
                                        Lei
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6 text-gray-900">
                            Nu sunt înregistrate servicii momentan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
