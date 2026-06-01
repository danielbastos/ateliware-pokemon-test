<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BattleConfigurationForm from '../../../packages/pokebattle/resources/js/Components/BattleConfigurationForm.vue';

defineProps({
    battleConfiguration: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <AppLayout title="Configurações">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Configurações
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <BattleConfigurationForm :battle-configuration="battleConfiguration" />
            </div>
        </div>
    </AppLayout>
</template>
