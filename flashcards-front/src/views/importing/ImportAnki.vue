<script setup>
import UploadField from '../../components/forms/UploadField.vue';
import SlimContainer from '../../components/ui/SlimContainer.vue';
import Card from '../../components/ui/Card.vue';
import Header from '../../components/common/Header.vue';
import { Button } from '@/components/ui/button';
import AjaxForm from '../../components/forms/AjaxForm.vue';
import CheckboxField from '../../components/forms/CheckboxField.vue';
import { useRouter } from 'vue-router';
import { useStatusMessageService, MESSAGE_TYPE } from '../../services/StatusMessageService';
import { ref } from 'vue';

const router = useRouter();
const { addStatusMessage } = useStatusMessageService();

const createdDecks = ref( null )

const onImport = ( data ) => {
    if ( data.length == 1 ) {
        router.push({ name: 'edit-deck', params: { id: data[0].id } });
        addStatusMessage( 'Deck created successfully', 'Your deck was imported without errors.', MESSAGE_TYPE.SUCCESS, 5000 );
    }
    else {
        createdDecks.value = data;
    }
}

</script>

<template>
<SlimContainer>
    <div v-if="!createdDecks">
        <h1>Import from Anki list</h1>
        <p class="mb-4">Create an Aktulibre deck from an Anki deck. In Anki, choose the deck you want to export (click the cogwheel button), choose "export" and choose "notes in plain text". You may read more information <a href="https://docs.ankiweb.net/exporting.html">here</a>.</p>

        <Card>
            <AjaxForm action="/api/import/anki" @success="onImport" multipart>
                <UploadField name="file"></UploadField>
                <CheckboxField name="many_decks">Create multiple decks (if the imported file is a collection of decks).</CheckboxField>
            </AjaxForm>
        </Card>
    </div>
    <div v-else>
        <Card>
            <Header>{{ createdDecks.length }} decks created</Header>
            <div v-for="deck of createdDecks" class="text-xl">
                <router-link :to="{ name: 'view-deck', params: { id: deck.id } }">{{ deck.name }}</router-link>
            </div>

            <div class="flex mt-6 items-center">
                <Button @click="router.push({ name: 'home' })" class="mr-4">Back to home</Button>
                <Button variant="ghost" @click="createdDecks = null">Import again</Button>
            </div>
            
        </Card>
    </div>
</SlimContainer>
</template>
