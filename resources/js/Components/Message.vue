<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    message: {
        type: String,
        required: true,
    },
    backgroundColor: {
        type: String,
        default: "bg-blue", // default background color
    },
});

const message = ref(props.message);

watch(
    () => props.message,
    (newMessage) => {
        message.value = newMessage;
    }
);
</script>

<template>
    <div :class="[backgroundColor, 'p-4', 'rounded', 'shadow-md']">
        {{ message }}
    </div>
</template>

<style scoped>
.bg-blue {
    background-color: rgba(0, 0, 250, 0.5); /* red with 50% opacity */
    color: black;
}
.bg-red {
    background-color: rgba(255, 0, 0, 0.5); /* red with 50% opacity */
    color: black;
}
.bg-green {
    background-color: rgba(0, 255, 0, 0.5); /* red with 50% opacity */
    color: black;
}
</style>
