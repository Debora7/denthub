<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Pagina principală" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="container">
                            <section class="mb-5">
                                <h4>Cine Suntem</h4>
                                <p>
                                    DentPortal este o platformă completă de
                                    îngrijire dentară, concepută pentru a
                                    conecta pacienții cu profesioniști dentari
                                    de top. Misiunea noastră este să asigurăm
                                    accesul tuturor la îngrijire dentară de
                                    calitate și informații utile.
                                </p>
                            </section>

                            <section class="mb-5">
                                <h4>Misiunea Noastră</h4>
                                <p>
                                    Să facem îngrijirea dentară accesibilă ca
                                    preț și ușor de înțeles pentru toată lumea.
                                    Ne străduim să oferim cele mai bune resurse
                                    și instrumente atât pentru pacienți, cât și
                                    pentru profesioniștii din domeniul dentar.
                                </p>
                            </section>

                            <section class="mb-5">
                                <h4>Serviciile Noastre</h4>
                                <ul>
                                    <li>
                                        <strong>Programări Dentare:</strong>
                                        Programări ușoare cu dentiști de
                                        încredere.
                                    </li>
                                    <li>
                                        <strong
                                            >Recenzii ale Pacienților:</strong
                                        >
                                        Feedback real de la pacienți reali.
                                    </li>
                                </ul>
                            </section>

                            <section class="mb-5">
                                <h4>Contactați-ne</h4>
                                <p>
                                    Nu ezitați să ne contactați pentru orice
                                    întrebări sau nelămuriri. Suntem aici pentru
                                    a vă ajuta să zâmbiți mai strălucitor în
                                    fiecare zi!
                                </p>
                                <p>
                                    <strong>Email: </strong>
                                    <a href="mailto:info@dentportal.com"
                                        >denthub.contact@yahoo.com</a
                                    >
                                </p>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
