<script setup>
import { ref, computed } from 'vue';
import Card from '../../../components/ui/Card.vue';
import PlainButton from '../../../components/ui/PlainButton.vue';

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
        <div class="deck-content">
            <div class="deck-question">{{ card.question }}</div>
            <div class="divider"></div>
            <div class="deck-answer">{{ card.answer }}</div>

            
        </div>

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
.deck-content {
    text-align: center;
    padding-top: 1em;
}

.deck-question {
    font-size: 3em;
    font-weight: 500;
}

.deck-answer {
    font-size: 2em;
}

.divider {
    display: inline-block;
    background-color: var(--color-shadow);
    height: 1px;
    width: 60%;
}

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