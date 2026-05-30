<script setup>
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import ApplicationInfoCard from '@/Components/Home/ApplicationInfoCard.vue';
import BattleSummaryCard from '@/Components/Home/BattleSummaryCard.vue';
import DatabaseInfoCard from '@/Components/Home/DatabaseInfoCard.vue';
import EmailTestingCard from '@/Components/Home/EmailTestingCard.vue';

defineProps({
    message: String,
});
</script>

<template>
    <main class="min-h-screen bg-surface-50 text-surface-950">
        <section class="mx-auto flex w-full max-w-6xl flex-col gap-10 px-5 py-10 sm:px-8 lg:py-14">
            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                <div class="space-y-6">
                    <Tag
                        severity="contrast"
                        value="Processo seletivo Ateliware"
                    />

                    <div class="space-y-4">
                        <h1 class="max-w-3xl text-4xl font-bold tracking-normal text-surface-950 sm:text-5xl">
                            Pokémon Battle — Ateliware
                        </h1>

                        <p class="max-w-3xl text-lg leading-8 text-surface-700">
                            Este projeto é uma aplicação de batalha entre Pokémons desenvolvida como parte do processo seletivo da ateliware para a vaga de Pessoa Desenvolvedora.
                        </p>

                        <p class="max-w-3xl text-base leading-7 text-surface-600">
                            A aplicação permite que o usuário informe os nomes de dois Pokémons, consulta os dados de cada um em tempo real via PokéAPI, e determina o vencedor com base nos Hit Points (HP) de cada um — ou declara empate quando os valores são iguais.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <Button
                            as="a"
                            href="http://ateliware.localhost/battle"
                            icon="pi pi-play"
                            label="Acessar a batalha"
                        />

                        <Button
                            as="a"
                            href="http://mailpit.localhost"
                            icon="pi pi-envelope"
                            label="Abrir Mailpit"
                            severity="secondary"
                            outlined
                        />
                    </div>
                </div>

                <BattleSummaryCard />
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <ApplicationInfoCard />
                <EmailTestingCard />
                <DatabaseInfoCard />
            </div>
        </section>
    </main>
</template>
