<script setup>

const props = defineProps({
    course: {
        required: true,
        type: Object,
    }
})

</script>

<template>

<p class="mb-2">Welcome to the course. To begin, click "Begin course" at the bottom.</p>
<p>Once you begin, your progress will be stored and you will be able to continue later.</p>

</template>