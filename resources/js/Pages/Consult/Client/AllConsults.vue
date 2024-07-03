<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { defineProps, ref, computed } from "vue";

const props = defineProps({
    consults: Array,
});

const searchQuery = ref("");

const filteredConsults = computed(() => {
    return props.consults.filter((consult) => {
        const searchLower = searchQuery.value.toLowerCase();
        return (
            consult.doctor.toLowerCase().includes(searchLower) ||
            consult.service.toLowerCase().includes(searchLower) ||
            consult.city.name.toLowerCase().includes(searchLower) ||
            consult.county.name.toLowerCase().includes(searchLower)
        );
    });
});
</script>

<template>
    <Head title="Servicii" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-4">
                    <div class="input-group">
                        <span
                            class="input-group-text"
                            id="basic-addon1"
                            style="background-color: white"
                        >
                            <i class="fas fa-search"></i>
                        </span>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Caută"
                            class="form-control"
                            aria-label="Search"
                            aria-describedby="basic-addon1"
                        />
                    </div>
                </div>
                <div v-if="filteredConsults.length > 0">
                    <div
                        class="card mb-2"
                        v-for="consult in filteredConsults"
                        :key="consult.id"
                    >
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h3 class="card-title">
                                        Dr. {{ consult.doctor }}
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
                                        {{ consult.price }}
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
