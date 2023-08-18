<script setup>
import jsCookie from 'js-cookie';
import { useRouter } from 'vue-router';
import { useUserStore } from '../../stores/user';

const router = useRouter()
const { setCurrentUser } = useUserStore()

setCurrentUser( null )
jsCookie.remove( 'api_key' )

router.push({ name: 'login' })

</script>

<template>Loggin out...</template>