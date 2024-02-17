<script setup>
import DataLoaderWrapper from '../../components/wrappers/DataLoaderWrapper.vue';
import SlimContainer from "../../components/ui/SlimContainer.vue";
import { useRoute } from 'vue-router';
import { ref } from 'vue';
import FlashcardDisplay from './components/FlashcardDisplay.vue';
import FlashcardControls from './components/FlashcardControls.vue';
import FlashcardContent from './components/FlashcardContent.vue';
import OutlineButton from '../../components/ui/OutlineButton.vue';
import { Button } from '@/components/ui/button';
import { useUserSettingStore } from '../../stores/user-settings';
import { Separator } from '@/components/ui/separator';

const route = useRoute()
const deckId = route.params.id 
const settings = useUserSettingStore()

const deck = ref({})
const i_currentCard = ref( 0 )
const cardRevealed = ref( false )

const onLoad = ( data ) => {
    deck.value = data
    i_currentCard.value = 0
    cardRevealed.value = false
}

const refreshKey = ref( 0 )

</script>

<template>
<DataLoaderWrapper 
    :url="`/api/decks/${deckId}`" 
    :query-params="{ 
        'choose': true,
        'quiz-mode': settings.getPreferredQuizMode( deckId ),
        'quiz-size': settings.getPreferredQuizSize( deckId )
    }"
    @load="onLoad" 
    :key="refreshKey">
    <SlimContainer>
        <h1>{{ deck.name }}</h1>

        <div v-if="deck.cards.length">
            <FlashcardDisplay>
                <div v-if="i_currentCard != deck.cards.length">
                    <FlashcardContent 
                        :text="deck.cards[i_currentCard].question" 
                        :type="deck.cards[i_currentCard].question_type"/>

                    <div v-if="cardRevealed !== i_currentCard">
                        <Button variant="ghost" @click="cardRevealed = i_currentCard">View answer</Button>
                    </div>

                    <Separator class="my-2" v-if="cardRevealed === i_currentCard"/>

                    <FlashcardContent 
                        v-if="cardRevealed === i_currentCard"
                        :text="deck.cards[i_currentCard].answer" 
                        :type="deck.cards[i_currentCard].answer_type"/>
                    
                </div>
                <div v-else>
                    <div class='learn-report mb-3'>You reviewed {{ deck.cards.length }} cards.</div>

                    <OutlineButton type="primary" class="mb-3" @click="refreshKey++">Keep learning</OutlineButton>
                    <router-link :to="{ name: 'revise-deck', params: { id: deck.id } }">
                        <OutlineButton class="mb-3">Practice</OutlineButton>
                    </router-link>
                    <router-link :to="{ name: 'view-deck', params: { id: deck.id } }">
                        <PlainButton class="mb-3">Back</PlainButton>
                    </router-link>
                </div>
                <template v-slot:controls>
                    <FlashcardControls
                        @onPrev="i_currentCard = Math.max( 0, i_currentCard - 1 )"
                        @onNext="i_currentCard = Math.min( deck.cards.length + 1, i_currentCard + 1 )">
                        {{ i_currentCard + 1 }}/{{ deck.cards.length + 1 }}
                    </FlashcardControls>
                </template>
            </FlashcardDisplay>
        </div>
        <div v-else>
            This deck is empty. <router-link :to="{ name: 'edit-deck', params: { id: deck.id } }">Click here to add cards.</router-link>
        </div>
        
    </SlimContainer>
</DataLoaderWrapper>
</template>

<style scoped>
.learn-report {
    font-size: 2em;
}

</style>