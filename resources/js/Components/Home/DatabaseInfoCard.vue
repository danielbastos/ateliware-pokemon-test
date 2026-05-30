<script setup>
import Card from 'primevue/card';
import Divider from 'primevue/divider';

const databaseCredentials = [
    { label: 'Host', value: 'pgsql' },
    { label: 'Porta', value: '5432' },
    { label: 'Database', value: 'laravel' },
    { label: 'Usuário', value: 'sail' },
    { label: 'Senha', value: 'password' },
];
</script>

<template>
    <Card class="border border-surface-200 shadow-sm">
        <template #title>
            <div class="flex items-center gap-3">
                <i class="pi pi-database text-primary" />
                Banco de dados
            </div>
        </template>

        <template #content>
            <div class="space-y-5">
                <div>
                    <span class="text-sm font-medium text-surface-500">Credenciais de acesso</span>

                    <dl class="mt-3 divide-y divide-surface-200 rounded-md border border-surface-200">
                        <div
                            v-for="credential in databaseCredentials"
                            :key="credential.label"
                            class="grid grid-cols-[6rem_1fr] gap-3 px-3 py-2 text-sm"
                        >
                            <dt class="font-medium text-surface-500">
                                {{ credential.label }}
                            </dt>
                            <dd class="break-all font-mono text-surface-800">
                                {{ credential.value }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </template>
    </Card>
</template>
