<script setup>
import { ref } from 'vue'
import AjaxForm from '../../components/forms/AjaxForm.vue';
import TextField from '../../components/forms/TextField.vue';
import HiddenField from '../../components/forms/HiddenField.vue';

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
        <TextField :value="props.deck?.name ?? ''" name="name" placeholder="">Deck name:</TextField>

        <div v-for="( card, index ) in cards" :key="card.listItemId" class="card">
            <div class="flex">
                <HiddenField v-if="card.id" :value="card.id" :name="`cards.${index}.id`"></HiddenField>
                <TextField :value="card.question ?? ''" :name="`cards.${index}.question`">Question: </TextField>
                <TextField :value="card.answer ?? ''" :name="`cards.${index}.answer`">Answer: </TextField>
                <div @click="() => removeCard( index )" class="btn">Remove</div>
            </div>
        </div>

        <div @click="addCard" class="btn btn-primary">New</div>
    </AjaxForm>
</template>

