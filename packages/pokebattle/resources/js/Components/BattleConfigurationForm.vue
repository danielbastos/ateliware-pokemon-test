<script setup>
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    battleConfiguration: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    concorrents: props.battleConfiguration.concorrents,
});

const updateBattleConfiguration = () => {
    form.put(route('battle-configuration.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <FormSection @submitted="updateBattleConfiguration">
        <template #title>
            Configuração da batalha
        </template>

        <template #description>
            Defina quantos pokémons podem entrar em cada batalha.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="concorrents" value="Pokémons por batalha" />
                <TextInput
                    id="concorrents"
                    v-model="form.concorrents"
                    type="number"
                    min="2"
                    max="6"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.concorrents" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Salvo.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Salvar
            </PrimaryButton>
        </template>
    </FormSection>
</template>
