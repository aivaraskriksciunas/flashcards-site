<script setup>
import { computed, ref } from 'vue';
import Card from '../../../components/ui/Card.vue';
import FlashcardContent from './_FlashcardContent.vue';
import OutlineButton from '../../../components/ui/OutlineButton.vue';
import { reportQuizItemProgress } from '../../../services/DeckProgressService'

const props = defineProps({
    items: {
        default: [],
        required: true,
    }
})
const emit = defineEmits([ 'finish' ])

const currentItemIndex = ref( 0 )
const isRevealed = ref( false )
const currentItem = computed( () => props.items[currentItemIndex.value] )

const nextItem = () => {
    if ( currentItemIndex.value >= props.items.length - 1 ) {
        emit( 'finish' );
        return;
    }

    currentItemIndex.value += 1;
    isRevealed.value = false;
}

const revealItem = () => {
    isRevealed.value = true;
}

const onCorrect = () => {
    reportQuizItemProgress( props.items[currentItemIndex.value].id, true )
    nextItem()
}

const onIncorrect = () => {
    reportQuizItemProgress( props.items[currentItemIndex.value].id, false )
    nextItem()
}

</script>

<template>
    <div v-if="!isRevealed">    
        <Card @click="revealItem">
            <FlashcardContent :card="currentItem.card" questionOnly></FlashcardContent>
        </Card>

        <div class="flex mt-3">
            <OutlineButton class="w-full" @click="revealItem">Flip card</OutlineButton>
        </div>
    </div>

    <div v-else>
        <Card>
            <FlashcardContent :card="currentItem.card"></FlashcardContent>
        </Card>
        <div class="flex mt-3">
            <OutlineButton class="flex-1 mr-1" type="danger" @click="onIncorrect">Need more practice</OutlineButton>
            <OutlineButton class="flex-1 ml-1" type="primary" @click="onCorrect">I know this</OutlineButton>
        </div>
    </div>
</template>