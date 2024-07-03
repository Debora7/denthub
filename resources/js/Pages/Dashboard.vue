<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, defineProps, computed } from "vue";

const props = defineProps({
    consults: Array,
});

const deleteConsult = (id) => {
    if (confirm("Ești sigur că vrei să ștergi serviciul?")) {
        router.delete(route("consult.destroy", id), {
            onSuccess: () => {
                alert("Serviciul a fost șters cu succes!");
            },
        });
    }
};

const currentPage = ref(1);
const itemsPerPageOptions = [1, 2, 3, 4, 5];
const itemsPerPage = ref(itemsPerPageOptions[0]);

const paginatedConsults = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return props.consults.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(props.consults.length / itemsPerPage.value);
});

const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

const changeItemsPerPage = (event) => {
    itemsPerPage.value = parseInt(event.target.value);
    currentPage.value = 1; // Reset to first page on items per page change
};
</script>

<template>
    <Head title="Servicii" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="paginatedConsults.length > 0">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Medic
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Serviciul oferit
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Preț
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Adresa completă
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Oraș
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Județ
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Acțiuni
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="consult in paginatedConsults"
                                        :key="consult.id"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ consult.doctor }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ consult.service }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ consult.price }} Lei
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ consult.address }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ consult.city.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ consult.county.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            <button
                                                type="button"
                                                @click="editConsult(consult.id)"
                                                class="inline-flex items-center justify-center text-center p-2 bg-yellow-500 border border-transparent rounded-full text-white hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:shadow-outline-red transition ease-in-out duration-150 ml-2"
                                            >
                                                <i class="fas fa-pencil"></i>
                                            </button>

                                            <button
                                                type="button"
                                                @click="
                                                    deleteConsult(consult.id)
                                                "
                                                class="inline-flex items-center justify-center text-center p-2 bg-red-500 border border-transparent rounded-full text-white hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:shadow-outline-red transition ease-in-out duration-150 ml-2"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <nav
                                class="d-flex justify-content-center align-items-center mt-4"
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
                                            @click.prevent="
                                                changePage(currentPage - 1)
                                            "
                                        >
                                            <
                                        </a>
                                    </li>
                                    <li
                                        class="page-item"
                                        v-for="page in totalPages"
                                        :key="page"
                                        :class="{
                                            active: page === currentPage,
                                        }"
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
                                            disabled:
                                                currentPage === totalPages,
                                        }"
                                    >
                                        <a
                                            class="page-link"
                                            href="#"
                                            @click.prevent="
                                                changePage(currentPage + 1)
                                            "
                                        >
                                            >
                                        </a>
                                    </li>
                                    <li
                                        class="page-item"
                                        :class="{
                                            disabled:
                                                currentPage === totalPages,
                                        }"
                                    >
                                        <a
                                            class="page-link"
                                            href="#"
                                            @click.prevent="
                                                changePage(totalPages)
                                            "
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
                            Nu sunt înregistrate servicii momentan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.custom-pagination .page-item .page-link {
    border: none;
    background-color: transparent;
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
</style>
