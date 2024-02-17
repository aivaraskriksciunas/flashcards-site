<script setup>
import { ref } from 'vue';
import FlashcardDisplay from '../FlashcardDisplay.vue';
import FlashcardContent from '../FlashcardContent.vue';
import { Button } from '@/components/ui/button';
import { parseFlashcardListContent } from '../../utils/parse-flashcard-content';

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
    isRevealed: {
        type: Boolean,
        required: true,
    }
})

const emit = defineEmits([ 'correct', 'incorrect', 'reveal' ]);

const isCorrect = ref( false )
const userResponse = ref( '' )

const validateResponse = () => {
    let correctResponses = [];
    
    // Get a list of accepted responses
    if ( props.card.answer_type == 'list' ) {
        correctResponses = parseFlashcardListContent( props.card.answer );
    }
    else if ( props.card.answer_type == 'text' ) {
        correctResponses = props.card.answer.trim().split( /[\,\;\/\\]/ );
    }
    correctResponses = correctResponses.map( s => s.trim().toLowerCase() ).filter( s => s != '' );
    let userResponses = userResponse.value.trim().split( /[\,\;\/\\]/ ).map( s => s.trim().toLowerCase() ).filter( s => s != '' );

    // Check if the user entered at least one correct option
    let correctCount = 0;
    for ( let r of correctResponses ) {
        if ( userResponses.indexOf( r ) != -1 ) {
            correctCount++;
        }
    }

    isCorrect.value = correctCount > 0;
    emit( 'reveal' )
}

const submitCard = ( isCorrect ) => {
    if ( isCorrect ) {
        emit( 'correct' )
    }
    else {
        emit( 'incorrect' )
    }

    userResponse.value = '';
}

</script>

<template>

    <div v-if="!props.isRevealed">    
        <FlashcardDisplay>
            <FlashcardContent :text="props.card.question" :type="props.card.question_type" class="mb-8"/>

            <div class="card-answer">
                <input v-model='userResponse' type="text" placeholder="Type in the response" class="card-input-field" @keyup.enter="validateResponse"/>
            </div>
        </FlashcardDisplay>

        <div class="flex mt-3">
            <Button variant='secondary' class="w-full" @click="validateResponse">Check</Button>
        </div>
    </div>

    <div v-else>
        <FlashcardDisplay>
            <FlashcardContent :text="props.card.question" :type="props.card.question_type" class="mb-8"/>
            <div class="card-result">
                <div v-if="!isCorrect">
                    <div id="youAnsweredText">You answered:</div>
                    <div id="youAnsweredValue">{{ userResponse }}</div>
                </div>
                <div id="correctAnswerText">Correct answer:</div>
                <div id="correctAnswerValue" v-if="props.card.answer_type == 'text'">
                    {{ props.card.answer }}
                </div>
                <div id="correctAnswerValue" v-else-if="props.card.answer_type == 'list'">
                    {{ parseFlashcardListContent( props.card.answer ).join( ', ' ) }}
                </div>
            </div>

            <div class="card-comment">
                {{ props.card.comment }}
            </div>

            <div class="flex mt-3 items-center">
                <Button variant="secondary" class="flex-1" @click="submitCard( isCorrect )">Continue</Button>
                <Button variant="ghost" id="iWasRightButton" v-if="!isCorrect" @click="submitCard( true )">I was right</Button>
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
    color: rgb( var( --destructive ) );
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