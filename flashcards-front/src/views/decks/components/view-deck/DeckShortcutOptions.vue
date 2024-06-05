<script setup>
import { Pencil, Bookmark, Copy, Loader2, BookmarkCheck } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { ref } from 'vue';
import { copyDeck, addDeckToLibrary } from '@/services/DeckService';
import { useRouter } from 'vue-router';
import { MESSAGE_TYPE, useStatusMessageService } from '@/services/StatusMessageService';

const { deck } = defineProps({
    deck: {
        required: true,
        type: Object,
    }
})

const router = useRouter();
const statusMessage = useStatusMessageService()

const isLoadingSave = ref( false )
const isLoadingCopy = ref( false )

const onSaveClicked = async () => {
    isLoadingSave.value = true 

    try {
        await addDeckToLibrary( deck.id )

        if ( deck.in_library == false ) {
            statusMessage.addStatusMessage( "Deck has been saved to your library", "", MESSAGE_TYPE.SUCCESS )
        }
        else {
            statusMessage.addStatusMessage( "Deck has been removed from your saved deck list", "", MESSAGE_TYPE.SUCCESS )
        }
        deck.in_library = !deck.in_library
    }
    catch( e ) {
        statusMessage.addStatusMessage( "Could not save deck", "There was an error adding deck to your library. Make sure the deck is still publicly visible and try again.", MESSAGE_TYPE.ERROR )
    }
    finally {
        isLoadingSave.value = false 
    }
}

const onCopyClicked = async () => {
    isLoadingCopy.value = true

    try {
        let res = await copyDeck( deck.id )
        statusMessage.addStatusMessage( "Deck has been copied successfully", "", MESSAGE_TYPE.SUCCESS )
        router.push({ name: 'edit-deck', params: { id: res.data.id }, replace: true })
    }
    catch( e ) {
        statusMessage.addStatusMessage( "Could not save deck", "There was an error creating a copy of the deck. Make sure the deck is still publicly visible and try again.", MESSAGE_TYPE.ERROR )
    }
    finally {
        isLoadingSave.value = false 
    }
}

</script>

<template>

    <router-link v-if="deck.permissions['update']" :to="{ name: 'edit-deck', params: { id: deck.id } }">
        <Button variant="ghost" size="sm" class="text-muted-foreground">
            <Pencil class="w-4 h-4 mr-2" color="rgb( var( --muted-foreground ) )"/>
            Edit
        </Button>
    </router-link>
    <div v-else>
        <Button variant="ghost" class="mr-2" :disabled="isLoadingSave" @click="onSaveClicked">
            <Bookmark v-if="!isLoadingSave && !deck.in_library" size="16" class="mr-2"/>
            <BookmarkCheck v-else-if="!isLoadingSave && deck.in_library" size="16" class="mr-2 text-primary"/>
            <Loader2 v-else size="16" class="mr-2 animate-spin"/>
            
            <span v-if='!deck.in_library'>Save</span>
            <span v-else>Saved</span>
        </Button>
        <Button variant="ghost" :disabled="isLoadingCopy" @click="onCopyClicked">
            <Copy v-if="!isLoadingCopy" size="16" class="mr-2"/>
            <Loader2 v-else size="16" class="mr-2 animate-spin"/>
            Create copy
        </Button>
    </div>

</template>