<script setup>
import { computed, ref } from 'vue';
import FlashcardContent from './FlashcardContent.vue';
// import OutlineButton from '../../../components/ui/OutlineButton.vue';
import { Button } from '@/components/ui/button';

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
            <Button variant='secondary' class="w-full" @click="revealItem">Flip card</Button>
        </div>
    </div>

    <div v-else>
        <FlashcardDisplay>
            <FlashcardContent :card="currentItem.card"></FlashcardContent>
        </FlashcardDisplay>
        <div class="flex mt-3">
            <Button variant='pill' size="lg" class="flex-1 mr-1 bg-destructive hover:bg-destructive/80" @click="onIncorrect">Need more practice</Button>
            <Button variant="pill" size="lg" class="flex-1 ml-1" @click="onCorrect">I know this</Button>
        </div>
    </div>
</template>