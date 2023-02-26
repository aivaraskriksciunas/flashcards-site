<script setup>
import { ref } from 'vue';
import DataLoaderWrapper from '../../components/wrappers/DataLoaderWrapper.vue';

const decks = ref([])

const onDataLoad = ( data ) => { decks.value = data }

</script>

<template>

<DataLoaderWrapper url="/api/library" @load="onDataLoad">
    
    <div v-for="deck of decks" :key="deck.id" class="mb-4">
        <router-link :to="{ name: 'edit-deck', params: { id: deck.id } }">
            <div class="font-bold">{{ deck.name }}</div>
        </router-link>
        <div>
            <p>Cards: {{ deck.card_count }}</p>
        </div>
    </div>

</DataLoaderWrapper>

</template>