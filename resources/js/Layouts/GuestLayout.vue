<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
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
                                    :href="route('homepage')"
                                    style="text-decoration: none"
                                >
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800"
                                    />
                                </Link>
                            </div>
                        </div>
                        <div class="hidden sm:flex sm:items-center">
                            <div class="ms-3 relative">
                                <div
                                    class="font-medium text-base text-gray-800"
                                >
                                    <Link
                                        :href="route('login')"
                                        style="
                                            text-decoration: none;
                                            color: black;
                                        "
                                    >
                                        Login
                                    </Link>
                                </div>
                            </div>
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
