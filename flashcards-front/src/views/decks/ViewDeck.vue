<script setup>
import { useRoute } from "vue-router";
import { ref } from 'vue'
import { useUserStore } from "../../stores/user";
import DataLoaderWrapper from '../../components/wrappers/DataLoaderWrapper.vue'
import Card from "../../components/ui/Card.vue";
import DeckPreview from "./components/DeckPreview.vue";
import PlainButton from "../../components/ui/PlainButton.vue";
import SlimContainer from "../../components/ui/SlimContainer.vue";
import DeckSummary from "./components/DeckSummary.vue";

const route = useRoute()
const deckId = route.params.id 

const deck = ref( null )
const onLoad = ( data ) => {
    deck.value = data
} 

const { isLoggedIn } = useUserStore()

</script>

<template>

<DataLoaderWrapper :url="`/api/decks/${deckId}`" @load="onLoad">

    <SlimContainer>
        <div class="deck-title-container">
            <div class='deck-title'>
                <h1>{{ deck.name }}</h1>
            </div>
            <router-link :to="{ name: 'edit-deck', params: { id: deck.id } }">
                <PlainButton>
                    <font-awesome-icon icon="far fa-edit"></font-awesome-icon>
                    Edit
                </PlainButton>
            </router-link>
        </div>

        <div v-if="deck.cards.length">
            <DeckPreview :cards="deck.cards"></DeckPreview>

            <div v-if="isLoggedIn">

                <div class="flex deck-actions mt-3">
                    <router-link :to="{ name: 'revise-deck', params: { id: deck.id } }">
                        <Card hover>Revise</Card>
                    </router-link>

                    <router-link :to="{ name: 'learn-deck', params: { id: deck.id } }">
                        <Card hover>Learn</Card>
                    </router-link>

                    <router-link :to="{ name: 'practice-deck', params: { id: deck.id } }">
                        <Card hover>Write</Card>
                    </router-link>
                </div>

                <DeckSummary :deck="deck"/>
            </div>
        </div>
        <div v-else>
            This deck is empty. <router-link :to="{ name: 'edit-deck', params: { id: deck.id } }">Click here to add cards.</router-link>
        </div>
        
    </SlimContainer>
    
</DataLoaderWrapper>

</template>

<style scoped>

.deck-title-container {
    display: flex;
    align-items: center;
}

.deck-title {
    flex-grow: 1;
}

.deck-actions {
    display: flex;
    gap: 12px;
}

.deck-actions a {
    flex: 1;
}
</style>