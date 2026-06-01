<script setup>
import { ref } from 'vue';
import AutoComplete from 'primevue/autocomplete';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Skeleton from 'primevue/skeleton';

const emit = defineEmits(['update:pokemon']);

const queryName = ref('');
const suggestions = ref([]);
const model = ref({});
const isLoadingSugestions = ref(false);
const isLoadingPokemon = ref(false);
const error = ref('');

async function getResource(resource) {
    const params = {headers: {Accept: 'application/json'}};

    try {
        const response = await fetch(resource, params);
        if (!response.ok) throw new Error('Falha na requisição');
        return await response.json();
    } catch {
        return null;
    }
}

const getSugestions = async (event) => {
    isLoadingSugestions.value = true;
    error.value = '';

    const resource = `/battle/names?query=${encodeURIComponent(event.query)}`;
    const data = await getResource(resource);
    suggestions.value = data?.suggestions ?? [];
    isLoadingSugestions.value = false;
};

const clearPokemon = async () => {
    model.value = {};
    emit('update:pokemon', null);
};

const getPokemon = async () => {
    const name = queryName.value?.trim();
    await clearPokemon();

    if (!name) {
        error.value = 'Informe um Pokémon.';
        return;
    }

    isLoadingPokemon.value = true;
    error.value = '';

    const resource = `/battle/pokemon?name=${encodeURIComponent(name)}`;
    const data = await getResource(resource);
    if (!data) {
        error.value = 'Não foi possível buscar o Pokémon.';
        isLoadingPokemon.value = false;
        return;
    }

    model.value = data;
    emit('update:pokemon', data);
    isLoadingPokemon.value = false;
};

</script>

<template>
    <Card class="border border-surface-200 shadow-sm">
        <template #title>
            <div class="flex flex-col gap-3 sm:flex-row">
                <AutoComplete
                    v-model="queryName"
                    class="w-full"
                    input-class="w-full"
                    :loading="isLoadingSugestions"
                    :suggestions="suggestions"
                    placeholder="Nome do Pokémon"
                    @complete="getSugestions"
                    @keyup.enter="getPokemon"
                    @keyup="clearPokemon"
                    @option-select="getPokemon"
                    :show-empty-message="false"
                />

                <Button
                    class="shrink-0"
                    icon="pi pi-search"
                    label="Buscar"
                    :loading="isLoadingPokemon"
                    @click="getPokemon"
                />
            </div>
        </template>

        <template #content>
            <div class="space-y-3">
                <span class="block text-sm font-medium text-surface-500">Imagem</span>
                <img
                    v-if="model.image_url"
                    class="mx-auto h-40 w-40 object-contain"
                    :src="model.image_url"
                    :alt="queryName"
                >

                <Skeleton
                    v-if="!model.image_url"
                    class="mx-auto"
                    :animation="isLoadingPokemon ? 'wave' : 'none'"
                    width="10rem"
                    height="10rem"
                />

                <div>
                    <span class="block text-sm font-medium text-surface-500">HP</span>
                    <strong class="mt-1 block text-4xl font-bold text-surface-950">
                        <span v-if="model.hp"> {{ model.hp }}</span>
                        <Skeleton 
                            v-if="!model.hp"
                            :animation="isLoadingPokemon ? 'wave' : 'none'"
                            size="3rem"
                            class="mr-2" />
                    </strong>
                </div>

                <p
                    v-if="error"
                    class="text-sm text-red-600"
                >
                    {{ error }}
                </p>
            </div>
        </template>
    </Card>
</template>
