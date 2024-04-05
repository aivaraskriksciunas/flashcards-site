<script setup>
import DataLoaderWrapper from '@/components/wrappers/DataLoaderWrapper.vue';
import { ref } from 'vue';
import Button from '@/components/ui/button/Button.vue';

const hasData = ref( false )

const onLoad = ( data ) => {
    hasData.value = data && data.length
}

</script>

<template>

<DataLoaderWrapper url="/api/notes" @load="onLoad">
    <template #loading>&nbsp;</template>
    <template #error>&nbsp;</template>
    <div v-if="hasData" class="mb-4">
        You have unsaved notes! Click the button to create a deck and start learning. 
        <router-link :to="{ name: 'create-deck-from-notes'}">
            <Button class="ml-2">Create deck from notes</Button>
        </router-link>
    </div>
</DataLoaderWrapper>

</template>