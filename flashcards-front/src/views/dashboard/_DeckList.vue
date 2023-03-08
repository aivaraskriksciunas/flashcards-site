<script setup>
import { ref } from 'vue';
import DataLoaderWrapper from '../../components/wrappers/DataLoaderWrapper.vue';
import DeckListItem from './_DeckListItem.vue';

const decks = ref([])

const onDataLoad = ( data ) => { decks.value = data }

</script>

<template>
    <DataLoaderWrapper url="/api/library" @load="onDataLoad">
        
        <div class="flex deck-list">
            <DeckListItem 
                v-for="deck of decks" :key="deck.id" 
                :deck="deck" 
                class="mb-4 w-1/3 deck-list-item">
            </DeckListItem>
        </div>
    </DataLoaderWrapper>
</template>

<style scoped>
.deck-list {
    margin: 0 -12px;
    flex-wrap: wrap;
    align-items: flex-start;
}

.deck-list-item {
    margin: 0 12px;
    max-width: 250px;
}
</style>

