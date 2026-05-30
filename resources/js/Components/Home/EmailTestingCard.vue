<script setup>
import Card from 'primevue/card';
import Divider from 'primevue/divider';
</script>

<template>
    <Card class="border border-surface-200 shadow-sm">
        <template #title>
            <div class="flex items-center gap-3">
                <i class="pi pi-envelope text-primary" />
                Testes de e-mail
            </div>
        </template>

        <template #content>
            <div class="space-y-5">
                <div>
                    <span class="text-sm font-medium text-surface-500">Endereço de acesso</span>
                    <a
                        class="mt-1 block break-all text-primary hover:underline"
                        href="http://mailpit.localhost"
                    >
                        http://mailpit.localhost
                    </a>
                </div>

                <Divider />

                <p class="leading-7 text-surface-600">
                    O Mailpit é uma caixa de entrada local para capturar e visualizar e-mails enviados pela aplicação durante o desenvolvimento, sem entregar mensagens reais para usuários.
                </p>
            </div>
        </template>
    </Card>
</template>
