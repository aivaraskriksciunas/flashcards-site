<script setup>
import { ref } from 'vue';
import useCardRevision from '../composables/revision';
import FlashcardDisplay from './FlashcardDisplay.vue';
import FlashcardContent from './FlashcardContent.vue';
import OutlineButton from '../../../components/ui/OutlineButton.vue';
import PlainButton from '../../../components/ui/PlainButton.vue';

const props = defineProps({
    items: {
        default: [],
        required: true,
    }
})
const emit = defineEmits([ 'finish' ])
const userResponse = ref( '' )
const isCorrect = ref( false )

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

const validateResponse = () => {
    let correctResponses = currentItem.value.card.answer.trim().split( /[\,\;\/\\]/ ).map( s => s.trim().toLowerCase() ).filter( s => s != '' );
    let userResponses = userResponse.value.trim().split( /[\,\;\/\\]/ ).map( s => s.trim().toLowerCase() ).filter( s => s != '' );

    let correctCount = 0;
    for ( let r of correctResponses ) {
        if ( userResponses.indexOf( r ) != -1 ) {
            correctCount++;
        }
    }

    isCorrect.value = correctCount > 0;
    revealItem();
}

const submitCard = ( isCorrect ) => {
    if ( isCorrect ) {
        onCorrect();
    }
    else {
        onIncorrect();
    }

    userResponse.value = '';
}

</script>

<template>
    <div v-if="!isRevealed">    
        <FlashcardDisplay>
            <div class="card-question">{{ currentItem.card.question }}</div>
            <div class="card-answer">
                <input v-model='userResponse' type="text" placeholder="Type in the response" class="card-input-field" @keyup.enter="validateResponse"/>
            </div>
        </FlashcardDisplay>

        <div class="flex mt-3">
            <OutlineButton class="w-full" @click="validateResponse">Check</OutlineButton>
        </div>
    </div>

    <div v-else>
        <FlashcardDisplay>
            <div class="card-question">{{ currentItem.card.question }}</div>
            <div class="card-result">
                <div v-if="!isCorrect">
                    <div id="youAnsweredText">You answered:</div>
                    <div id="youAnsweredValue">{{ userResponse }}</div>
                </div>
                <div id="correctAnswerText">Correct answer:</div>
                <div id="correctAnswerValue">{{ currentItem.card.answer }}</div>
            </div>

            <div class="card-comment">
                {{ currentItem.card.comment }}
            </div>

            <div class="flex mt-3 items-center">
                <OutlineButton class="flex-1" @click="submitCard( isCorrect )">Continue</OutlineButton>
                <PlainButton id="iWasRightButton" v-if="!isCorrect" @click="submitCard( true )">I was right</PlainButton>
            </div>
        </FlashcardDisplay>

    </div>
</template>

<style scoped>
.card-question {
    font-weight: 500;
    font-size: 3em;
    margin-bottom: 0.8em;
}

.card-comment {
    font-size: 0.9;
    color: rgb( var( --muted-foreground ) );
    text-align: left;
}

.card-input-field {
    border: 0;
    background-color: rgb( var( --background ) );
    border-bottom: 3px solid rgb( var( --shadow ) );
    border-radius: 3px;
    font-size: 1.4em;
    padding: 8px 16px;
    width: 100%;
}

.card-input-field:focus {
    @apply bg-background/80;
    /* background-color: var( --color-hover ); */
    outline: none;
}

.card-result {
    text-align: left;
}

#youAnsweredText, #correctAnswerText {
    font-size: 1.1em;
    color: rgb( var( --muted-foreground ) );
}

#youAnsweredValue {
    font-size: 1.5em;
    font-weight: 500;
}

#correctAnswerValue {
    font-size: 2em;
    font-weight: 500;
}

#youAnsweredValue {
    color: var( --color-danger );
    margin-bottom: 16px;
}

#correctAnswerValue {
    color: rgb( var( --primary ) );
}

#iWasRightButton {
    flex-shrink: 1;
    margin-left: 6px;
}

</style>