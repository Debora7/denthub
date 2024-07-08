<script setup>
defineProps({
    value: {
        type: String,
    },
    required: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <label class="block font-medium text-sm text-gray-700">
        <span v-if="value">
            {{ value }}
            <span v-if="required" style="color: red">*</span>
        </span>
        <span v-else>
            <slot />
            <span v-if="required" style="color: red">*</span>
        </span>
    </label>
</template>
