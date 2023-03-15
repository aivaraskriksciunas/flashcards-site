<script setup>
import EditForm from './components/_EditForm.vue'
import DataLoaderWrapper from '../../components/wrappers/DataLoaderWrapper.vue';
import { useRoute } from 'vue-router';
import { ref } from 'vue';

const route = useRoute()
const deckId = route.params.id 

const deck = ref( null )
const onLoad = ( d ) => {
    deck.value = d 
}

</script>

<template>

<DataLoaderWrapper :url="`/api/decks/${deckId}`" @load="onLoad">
    <h1>Edit deck '{{ deck.name }}'</h1>
    <EditForm :deck="deck"></EditForm>
</DataLoaderWrapper>
    
</template>