<script setup>
import Card from 'primevue/card';
</script>

<template>
    <Card class="border border-surface-200 shadow-sm">
        <template #title>
            <div class="flex items-center gap-3 text-xl">
                <i class="pi pi-bolt text-primary" />
                Batalha por HP
            </div>
        </template>

        <template #content>
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-md bg-primary-50 p-4">
                    <span class="block text-sm text-surface-600">Entrada</span>
                    <strong class="mt-1 block text-lg text-surface-950">Entre 2 e 6 Pokémons</strong>
                </div>

                <div class="rounded-md bg-primary-50 p-4">
                    <span class="block text-sm text-surface-600">Fonte dos dados</span>
                    <strong class="mt-1 block text-lg text-surface-950">PokéAPI</strong>
                </div>

                <div class="rounded-md bg-primary-50 p-4">
                    <span class="block text-sm text-surface-600">Critério da batalha</span>
                    <strong class="mt-1 block text-lg text-surface-950">HP</strong>
                </div>
            </div>
        </template>
    </Card>
</template>
