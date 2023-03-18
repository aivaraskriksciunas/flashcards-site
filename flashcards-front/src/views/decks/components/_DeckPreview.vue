<script setup>
import { ref, computed } from 'vue';
import Card from '../../../components/ui/Card.vue';
import PlainButton from '../../../components/ui/PlainButton.vue';
import FlashcardContent from './_FlashcardContent.vue';

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
    <Card>
        <FlashcardContent :card="card"></FlashcardContent>

        <div class="controls">
            <PlainButton @click="prevCard">
                Prev
            </PlainButton>
            <div id="card-count">
                {{ currentCardIndex + 1 }}/{{ cards.length }}
            </div>
            <PlainButton @click="nextCard">
                Next
            </PlainButton>
        </div>
    </Card>
</div>
<div v-else>
    Deck is empty. 
</div>
</template>


<style scoped>

.controls {
    margin-top: 4em;
    display: flex;
    align-items: center;
}

#card-count {
    flex-grow: 1;
    text-align: center;
    color: var( --color-text-lighter );
}
</style>