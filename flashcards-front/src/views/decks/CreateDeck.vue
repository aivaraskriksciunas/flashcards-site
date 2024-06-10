<script setup>
import { useRouter } from 'vue-router';
import { createEmptyDeck } from '@/services/DeckService';
import { useStatusMessageService, MESSAGE_TYPE } from '@/services/StatusMessageService';

const router = useRouter();
const statusMessages = useStatusMessageService()

createEmptyDeck( 'Untitled deck' )
.then( ( res ) => {
    router.push({ name: 'edit-deck', params: { id: res.data.id } })
})
.catch( () => {
    statusMessages.addStatusMessage( 'Error', 'Could not create an empty new deck. Please refresh the page and try again.', MESSAGE_TYPE.ERROR )
})

</script>

<template>
    Creating new deck...
</template>
