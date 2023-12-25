<script setup>
import Header from '../../../components/common/Header.vue'
import DeckList from '../components/DeckList.vue'
import IconButton from '../../../components/ui/IconButton.vue';
import { useUserStore } from '../../../stores/user';

const user = useUserStore().user;

</script>

<template>
    <Header>
        {{ user.organization.name }}
        <template v-slot:actions>
            <div class="flex">
                <IconButton class='mr-2' icon="fas fa-plus" text="Create deck" filled size="sm" :to="{ name: 'create-deck' }">
                    Create deck
                </IconButton>
            </div>
        </template>
    </Header>

    <DeckList></DeckList>

</template>