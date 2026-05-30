<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <main>
        <h1>{{ message }}</h1>
    </main>
</template>