<script setup>
import { onMounted, ref } from "vue";

const model = ref(0); // Initialize model with a default number value

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

const handleInput = (event) => {
    const value = event.target.value;
    if (/^\d*\.?\d*$/.test(value)) {
        model.value = parseFloat(value);
    } else {
        model.value = parseFloat(value.replace(/[^0-9.]/g, ""));
    }
};
</script>

<template>
    <input
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        v-model="model"
        @input="handleInput"
        ref="input"
        type="text"
    />
</template>
