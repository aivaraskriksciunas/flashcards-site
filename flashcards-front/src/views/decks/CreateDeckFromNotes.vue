<script setup>
import EditForm from './components/EditForm.vue'
import DataLoaderWrapper from '../../components/wrappers/DataLoaderWrapper.vue';
import { useRoute, useRouter } from 'vue-router';
import { ref } from 'vue';
import axios from 'axios';

const route = useRoute()
const router = useRouter()

const deck = ref( null )
const onLoad = ( d ) => {
    let newDeck = {
        'name': "Untitled deck",
        'cards': [],
    }

    if ( !Array.isArray( d ) ) {
        router.replace({ name: 'create-deck' });
    }
    
    d.forEach( ( card ) => {
        try {
            let data = JSON.parse( card )
            
            if ( 'question' in data || 'answer' in data ) {
                newDeck['cards'].push({
                    'question': data['question'] ?? '',
                    'answer': data['answer'] ?? ''
                })
            }
        }
        catch ( e ) {}
    })

    deck.value = newDeck
}

const onSave = ( createdDeck ) => {
    axios.delete( '/api/notes' ); // Clear existing array
    router.replace({ name: 'view-deck', params: { id: createdDeck.id } })
}

</script>

<template>

<DataLoaderWrapper :url="`/api/notes`" @load="onLoad">
    <h1>Create deck from notes</h1>
    <EditForm :deck="deck" @save="onSave"></EditForm>
</DataLoaderWrapper>
    
</template>