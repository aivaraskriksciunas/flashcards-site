<script setup>
import DataLoaderWrapper from '../../../components/wrappers/DataLoaderWrapper.vue';

const { deck } = defineProps({
    deck: {
        required: true
    }
})

const onLoad = ( data ) => {
    console.log( data )
}

</script>

<template>
<DataLoaderWrapper :url="`/api/decks/${deck.id}/summary`" @load="onLoad"> 

</DataLoaderWrapper>
</template>