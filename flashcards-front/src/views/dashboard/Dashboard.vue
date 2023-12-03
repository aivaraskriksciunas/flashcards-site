<script setup>
import { useUserStore } from '../../stores/user';
import Header from '../../components/common/Header.vue'
import DeckList from './_DeckList.vue'
import Button from '../../components/ui/Button.vue';
import DropdownButton from '../../components/ui/DropdownButton.vue';
import DropdownButtonItem from '../../components/ui/DropdownItem.vue';
import IconButton from '../../components/ui/IconButton.vue';

const { user } = useUserStore()

</script>

<template>
    <Header>
        Your decks
        <template v-slot:actions>
            <div class="flex">
                <IconButton class='mr-2' icon="fas fa-plus" text="Create deck" filled size="sm" :to="{ name: 'create-deck' }">
                    Create deck
                </IconButton>

                <DropdownButton label="Import from">
                    <DropdownButtonItem :to="{name: 'import-quizlet'}">Quizlet</DropdownButtonItem>
                    <DropdownButtonItem :to="{name: 'import-anki'}">Anki</DropdownButtonItem>
                </DropdownButton>
            </div>
        </template>
    </Header>

    <DeckList></DeckList>

</template>