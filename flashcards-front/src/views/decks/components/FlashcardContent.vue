<script setup>
import { onMounted, onUpdated, ref } from 'vue';
import FlashcardListContent from './flashcard-content/FlashcardListContent.vue';
import FlashcardMathContent from './flashcard-content/FlashcardMathContent.vue';
import FlashcardTextContent from './flashcard-content/FlashcardTextContent.vue';

const props = defineProps({
    text: {
        required: true,
        type: String,
    },
    type: {
        type: String,
        default: 'text'
    }
})

const contentSizer = ref( null )
const adaptContent = () => {
    const el = contentSizer.value;
    const max_text_size = 36; // px
    const min_text_size = 14; // px

    const isOverflowing = ( clientHeight, scrollHeight ) => scrollHeight > clientHeight;

    let fontSize = max_text_size;
    el.style.fontSize = `${fontSize}px`;
    while ( isOverflowing( el.clientHeight, el.scrollHeight ) && fontSize > min_text_size ) {
        fontSize -= 2;
        el.style.fontSize = `${fontSize}px`;
    }
}

onMounted(() => adaptContent() )
onUpdated(() => adaptContent() )
</script>

<template>
    <div class="content-sizer" ref="contentSizer">
        <FlashcardTextContent 
            v-if="props.type == 'text'"
            :text="props.text"/>
        
        <FlashcardListContent
            v-else-if="props.type == 'list'"
            :text="props.text"
            :key="props.text"/>

        <FlashcardMathContent 
            v-else-if="props.type == 'math'"
            :text="props.text"/>
    </div>
</template>

<style scoped>
.content-sizer {
    max-height: 8rem;
    line-height: 1.5;
}
</style>