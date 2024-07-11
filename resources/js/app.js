import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp, Link, Head } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import PrimeVue from "primevue/config";
import axios from './axios'; // Import your axios instance
import { Inertia } from '@inertiajs/inertia';
import { InertiaProgress } from '@inertiajs/progress';

import "bootstrap/dist/css/bootstrap.min.css"; // Bootstrap CSS
import "bootstrap/dist/js/bootstrap.bundle.min"; // Bootstrap JS

const appName = import.meta.env.VITE_APP_NAME || "DentPortal";

// Set up Inertia to use Axios for requests
Inertia.on('before', (event) => {
  event.detail.visit.axios = axios;
});

createInertiaApp({
    title: (title) => `${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.component('Link', Link);
        app.component('Head', Head);
        app.use(plugin);
        app.use(ZiggyVue);
        app.use(PrimeVue);
        
        // Set Axios as a global property
        app.config.globalProperties.$axios = axios;

        app.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});

InertiaProgress.init({
  color: "#4B5563",
});
