<script setup>
import { useRoute, useRouter } from "vue-router";
import { ref } from 'vue'
import { useUserStore } from "../../stores/user";
import DataLoaderWrapper from '../../components/wrappers/DataLoaderWrapper.vue'
import Card from "../../components/ui/Card.vue";
import DeckPreview from "./components/DeckPreview.vue";
import { Button } from '@/components/ui/button';
import SlimContainer from "../../components/ui/SlimContainer.vue";
import DeckSummary from "./components/DeckSummary.vue";
import SelectField from "../../components/forms/SelectField.vue";
import { useUserSettingStore } from '../../stores/user-settings';

import { 
    Sheet,
    SheetContent,
    SheetClose,
    SheetTrigger,
    SheetHeader, 
    SheetTitle,
    SheetDescription,
    SheetFooter,
} from '@/components/ui/sheet';
import { Settings } from "lucide-vue-next";
import DeckOptionsDropdown from './components/view-deck/DeckOptionsDropdown.vue';
import DeckShortcutOptions from './components/view-deck/DeckShortcutOptions.vue';

const route = useRoute()
const router = useRouter()
const deckId = route.params.id 
const settings = useUserSettingStore()

const deck = ref( null )
const onLoad = ( data ) => {
    deck.value = data
} 

const { isLoggedIn } = useUserStore()

const quizModes = [
    { value: 'qa', label: 'Question-Answer' },
    { value: 'aq', label: 'Answer-Question' },
    { value: 'mixed', label: 'Mixed' }
];
const quizMode = ref( settings.getPreferredQuizMode( deckId ) || 'qa' );

const onQuizModeChange = ( val ) => {
    quizMode.value = val
    settings.setPreferredQuizMode( deckId, val )
}

const revisionSizes = [
    { value: '5', label: '5 cards' },
    { value: '10', label: '10 cards' },
    { value: '15', label: '15 cards' },
    { value: '20', label: '20 cards' },
    { value: 'all', label: 'Entire deck' },
]
const revisionSize = ref( settings.getPreferredQuizSize( deckId ) || '10' )

const onRevisionSizeChange = ( val ) => {
    revisionSize.value = val
    settings.setPreferredQuizSize( deckId, val )
}

</script>

<template>

<DataLoaderWrapper :url="`/api/decks/${deckId}`" @load="onLoad">

    <SlimContainer>
        <div class="deck-title-container">
            <div class='deck-title'>
                <h1>{{ deck.name }}</h1>
            </div>
            
            <DeckShortcutOptions :deck="deck"/>

            <DeckOptionsDropdown :deck="deck"/>
        </div>

        <div v-if="deck.cards.length">
            <DeckPreview :cards="deck.cards"></DeckPreview>

            <div v-if="isLoggedIn">
                
                <div class="flex mt-3 items-center justify-end text-sm">
                    <Sheet>
                        <SheetTrigger>
                            <Button variant="ghost" class="text-muted-foreground">Revision options <Settings class="ml-1" size="22"/></Button>
                        </SheetTrigger>
                        <SheetContent>
                            <SheetHeader class="mb-6">
                                <SheetTitle>Revision options</SheetTitle>
                                <SheetDescription>
                                    Fine-tune your revision generation. 
                                </SheetDescription>
                            </SheetHeader>

                            <SelectField class="mb-3" name="" :value="quizMode" @change="onQuizModeChange" :choices="quizModes">
                                Revision mode
                            </SelectField>
                            <SelectField class='mb-3' name="" :value="revisionSize" @change="onRevisionSizeChange" :choices="revisionSizes">
                                Cards per revision
                            </SelectField>

                            <SheetFooter>
                                <SheetClose>
                                    <Button>Done</Button>
                                </SheetClose>
                            </SheetFooter>
                        </SheetContent>
                    </Sheet>
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