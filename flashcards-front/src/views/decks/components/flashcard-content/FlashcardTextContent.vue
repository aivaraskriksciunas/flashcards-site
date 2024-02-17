<script setup>

const props = defineProps({
    text: {
        required: true,
        type: String,
    }
})
</script>

<template>
    <div>
        {{ props.text }}
    </div>
</template>

<script>
</script>