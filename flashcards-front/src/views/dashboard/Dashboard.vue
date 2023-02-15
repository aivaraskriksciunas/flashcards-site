<script setup>
import { useUserStore } from '../../stores/user';
import Header from '../../components/common/Header.vue'
import DeckList from './_DeckList.vue'

const { user } = useUserStore()

</script>

<template>
    <Header>Your decks</Header>

    <DeckList></DeckList>

</template>