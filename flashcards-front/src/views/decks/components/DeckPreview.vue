<script setup>
import { ref, computed } from 'vue';
import FlashcardContent from './FlashcardContent.vue';
import FlashcardDisplay from './FlashcardDisplay.vue';
import FlashcardControls from './FlashcardControls.vue';

const props = defineProps([ 'cards' ])

const currentCardIndex = ref( 0 )
const card = computed( () => props.cards[currentCardIndex.value])

const nextCard = () => {
    currentCardIndex.value++;

    if ( currentCardIndex.value >= props.cards.length ) {
        currentCardIndex.value = 0
    }
}

const prevCard = () => {
    currentCardIndex.value--;

    if ( currentCardIndex.value < 0 ) {
        currentCardIndex.value = props.cards.length - 1
    }
}

</script>

<template>

<div v-if="props.cards">
    <FlashcardDisplay>
        <FlashcardContent :card="card"></FlashcardContent>
        
        <template v-slot:controls>
            <FlashcardControls @onPrev="prevCard" @onNext="nextCard">
                {{ currentCardIndex + 1 }}/{{ cards.length }}
            </FlashcardControls>
        </template>
    </FlashcardDisplay>
</div>
<div v-else>
    Deck is empty. 
</div>
</template>


<style scoped>
#card-count {
    flex-grow: 1;
    text-align: center;
    color: rgb( var( --muted-foreground ) );
}
</style>