<script setup>
import { useRouter } from 'vue-router';
import Header from '../../components/common/Header.vue'
import EditForm from './components/EditForm.vue'

const router = useRouter();

const onDeckCreate = ( deck ) => {
    router.push({ name: 'edit-deck', params: { id: deck.id } })
}

</script>

<template>
    <Header>New deck</Header>

    <EditForm @save="onDeckCreate"></EditForm>
</template>
