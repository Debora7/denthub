<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link, usePage } from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);

// Access the user's role
const { props } = usePage();
const userRole = props.auth.user.cui;
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link
                                    :href="route('about-us')"
                                    style="text-decoration: none"
                                >
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    v-if="userRole !== null"
                                    :href="
                                        route('consult.medic.appointment.index')
                                    "
                                    :active="
                                        route().current(
                                            'consult.medic.appointment.index'
                                        )
                                    "
                                >
                                    Programări
                                </NavLink>
                                <NavLink
                                    v-if="userRole !== null"
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    Servicii
                                </NavLink>
                                <NavLink
                                    v-if="userRole !== null"
                                    :href="route('consult.index')"
                                    :active="route().current('consult.index')"
                                >
                                    Serviciu nou
                                </NavLink>
                                <NavLink
                                    v-if="userRole !== null"
                                    :href="route('consult.medic.allMedics')"
                                    :active="
                                        route().current(
                                            'consult.medic.allMedics'
                                        )
                                    "
                                >
                                    Medici
                                </NavLink>
                                <NavLink
                                    v-if="userRole !== null"
                                    :href="route('consult.medic.index')"
                                    :active="
                                        route().current('consult.medic.index')
                                    "
                                >
                                    Medic nou
                                </NavLink>
                                <NavLink
                                    v-if="userRole === null"
                                    :href="route('consult.client.index')"
                                    :active="
                                        route().current('consult.client.index')
                                    "
                                >
                                    Servicii
                                </NavLink>
                                <NavLink
                                    v-if="userRole === null"
                                    :href="
                                        route(
                                            'consult.client.appointment.index'
                                        )
                                    "
                                    :active="
                                        route().current(
                                            'consult.client.appointment.index'
                                        )
                                    "
                                >
                                    Programări
                                </NavLink>
                            </div>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ props.auth.user.name }}
                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 111.414 1.414l-4 4a1 1 01-1.414 0l-4-4a1 1 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profil
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1" v-if="userRole !== null">
                        <ResponsiveNavLink
                            :href="route('consult.medic.appointment.index')"
                            :active="
                                route().current(
                                    'consult.medic.appointment.index'
                                )
                            "
                        >
                            Programări
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Servicii
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('consult.index')"
                            :active="route().current('consult.index')"
                        >
                            Serviciu nou
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('consult.medic.allMedics')"
                            :active="route().current('consult.medic.allMedics')"
                        >
                            Medici
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('consult.medic.index')"
                            :active="route().current('consult.medic.index')"
                        >
                            Medic nou
                        </ResponsiveNavLink>
                    </div>

                    <div class="pt-2 pb-3 space-y-1" v-if="userRole === null">
                        <ResponsiveNavLink
                            :href="route('consult.client.index')"
                            :active="route().current('consult.client.index')"
                        >
                            Servicii
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('consult.client.appointment.index')"
                            :active="
                                route().current(
                                    'consult.client.appointment.index'
                                )
                            "
                        >
                            Programări
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">
                                {{ props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profil
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 mt-8">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Contactează-ne
                            </h3>
                            <ul class="mt-2 text-gray-600 list-none pl-0">
                                <li>
                                    <a
                                        href="https://mail.google.com/mail/?view=cm&fs=1&to=denthub.contact@yahoo.com"
                                        class="flex items-center text-gray-600 hover:text-gray-900"
                                        style="text-decoration: none"
                                    >
                                        <i class="fas fa-envelope mr-2"></i>
                                        denthub.contact@yahoo.com
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Linkuri utile
                            </h3>
                            <ul class="mt-2 space-y-2 list-none pl-0">
                                <li>
                                    <a
                                        href="https://www.termsandconditionsgenerator.com/live.php?token=7xcMdXEqs0Q9ceK0Grjbk2fGrZwjvkkM"
                                        class="text-gray-600 hover:text-gray-900"
                                        style="text-decoration: none"
                                    >
                                        Termene și condiții
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="https://www.freeprivacypolicy.com/live/50e07187-a51a-4f7e-84e4-e38f5d6faeb4"
                                        class="text-gray-600 hover:text-gray-900"
                                        style="text-decoration: none"
                                    >
                                        Politica de prelucrare a datelor cu
                                        caracter personal
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>
