<script setup>
import { ref, Transition } from 'vue'
import AjaxForm from '../../../components/forms/AjaxForm.vue';
import TextField from '../../../components/forms/TextField.vue';
import HiddenField from '../../../components/forms/HiddenField.vue';
import PlainButton from '../../../components/ui/PlainButton.vue';
import Card from '../../../components/ui/Card.vue';

const props = defineProps({
    deck: {
        default: null
    }
})

const cards = ref([])

if ( props.deck && props.deck.cards ) {
    for ( let card of props.deck.cards ) {
        cards.value.push({
            listItemId: Symbol(), 
            id: card.id,
            // If card value is changed, showId will be set to false
            // This will remove the card from the server and create a new one in its place
            showId: true,
            question: card.question,
            answer: card.answer
        })
    }
}

let url = '/api/decks', method = 'POST';
if ( props.deck ) {
    url = `/api/decks/${props.deck.id}`
    method = 'PATCH'
}

const addCard = () => {
    cards.value.push({
        listItemId: Symbol(),
        id: null,
        question: '',
        answer: ''
    })
}

const removeCard = ( index ) => {
    cards.value.splice( index, 1 )
}

</script>

<template>
    <AjaxForm :action="url" :method="method">
        <TextField class="mb-8" :value="props.deck?.name ?? ''" name="name" placeholder="">Deck name:</TextField>

        <TransitionGroup name="card-list">
            <div v-for="( card, index ) in cards" :key="card.listItemId" class="card">
                <Card class="md:flex deck-item-form">
                    <HiddenField v-if="card.id" :value="card.id" :name="`cards.${index}.id`"></HiddenField>
                    <TextField 
                        class="deck-item-control" 
                        :value="card.question ?? ''" 
                        :name="`cards.${index}.question`">
                        Question: 
                    </TextField>
                    <TextField 
                        class="deck-item-control"
                        :value="card.answer ?? ''" 
                        :name="`cards.${index}.answer`">
                        Answer: 
                    </TextField>

                    <PlainButton @click="() => removeCard( index )" type="danger">
                        Remove
                    </PlainButton>
                </Card>
            </div>
        </TransitionGroup>

        <div @click="addCard" class="btn btn-primary">New</div>
    </AjaxForm>
</template>

<style scoped>
.deck-item-form {
    margin-bottom: 12px;
    align-items: center;
}

.deck-item-control  {
    flex-grow: 1;
    margin-right: 12px;
}

.card-list-enter-from,
.card-list-leave-to {
    transform: scale( 0 );
}

.card-list-enter-active,
.card-list-leave-active {
    transition: transform 0.2s ease;
}

</style>
