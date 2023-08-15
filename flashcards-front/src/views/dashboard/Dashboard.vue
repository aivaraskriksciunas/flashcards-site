<script setup>
import { useUserStore } from '../../stores/user';
import Header from '../../components/common/Header.vue'
import DeckList from './_DeckList.vue'
import IconButton from '../../components/ui/IconButton.vue';

const { user } = useUserStore()

</script>

<template>
    <Header>
        Your decks
        <template v-slot:actions>
            <IconButton icon="fa-solid fa-plus" text="New deck" filled></IconButton>
        </template>
    </Header>

    <DeckList></DeckList>

</template>