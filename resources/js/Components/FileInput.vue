<script setup>
import { ref } from "vue";

const model = defineModel({
    type: [File, null],
    required: true,
});

const fileInput = ref(null);

defineExpose({ focus: () => fileInput.value.focus() });

const handleInput = (event) => {
    model.value = event.target.files[0] || null;
};
</script>

<template>
    <div class="relative">
        <input
            type="file"
            ref="fileInput"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full opacity-0 absolute inset-0 z-10"
            @change="handleInput"
        />
        <div
            class="border border-gray-300 rounded-md shadow-sm mt-1 block w-full p-2 bg-white cursor-pointer"
        >
            <span v-if="!model" class="text-gray-500"
                >Selectează o imagine</span
            >
            <span v-else>{{ model.name }}</span>
        </div>
    </div>
</template>
