<script setup>
import TeXRenderer from '@/components/ui/TeXRenderer.vue';

const props = defineProps({
    text: {
        required: true,
        type: String,
    }
})

</script>

<template>
    <TeXRenderer :text="props.text"/>
</template>