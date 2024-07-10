<script setup>
import { ref } from 'vue';
import DataLoaderWrapper from '../../../components/wrappers/DataLoaderWrapper.vue';
import DeckListItem from './DeckListItem.vue';
import { Skeleton } from '@/components/ui/skeleton';
import Card from '@/components/ui/Card.vue';

const decks = ref([])

const onDataLoad = ( data ) => { decks.value = data }

</script>

<template>
    <DataLoaderWrapper url="/api/library" @load="onDataLoad">
        
        <template #loading>
            <div class="md:flex deck-list">
                <Card class="deck-list-item">
                    <Skeleton class="w-full h-8 mb-2"></Skeleton>
                    <Skeleton class="w-1/3 h-4"></Skeleton>
                </Card>
                <Card class="deck-list-item">
                    <Skeleton class="w-full h-8 mb-2"></Skeleton>
                    <Skeleton class="w-1/3 h-4"></Skeleton>
                </Card>
            </div>
        </template>

        <div class="md:flex deck-list" v-if="decks.length > 0">
            <DeckListItem 
                v-for="deck of decks" :key="deck.id" 
                :deck="deck" 
                class="deck-list-item">
            </DeckListItem>
        </div>
        <div v-else>
            <Card>
                <p>
                    You have not created any decks yet.
                    <router-link :to="{ name: 'create-deck' }">Create deck</router-link>.
                </p>
            </Card>
        </div>
    </DataLoaderWrapper>
</template>

<style scoped>
.deck-list {
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

