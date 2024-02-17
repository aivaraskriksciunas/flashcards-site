<script setup>
import { ref } from 'vue';
import useCardRevision from '../composables/revision';
import WriteRevision from './single-card-revision/WriteRevision.vue';
import YesNoRevision from './single-card-revision/YesNoRevision.vue';

const props = defineProps({
    items: {
        default: [],
        required: true,
    }
})
const emit = defineEmits([ 'finish' ])

const onFinish = () => {
    emit( 'finish' )
}

const {
    isRevealed,
    currentItem,
    revealItem,
    onCorrect,
    onIncorrect,
} = useCardRevision( props.items, onFinish )

</script>

<template>
    <YesNoRevision 
        v-if="currentItem.card.answer_type == 'math'" 
        :card="currentItem.card" 
        @correct="onCorrect" 
        @incorrect="onIncorrect"
        :isRevealed="isRevealed"
        @reveal="revealItem"/>

    <WriteRevision 
        v-else 
        :card="currentItem.card" 
        @correct="onCorrect" 
        @incorrect="onIncorrect"
        :isRevealed="isRevealed"
        @reveal="revealItem"/>
</template>

