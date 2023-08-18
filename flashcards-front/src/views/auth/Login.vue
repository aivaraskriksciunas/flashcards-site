<script setup>
import AjaxForm from '../../components/forms/AjaxForm.vue'
import TextField from '../../components/forms/TextField.vue'
import { useRouter, useRoute } from 'vue-router'
import jsCookie from 'js-cookie';

const router = useRouter()
const route = useRoute()

function onLogin( data ) {
    if ( data.token ) {
        jsCookie.set( 'api_key', data.token, { expires: 40, sameSite: 'strict' } )
    }
    
    if ( route.query.r ) {
        router.push( route.query.r )
    }
    else {
        router.push({ name: 'home' })
    }
}

</script>

<template>

    <AjaxForm action="/api/login" @success="onLogin">
        <TextField type="email" name="email">Email:</TextField>
        <TextField type="password" name="password">Password:</TextField>
    </AjaxForm>

    <small>Don't have an account? <router-link :to="{ name: 'register' }">Register</router-link></small>

</template>