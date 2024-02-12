<script setup>

const props = defineProps({
    content: {
        type: String,
        required: true,
    }
})

</script>

<template>
    <div class="rich-text-content" v-html="props.content"></div>
</template>

<style>
.rich-text-content {
    line-height: 1.5;
}

.rich-text-content p {
    margin-bottom: 1.5em;
}
</style>