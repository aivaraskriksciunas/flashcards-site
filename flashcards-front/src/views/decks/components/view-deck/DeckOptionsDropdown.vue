<script setup>
import { ref } from 'vue';
import { useRouter } from "vue-router";
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { 
    DropdownMenu, 
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuItem,
} from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { deleteDeck } from '@/services/DeckService';
import { useStatusMessageService, MESSAGE_TYPE } from '@/services/StatusMessageService';
import { Loader2, MoreVertical, Trash2 } from "lucide-vue-next";

const { deck } = defineProps({
    deck: {
        required: true,
        type: Object,
    }
})

const router = useRouter()
const { addStatusMessage } = useStatusMessageService()


const confirmDeleteModalOpen = ref( false )
const deleteDeckLoading = ref( false )
const onDeleteDeck = async () => {
    deleteDeckLoading.value = true;

    try {
        await deleteDeck( deck.id );
        addStatusMessage( "Deck deleted", `Your deck "${deck.name}" has been deleted.`, MESSAGE_TYPE.SUCCESS );
        router.push({ name: 'home' });
    }
    catch ( err ) {
        addStatusMessage( "An error occurred", `We could not delete your deck "${deck.name}". Please refresh the page and try again later.`, MESSAGE_TYPE.ERROR );
    }
    finally {
        deleteDeckLoading.value = false;
        confirmDeleteModalOpen.value = false;
    }
}
</script>

<template>

    <DropdownMenu v-if="deck.permissions['delete']">
        <DropdownMenuTrigger>
            <Button variant="ghost" size="icon">
                <MoreVertical />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent>
            <DropdownMenuItem @click="confirmDeleteModalOpen = true" class="text-destructive">
                <Trash2 class="mr-2" size="16"/> Delete
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>

    <AlertDialog :open="confirmDeleteModalOpen">
        <AlertDialogTrigger>
        </AlertDialogTrigger>
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Confirm delete</AlertDialogTitle>
                <AlertDialogDescription>Are you sure you want to delete this deck?</AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel @click="confirmDeleteModalOpen = false">Cancel</AlertDialogCancel>
                <Button variant="destructive" @click="onDeleteDeck" :disabled="deleteDeckLoading">
                    <Loader2 v-if="deleteDeckLoading" class="h-4 w-4 mr-2 animate-spin"></Loader2>
                    Delete
                </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>