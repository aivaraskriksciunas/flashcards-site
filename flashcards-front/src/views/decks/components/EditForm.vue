<script setup>
import { ref } from 'vue'
import AjaxForm from '../../../components/forms/AjaxForm.vue';
import TextField from '../../../components/forms/TextField.vue';
import TextareaField from '../../../components/forms/TextareaField.vue';
import HiddenField from '../../../components/forms/HiddenField.vue';
import PlainButton from '../../../components/ui/PlainButton.vue';
import Card from '../../../components/ui/Card.vue';
import PlainIconButton from '../../../components/ui/PlainIconButton.vue';
import OutlineButton from '../../../components/ui/OutlineButton.vue';

const props = defineProps({
    deck: {
        default: null
    }
})

const cards = ref([])
const addCardButton = ref( null )
const emit = defineEmits([ 'save' ])

if ( props.deck && props.deck.cards ) {
    for ( let card of props.deck.cards ) {
        cards.value.push({
            listItemId: Symbol(), 
            id: card.id,
            // If card value is changed, showId will be set to false
            // This will remove the card from the server and create a new one in its place
            showId: true,
            question: card.question,
            answer: card.answer,
            comment: card.comment == '' ? null : card.comment,
        })
    }
}

let url = '/api/decks', method = 'POST';
if ( props.deck ) {
    url = `/api/decks/${props.deck.id}`
    method = 'PATCH'
}

const scrollToView = () => {
    addCardButton.value.scrollIntoView({ behavior: 'smooth', block: 'end' })
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

const addComment = ( index ) => {
    cards.value[index].comment = ''
}

const closeComment = ( index ) => {
    cards.value[index].comment = null
}

const onDeckSave = ( data ) => {
    emit( 'save', data );
}

</script>

<template>
    <AjaxForm :action="url" :method="method" :show-status-message="true" @success="onDeckSave">
        <TextField class="mb-8" :value="props.deck?.name ?? ''" name="name" placeholder="">Deck name:</TextField>

        <TransitionGroup name="card-list" @after-enter="scrollToView">
            <div v-for="( card, index ) in cards" :key="card.listItemId" class="card">
                <Card class="deck-item-form">
                    <div class="card-header flex content-center">
                        <div class="card-title">#{{ index + 1 }}</div>
                        <div class="flex-1"></div>
                        <PlainIconButton @click="() => removeCard( index )" type="danger" icon="fas fa-trash" size="sm">
                        </PlainIconButton>
                    </div>

                    <div class="md:flex mb-2">
                        <HiddenField v-if="card.id" :value="card.id" :name="`cards.${index}.id`"></HiddenField>
                        <TextField 
                            class="deck-item-control" 
                            :value="card.question ?? ''" 
                            :name="`cards.${index}.question`"
                            placeholder="Question">
                        </TextField>
                        <TextField 
                            class="deck-item-control"
                            :value="card.answer ?? ''" 
                            :name="`cards.${index}.answer`"
                            placeholder="Answer">
                        </TextField>
                    </div>
                    
                    <div class="flex justify-center text-sm" v-if="card.comment == null">
                        <PlainButton @click="() => addComment( index )">
                            <font-awesome-icon icon="fas fa-plus" class="mr-1"></font-awesome-icon>
                            Comment
                        </PlainButton> 
                    </div>
                    <div class='flex w-full' v-else>
                        <TextareaField 
                            class="w-full"
                            :name="`cards.${index}.comment`"
                            :value="card.comment">
                            <div class="flex items-center">
                                <div class="flex-1">Comment</div>
                                <PlainIconButton icon="fas fa-xmark" @click="() => closeComment( index )"></PlainIconButton>
                            </div>
                        </TextareaField>
                    </div>

                </Card>
            </div>
        </TransitionGroup>

        <div ref="addCardButton">
            <OutlineButton @click="addCard" class="w-full mb-4" type="primary">New card</OutlineButton>
        </div>
    </AjaxForm>
</template>

<style scoped>
.card-header {
    align-items: center;
}

.card-title {
    color: rgb( var( --muted-foreground ) );
    font-style: italic;
}

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
    transition: transform 0.1s ease;
}

</style>
