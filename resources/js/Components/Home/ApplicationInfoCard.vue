<script setup>
import Card from 'primevue/card';
import Divider from 'primevue/divider';
import Tag from 'primevue/tag';

const technologies = [
    'Laravel 13',
    'PHP 8.5',
    'Vue 3',
    'Inertia.js',
    'PrimeVue 4',
    'Tailwind CSS 4',
    'Vite',
    'PostgreSQL',
    'Laravel Sail',
    'PokéAPI',
];
</script>

<template>
    <Card class="border border-surface-200 shadow-sm">
        <template #title>
            <div class="flex items-center gap-3">
                <i class="pi pi-desktop text-primary" />
                Aplicação
            </div>
        </template>

        <template #content>
            <div class="space-y-5">
                <div>
                    <span class="text-sm font-medium text-surface-500">Endereço de acesso</span>
                    <a
                        class="mt-1 block break-all text-primary hover:underline"
                        href="http://ateliware.localhost"
                    >
                        http://ateliware.localhost
                    </a>
                </div>

                <Divider />

                <div>
                    <span class="text-sm font-medium text-surface-500">Tecnologias usadas</span>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <Tag
                            v-for="technology in technologies"
                            :key="technology"
                            :value="technology"
                            severity="secondary"
                        />
                    </div>
                </div>
            </div>
        </template>
    </Card>
</template>
