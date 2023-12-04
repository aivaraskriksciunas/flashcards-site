<script setup>
import AjaxForm from '../../components/forms/AjaxForm.vue'
import TextField from '../../components/forms/TextField.vue'
import { useRouter } from 'vue-router'
import jsCookie from 'js-cookie';

const router = useRouter()

function onRegister( data ) {
    if ( data.token ) {
        jsCookie.set( 'api_key', data.token, { expires: 40, sameSite: 'strict' } )
    }
    
    router.push({ name: 'home' })
}

</script>

<template>

    <AjaxForm action="/api/register" captcha="REGISTER" @success="onRegister">
        <TextField type="text" name="name">Your name:</TextField>
        <TextField type="email" name="email">Email:</TextField>
        <TextField type="password" name="password">Password:</TextField>
        <TextField type="password" name="password_confirmation">Confirm password:</TextField>
    </AjaxForm>

    <small>Already have an account? <router-link :to="{ name: 'login' }">Log in</router-link></small>

</template>