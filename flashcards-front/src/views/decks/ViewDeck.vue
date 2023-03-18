<script setup>
import { useRoute } from "vue-router";
import { ref } from 'vue'
import DataLoaderWrapper from '../../components/wrappers/DataLoaderWrapper.vue'
import Card from "../../components/ui/Card.vue";
import DeckPreview from "./components/_DeckPreview.vue";
import PlainButton from "../../components/ui/PlainButton.vue";
import SlimContainer from "../../components/ui/SlimContainer.vue";

const route = useRoute()
const deckId = route.params.id 

const deck = ref( null )
const onLoad = ( data ) => {
    deck.value = data
} 

</script>

<template>

<DataLoaderWrapper :url="`/api/decks/${deckId}`" @load="onLoad">

    <SlimContainer>
        <h1>{{ deck.name }}</h1>

        <DeckPreview :cards="deck.cards"></DeckPreview>

        <router-link :to="{ name: 'edit-deck', params: { id: deck.id } }">
            <PlainButton>Edit</PlainButton>
        </router-link>

        <router-link :to="{ name: 'revise-deck', params: { id: deck.id} }">
            <Card hover>Revise</Card>
        </router-link>

        TODO: learn mode; learn all deck mode; practice mode;
    </SlimContainer>
    
</DataLoaderWrapper>

</template>

