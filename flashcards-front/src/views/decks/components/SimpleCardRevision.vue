<script setup>
import YesNoRevision from './single-card-revision/YesNoRevision.vue';
import useCardRevision from '../composables/revision';

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
        :card="currentItem.card" 
        @correct="onCorrect" 
        @incorrect="onIncorrect"
        :isRevealed="isRevealed"
        @reveal="revealItem"
        />
</template>