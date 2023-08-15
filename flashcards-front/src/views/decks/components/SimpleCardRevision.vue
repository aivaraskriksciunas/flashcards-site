<script setup>
import { computed, ref } from 'vue';
import FlashcardContent from './FlashcardContent.vue';
import OutlineButton from '../../../components/ui/OutlineButton.vue';

import FlashcardDisplay from './FlashcardDisplay.vue';
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
    <div v-if="!isRevealed">    
        <FlashcardDisplay @click="revealItem">
            <FlashcardContent :card="currentItem.card" questionOnly></FlashcardContent>
        </FlashcardDisplay>

        <div class="flex mt-3">
            <OutlineButton class="w-full" @click="revealItem">Flip card</OutlineButton>
        </div>
    </div>

    <div v-else>
        <FlashcardDisplay>
            <FlashcardContent :card="currentItem.card"></FlashcardContent>
        </FlashcardDisplay>
        <div class="flex mt-3">
            <OutlineButton class="flex-1 mr-1" type="danger" @click="onIncorrect">Need more practice</OutlineButton>
            <OutlineButton class="flex-1 ml-1" type="primary" @click="onCorrect">I know this</OutlineButton>
        </div>
    </div>
</template>