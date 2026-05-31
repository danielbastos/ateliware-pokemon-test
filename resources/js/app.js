import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';

createInertiaApp({
    resolve: (name) => resolvePageComponent(
        [
            `./Pages/${name}.vue`,
            `../../packages/pokebattle/resources/js/Pages/${name}.vue`,
        ],
        {
            ...import.meta.glob('./Pages/**/*.vue'),
            ...import.meta.glob('../../packages/pokebattle/resources/js/Pages/**/*.vue'),
        },
    ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                    options: {
                        darkModeSelector: 'system',
                    },
                },
            })
            .mount(el);
    },
});
