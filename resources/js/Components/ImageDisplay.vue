<template>
    <img
        :src="src"
        alt="Imagine"
        class="max-w-full h-auto rounded-md shadow-md"
        :style="{ width: maxWidth, height: maxHeight }"
    />
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    src: {
        type: String,
        required: true,
    },
    maxWidth: {
        type: String,
        default: "100%",
    },
    maxHeight: {
        type: String,
        default: "auto",
    },
});
</script>

<style scoped>
img {
    object-fit: cover;
}
</style>
