<script setup>
import { useRoute, useRouter } from "vue-router";
import { ref } from 'vue'
import { useUserStore } from "../../stores/user";
import DataLoaderWrapper from '../../components/wrappers/DataLoaderWrapper.vue'
import Card from "../../components/ui/Card.vue";
import DeckPreview from "./components/DeckPreview.vue";
import PlainButton from "../../components/ui/PlainButton.vue";
import OutlineButton from "../../components/ui/OutlineButton.vue";
import SlimContainer from "../../components/ui/SlimContainer.vue";
import DeckSummary from "./components/DeckSummary.vue";
import SelectField from "../../components/forms/SelectField.vue";
import { useUserSettingStore } from '../../stores/user-settings';
import OverflowMenu from '../../components/ui/OverflowMenu.vue';
import DropdownItem from '../../components/ui/DropdownItem.vue';
import Modal from '../../components/ui/Modal.vue';
import { deleteDeck } from '../../services/DeckService';
import { useStatusMessageService, MESSAGE_TYPE } from '../../services/StatusMessageService';

const route = useRoute()
const router = useRouter()
const deckId = route.params.id 
const settings = useUserSettingStore()

const deck = ref( null )
const onLoad = ( data ) => {
    deck.value = data
} 

const { isLoggedIn } = useUserStore()
const { addStatusMessage } = useStatusMessageService()

const quizModes = [
    { value: 'qa', label: 'Question-Answer' },
    { value: 'aq', label: 'Answer-Question' },
    { value: 'mixed', label: 'Mixed' }
];
const quizMode = ref( settings.getPreferredQuizMode( deckId ) || 'qa' );

const onQuizModeChange = ( val ) => {
    settings.setPreferredQuizMode( deckId, val )
}

const confirmDeleteModal = ref( null )
const deleteDeckLoading = ref( false )
const onDeleteDeck = async () => {
    deleteDeckLoading.value = true;

    try {
        await deleteDeck( deck.value.id );
        addStatusMessage( "Deck deleted", `Your deck "${deck.value.name}" has been deleted.`, MESSAGE_TYPE.SUCCESS );
        router.push({ name: 'home' });
    }
    catch ( err ) {
        addStatusMessage( "An error occurred", `We could not delete your deck "${deck.value.name}". Please refresh the page and try again later.`, MESSAGE_TYPE.ERROR );
    }
    finally {
        deleteDeckLoading.value = false;
        confirmDeleteModal.value.closeModal();
    }
}

</script>

<template>

<DataLoaderWrapper :url="`/api/decks/${deckId}`" @load="onLoad">

    <SlimContainer>
        <div class="deck-title-container">
            <div class='deck-title'>
                <h1>{{ deck.name }}</h1>
            </div>
            <router-link :to="{ name: 'edit-deck', params: { id: deck.id } }">
                <PlainButton class='mr-2'>
                    <font-awesome-icon icon="far fa-edit"></font-awesome-icon>
                    Edit
                </PlainButton>
            </router-link>
            <OverflowMenu>
                <DropdownItem @click="confirmDeleteModal.openModal">
                    <div class='flex items-center text-danger'>
                        <font-awesome-icon icon="far fa-trash-can" class='mr-2'></font-awesome-icon>
                        Delete
                    </div>
                </DropdownItem>
            </OverflowMenu>
        </div>

        <div v-if="deck.cards.length">
            <DeckPreview :cards="deck.cards"></DeckPreview>

            <div v-if="isLoggedIn">
                
                <div class="flex mt-3 items-center justify-end text-sm">
                    <div class="mr-2">Quiz mode:</div>
                    <SelectField name="" :value="quizMode" @change="onQuizModeChange" :choices="quizModes"></SelectField>
                </div>

                <div class="flex deck-actions mt-3">
                    <router-link :to="{ name: 'revise-deck', params: { id: deck.id } }">
                        <Card hover>Revise</Card>
                    </router-link>

                    <router-link :to="{ name: 'learn-deck', params: { id: deck.id } }">
                        <Card hover>Learn</Card>
                    </router-link>

                    <router-link :to="{ name: 'practice-deck', params: { id: deck.id } }">
                        <Card hover>Write</Card>
                    </router-link>
                </div>

                <DeckSummary :deck="deck"/>
            </div>
        </div>
        <div v-else>
            This deck is empty. <router-link :to="{ name: 'edit-deck', params: { id: deck.id } }">Click here to add cards.</router-link>
        </div>
        
    </SlimContainer>
    
</DataLoaderWrapper>

<Modal ref="confirmDeleteModal" :isLoading="deleteDeckLoading">
    Are you sure you want to delete this deck?

    <template v-slot:actions>
        <PlainButton class="mr-2" @click="confirmDeleteModal.closeModal">Cancel</PlainButton>
        <OutlineButton type="danger" class="mr-2" @click="onDeleteDeck">Delete</OutlineButton>
    </template>
</Modal>

</template>

<style scoped>

.deck-title-container {
    display: flex;
    align-items: center;
}

.deck-title {
    flex-grow: 1;
}

.deck-actions {
    display: flex;
    gap: 12px;
}

.deck-actions a {
    flex: 1;
}
</style>