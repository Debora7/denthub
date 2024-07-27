<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head } from "@inertiajs/vue3";
import { defineProps, ref, computed, defineEmits, watch } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import "toastr/build/toastr.min.css";
import ModalReview from "@/Components/ModalReview.vue";
import Rating from "primevue/rating";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    consults: Array,
    appointments: Array,
});

const reviews_mapped = ref([]);
const modalReview = ref(false);

const openReviewModal = (consult) => {
    reviews_mapped.value = consult.reviews;

    modalReview.value = true;
};

const closeModalReview = () => {
    modalReview.value = false;
};

const emit = defineEmits(["consult-clicked"]);

const getMapLink = (consult) => {
    const address = consult.address;
    const city = consult.city.name;
    const county = consult.county.name;
    return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
        address + ", " + city + ", " + county
    )}`;
};

const handleClick = (consult) => {
    emit("consult-clicked", consult);
};

const searchQuery = ref("");
const currentPage = ref(1);
const itemsPerPageOptions = [15, 25, 50, 100];
const itemsPerPage = ref(itemsPerPageOptions[0]);

const selectedService = ref("");
const selectedCounty = ref("");
const selectedCity = ref("");
const sortOrder = ref("asc");

const uniqueServices = computed(() => {
    return [...new Set(props.consults.map((consult) => consult.service))];
});

const uniqueCounties = computed(() => {
    return [...new Set(props.consults.map((consult) => consult.county.name))];
});

const uniqueCities = computed(() => {
    return [...new Set(props.consults.map((consult) => consult.city.name))];
});

const filteredConsults = computed(() => {
    const searchLower = searchQuery.value.toLowerCase();
    let consults = props.consults.filter((consult) => {
        const matchesSearch =
            consult.doctor.name.toLowerCase().includes(searchLower) ||
            consult.service.toLowerCase().includes(searchLower) ||
            consult.city.name.toLowerCase().includes(searchLower) ||
            consult.county.name.toLowerCase().includes(searchLower);
        const matchesService = selectedService.value
            ? consult.service === selectedService.value
            : true;
        const matchesCounty = selectedCounty.value
            ? consult.county.name === selectedCounty.value
            : true;
        const matchesCity = selectedCity.value
            ? consult.city.name === selectedCity.value
            : true;
        return matchesSearch && matchesService && matchesCounty && matchesCity;
    });

    if (sortOrder.value === "asc") {
        consults = consults.sort((a, b) => a.price - b.price);
    } else {
        consults = consults.sort((a, b) => b.price - a.price);
    }

    return consults.map((consult) => {
        const totalRating = consult.reviews.reduce(
            (acc, review) => acc + review.rating,
            0
        );
        const averageRating =
            consult.reviews.length > 0
                ? totalRating / consult.reviews.length
                : 0;
        return { ...consult, averageRating };
    });
});

const paginatedConsults = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredConsults.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredConsults.value.length / itemsPerPage.value);
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

const toggleSortOrder = () => {
    sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
};
</script>

<template>
    <Head title="Servicii" />

    <GuestLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-4">
                    <div class="input-group mb-3">
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
                    <div class="d-flex justify-content-between mb-3">
                        <select
                            v-model="selectedService"
                            class="form-select me-2"
                            aria-label="Select Service"
                        >
                            <option value="">Serviciu</option>
                            <option
                                v-for="service in uniqueServices"
                                :key="service"
                                :value="service"
                            >
                                {{ service }}
                            </option>
                        </select>
                        <select
                            v-model="selectedCounty"
                            class="form-select me-2"
                            aria-label="Select County"
                        >
                            <option value="">Județ</option>
                            <option
                                v-for="county in uniqueCounties"
                                :key="county"
                                :value="county"
                            >
                                {{ county }}
                            </option>
                        </select>
                        <select
                            v-model="selectedCity"
                            class="form-select me-2"
                            aria-label="Select City"
                        >
                            <option value="">Oraș</option>
                            <option
                                v-for="city in uniqueCities"
                                :key="city"
                                :value="city"
                            >
                                {{ city }}
                            </option>
                        </select>
                        <button
                            class="btn btn-outline-secondary"
                            @click="toggleSortOrder"
                            :aria-label="`Sort by price ${
                                sortOrder === 'asc' ? 'ascending' : 'descending'
                            }`"
                        >
                            <i
                                :class="
                                    sortOrder === 'asc'
                                        ? 'fas fa-arrow-up'
                                        : 'fas fa-arrow-down'
                                "
                            ></i>
                        </button>
                    </div>
                </div>
                <div v-if="paginatedConsults.length > 0">
                    <div
                        class="card mb-4 border-gray-200 shadow-lg"
                        v-for="consult in paginatedConsults"
                        :key="consult.id"
                    >
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h3 class="card-title">
                                        Dr. {{ consult.doctor.name }}
                                    </h3>
                                    <h4 class="card-subtitle mb-2 text-muted">
                                        {{ consult.service }}
                                    </h4>
                                    <p class="card-text">
                                        {{ consult.description }}
                                    </p>
                                    <p class="card-text text-muted">
                                        <a
                                            class="text-muted"
                                            style="text-decoration: none"
                                            :href="getMapLink(consult)"
                                            target="_blank"
                                            @click="handleClick(consult)"
                                        >
                                            <i
                                                class="fas fa-map-marker-alt"
                                            ></i>
                                            {{ consult.county.name }},
                                            {{ consult.city.name }},
                                            {{ consult.address }}
                                        </a>
                                    </p>
                                </div>
                                <div
                                    class="col-md-4 d-flex flex-column justify-content-between mt-4 md:mt-0"
                                    style="text-align: left"
                                >
                                    <div @click="openReviewModal(consult)">
                                        <Rating
                                            v-model="consult.averageRating"
                                            class="block w-full mb-2"
                                            :stars="5"
                                            readonly
                                        />
                                    </div>
                                    <p
                                        class="card-text"
                                        style="
                                            font-size: 20px;
                                            position: relative;
                                        "
                                    >
                                        <i
                                            class="fas fa-info-circle info-icon"
                                            title="Prețul poate varia în funcție de complexitatea situației"
                                        ></i>
                                        <strong>Preț:</strong>

                                        {{ consult.price }} Lei
                                        <span class="tooltip-text"
                                            >Prețul poate varia în funcție de
                                            complexitatea situației</span
                                        >
                                    </p>

                                    <div>
                                        <Link
                                            :href="route('login')"
                                            style="text-decoration: none"
                                        >
                                            <PrimaryButton>
                                                Fă o programare
                                            </PrimaryButton>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <nav
                        class="d-flex justify-content-center align-items-center"
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
                                >
                                    <<
                                </a>
                            </li>
                            <li
                                class="page-item"
                                :class="{ disabled: currentPage === 1 }"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="changePage(currentPage - 1)"
                                >
                                    <
                                </a>
                            </li>
                            <li
                                class="page-item"
                                v-for="page in totalPages"
                                :key="page"
                                :class="{ active: page === currentPage }"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="changePage(page)"
                                >
                                    {{ page }}
                                </a>
                            </li>
                            <li
                                class="page-item"
                                :class="{
                                    disabled: currentPage === totalPages,
                                }"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="changePage(currentPage + 1)"
                                >
                                    >
                                </a>
                            </li>
                            <li
                                class="page-item"
                                :class="{
                                    disabled: currentPage === totalPages,
                                }"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="changePage(totalPages)"
                                >
                                    >>
                                </a>
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

        <ModalReview
            :show="modalReview"
            :reviews="reviews_mapped"
            @close="closeModalReview"
        />
    </GuestLayout>
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

.card {
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
}

.card-title {
    font-size: 1.25rem;
}

.card-subtitle {
    font-size: 1rem;
}

.card-text {
    font-size: 1rem;
    position: relative;
}

.info-icon {
    color: #e63946;
    font-size: 10px;
    vertical-align: super;
    margin-right: 2px;
    cursor: pointer;
}

.tooltip-text {
    visibility: hidden;
    width: 300px;
    background-color: #f4a261;
    color: #2d2d2d;
    text-align: center;
    border-radius: 5px;
    padding: 5px;
    position: absolute;
    z-index: 1;
    bottom: 150%;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 0.9rem;
}

.tooltip-text::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #f4a261 transparent transparent transparent;
}

.card-text:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}
</style>
