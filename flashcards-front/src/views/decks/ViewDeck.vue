<script setup>
import { useRoute } from "vue-router";
import { ref } from 'vue'
import DataLoaderWrapper from '../../components/wrappers/DataLoaderWrapper.vue'
import Card from "../../components/ui/Card.vue";
import DeckPreview from "./components/_DeckPreview.vue";
import PlainButton from "../../components/ui/PlainButton.vue";

const route = useRoute()
const deckId = route.params.id 

const deck = ref( null )
const onLoad = ( data ) => {
    deck.value = data
} 

</script>

<template>

<DataLoaderWrapper :url="`/api/decks/${deckId}`" @load="onLoad">

    <div class="lg:w-1/2 md:w-2/3 w-full mx-auto">
        <h1>{{ deck.name }}</h1>

        <DeckPreview :cards="deck.cards"></DeckPreview>

        <router-link :to="{ name: 'edit-deck', params: { id: deck.id } }">
            <PlainButton>Edit</PlainButton>
        </router-link>

        TODO: learn mode; learn all deck mode; practice mode;
    </div>
    
</DataLoaderWrapper>

</template>

