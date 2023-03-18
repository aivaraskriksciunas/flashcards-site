<script setup>
import { useRouter } from 'vue-router';
import { useUserStore } from '../../stores/user';

const router = useRouter()
const { setCurrentUser } = useUserStore()

setCurrentUser( null )
localStorage.setItem( 'api_key', null )

router.push({ name: 'login' })

</script>

<template>Loggin out...</template>