<script setup>
import { onMounted, ref, onUpdated } from 'vue';
import { parseFlashcardListContent } from '../../utils/parse-flashcard-content';

const props = defineProps({
    text: {
        required: true,
        type: String,
    }
})

const list = ref( parseFlashcardListContent( props.text ) )

</script>

<template>

<ol class="p-0 flashcard-list">
    <li v-for="item of list">{{ item }}</li>
</ol>

</template>

<style scoped>
.flashcard-list {
    list-style-position: inside;
}
</style>