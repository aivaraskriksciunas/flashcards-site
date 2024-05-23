<script setup>
import { ref, onMounted } from 'vue'
import {
    Plus,
    Trash2
} from 'lucide-vue-next';
import AjaxForm from '../../../components/forms/AjaxForm.vue';
import TextField from '../../../components/forms/TextField.vue';
import TextareaField from '../../../components/forms/TextareaField.vue';
import PlainButton from '../../../components/ui/PlainButton.vue';
import Card from '../../../components/ui/Card.vue';
import PlainIconButton from '../../../components/ui/PlainIconButton.vue';
import { Button } from '@/components/ui/button';
import FlashcardContentFields from './flashcard-content-fields/FlashcardContentFields.vue';
import FlashcardContentTypeSelect from './flashcard-content-fields/FlashcardContentTypeSelect.vue';
import debounce from 'lodash/debounce'
import axios from 'axios';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog'

const props = defineProps({
    deck: {
        default: null
    }
})

const cards = ref([])
const addCardButton = ref( null )
const emit = defineEmits([ 'save' ])
const draft = ref( null )

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
            question_type: card.question_type ?? 'text',
            answer_type: card.answer_type ?? 'text'
        })
    }
}

let url = '/api/decks', method = 'POST';
if ( props.deck && props.deck.id ) {
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
        answer: '',
        question_type: 'text',
        answer_type: 'text',
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

const onFormChange = debounce( ( data ) => {
    if ( !props.deck || !props.deck.id ) return;

    // Store draft
    axios.post( `/api/decks/${props.deck.id}/draft`, data ).catch(() => {})
}, 1500 )

onMounted( async () => {
    if ( !props.deck || !props.deck.id ) return;

    try {
        let data = await axios.get( `/api/decks/${props.deck.id}/draft` )
        draft.value = data.data
    }
    catch ( e ) {}
})

const openDraft = () => {
    if ( !draft.value.cards ) return;
    
    cards.value = draft.value.cards 
    draft.value = null

    // Ensure all cards have a listItemId
    cards.value.forEach( ( val, index ) => {
        if ( val.listItemId == null ) {
            cards.value[index].listItemId = Symbol()
        }
    });
    console.log( cards.value )
}

</script>

<template>
    <AjaxForm :action="url" :method="method" :show-status-message="true" @success="onDeckSave" @change="onFormChange">
        <TextField class="mb-8" :value="props.deck?.name ?? ''" name="name" placeholder="">Deck name:</TextField>

        <TransitionGroup name="card-list" @after-enter="scrollToView">
            <div v-for="( card, index ) in cards" :key="card.listItemId" class="card">
                <Card class="deck-item-form">
                    <div class="card-header flex content-center">
                        <div class="card-title">#{{ +index + 1 }}</div>

                        <div class="flex-1">
                            <FlashcardContentTypeSelect v-model="cards[index].answer_type"/>
                        </div>

                        <PlainIconButton @click="() => removeCard( index )" variant="destructive">
                            <Trash2/>
                        </PlainIconButton>
                    </div>
                    
                    <FlashcardContentFields :card="card" :index="index" :answerType="card.answer_type"/>
                    
                    <div class="flex justify-center text-sm" v-if="card.comment == null">
                        <PlainButton @click="() => addComment( index )">
                            <Plus class="mr-1" size="16"/>
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
            <Button variant="pillOutline" @click="addCard" class="w-full mb-4">New card</Button>
        </div>
    </AjaxForm>

    <Dialog :open="draft != null && draft.cards != null">
        <DialogContent class="sm:max-w-[425px]">
        <DialogHeader>
            <DialogTitle>Unsaved draft found</DialogTitle>
            <DialogDescription>
                There is an unsaved draft for this deck. Do you want to load it?
            </DialogDescription>
        </DialogHeader>
        <DialogFooter>
            <Button variant="outline" @click="draft = null">
                No 
            </Button>
            <Button @click="openDraft">
                Yes, load draft
            </Button>
        </DialogFooter>
        </DialogContent>
    </Dialog>
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
