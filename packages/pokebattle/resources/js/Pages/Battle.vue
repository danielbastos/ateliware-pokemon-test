<script setup>
import { computed, ref } from 'vue';
import PokemonCard from '../Components/PokemonCard.vue';

const props = defineProps({
    concorrents: {
        type: Number,
        required: true,
    },
});

const pokeCards = ref([]);

for (let i = 0; i < props.concorrents; i++) {
    pokeCards.value.push({id: i, data: {}})
}

const results = ref([]);

const orderedResults = computed(() => {
    return [...results.value]
        .filter((result) => Number.isFinite(result.hp))
        .sort((firstResult, secondResult) => secondResult.hp - firstResult.hp);
});

const updateResult = (index, pokemon) => {
    results.value = results.value.filter((result) => result.index !== index);

    if (!pokemon) return;
    results.value.push({
        ...pokemon,
        hp: Number(pokemon.hp),
        index,
    });
};

const getComparisonSignal = (index) => {
    return orderedResults.value[index].hp === orderedResults.value[index + 1].hp ? '=' : '>';
};

</script>

<template>
    <main class="min-h-screen bg-surface-50 text-surface-950">
        <section class="mx-auto grid w-full max-w-6xl gap-6 px-5 py-10 sm:px-8 lg:grid-cols-2 lg:py-14">
            <PokemonCard
                v-for="pokeCard in pokeCards"
                :key="pokeCard.id"
                @update:pokemon="updateResult(pokeCard.id, $event)"
            />
        </section>

        <section
            v-if="orderedResults.length"
            class="mx-auto w-full max-w-6xl px-5 pb-10 sm:px-8 lg:pb-14"
        >
            <div class="border border-surface-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-surface-950">
                    Resultado
                </h2>

                <div class="flex flex-wrap items-center justify-center gap-3">
                    <template
                        v-for="(result, index) in orderedResults"
                        :key="result.index"
                    >
                        <div class="flex min-h-16 items-center gap-3 border border-surface-200 bg-surface-50 px-4 py-3">
                            <img
                                v-if="result.image_url"
                                class="h-12 w-12 object-contain"
                                :src="result.image_url"
                                :alt="result.name"
                            >

                            <div>
                                <span class="block text-sm font-medium capitalize text-surface-600">
                                    {{ result.name }}
                                </span>
                                <strong class="text-2xl font-bold text-surface-950">
                                    {{ result.hp }} HP
                                </strong>
                            </div>
                        </div>

                        <span
                            v-if="index < orderedResults.length - 1"
                            class="text-3xl font-bold text-surface-500"
                        >
                            {{ getComparisonSignal(index) }}
                        </span>
                    </template>
                </div>
            </div>
        </section>
    </main>
</template>
