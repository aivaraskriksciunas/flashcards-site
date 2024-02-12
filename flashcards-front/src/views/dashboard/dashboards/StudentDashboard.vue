<script setup>
import Header from '../../../components/common/Header.vue'
import DeckList from '../components/DeckList.vue'
import { 
    DropdownMenu, 
    DropdownMenuTrigger,
    DropdownMenuItem,
    DropdownMenuContent,
} from '@/components/ui/dropdown-menu'
import Button from '@/components/ui/button/Button.vue';
import { Plus } from 'lucide-vue-next';

</script>

<template>
    <Header>
        Your decks
        <template v-slot:actions>
            <div class="flex">
                <router-link :to="{ name: 'create-deck' }">
                    <Button class='mr-2' icon="fas fa-plus">
                        <Plus size="16" class="mr-2"/>
                        Create deck
                    </Button>
                </router-link>

                <DropdownMenu>
                    <DropdownMenuTrigger>
                        <Button variant="outline">Import from</Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent>
                        <router-link :to="{name: 'import-quizlet'}">
                            <DropdownMenuItem>Quizlet</DropdownMenuItem>
                        </router-link>
                        <router-link :to="{name: 'import-anki'}">
                            <DropdownMenuItem>Anki</DropdownMenuItem>
                        </router-link>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </template>
    </Header>

    <DeckList></DeckList>

</template>