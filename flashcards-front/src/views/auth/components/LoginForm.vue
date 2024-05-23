<script setup>
import GoogleLogin from './GoogleLogin.vue'
import AjaxForm from '../../../components/forms/AjaxForm.vue'
import TextField from '../../../components/forms/TextField.vue'
import useAuthentication from '../composables/useAuthentication.js'

const { onLoginSuccessCallback } = useAuthentication()

</script>

<template>
    <div class='mb-4'>
        <GoogleLogin></GoogleLogin>
    </div>

    <AjaxForm action="/api/login" @success="onLoginSuccessCallback">
        <TextField type="email" name="email">Email:</TextField>
        <TextField type="password" name="password">
            Password:
            <template #note>
                <router-link :to="{ name: 'forgot-password' }">Forgot password?</router-link>
            </template>    
        </TextField>
    </AjaxForm>

    <small>
        Don't have an account? <router-link :to="{ name: 'register' }">Register</router-link>. 
        <br>
    </small>
</template>