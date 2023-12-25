<script setup>
import { ref } from 'vue';
import DataLoaderWrapper from '../../../components/wrappers/DataLoaderWrapper.vue';
import DeckListItem from './DeckListItem.vue';

const decks = ref([])

const onDataLoad = ( data ) => { decks.value = data }

</script>

<template>
    <DataLoaderWrapper url="/api/library" @load="onDataLoad">
        
        <div class="md:flex deck-list">
            <DeckListItem 
                v-for="deck of decks" :key="deck.id" 
                :deck="deck" 
                class="deck-list-item">
            </DeckListItem>
        </div>
    </DataLoaderWrapper>
</template>

<style scoped>
.deck-list {
    margin: 0 -12px;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 12px;
}

.deck-list-item {
    margin-bottom: 1em;
}

@media screen and ( min-width: 768px ) {
    .deck-list-item {
        width: 30%;
        max-width: 280px;
        margin-bottom: 0em;
    }
}
</style>

