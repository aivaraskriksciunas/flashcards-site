<script setup>
import AjaxForm from '../../../components/forms/AjaxForm.vue'
import TextField from '../../../components/forms/TextField.vue'
import HiddenField from '../../../components/forms/HiddenField.vue'
import { useRouter, useRoute } from 'vue-router'
import useAuthentication from '../composables/useAuthentication.js'
import { useAuthenticationState } from '../stores/authenticationState';

const { onLoginSuccessCallback } = useAuthentication();
const authState = useAuthenticationState()

</script>

<template>

    <AjaxForm action="/api/google-link" @success="onLoginSuccessCallback">
        <p class='mb-3'>A user with your email already exists.</p>
        <p class='mb-3'>If you want to link your Aktulibre account with your Google account, enter the password you used for this account.</p>
        <HiddenField name='email' :value="authState.getGoogleLinkPayload().email"></HiddenField>
        <HiddenField name='credential' :value="authState.getGoogleLinkPayload().google_credential"></HiddenField>
        <TextField type="password" name="password">Password:</TextField>
    </AjaxForm>

    <small>Don't want to link accounts? <router-link :to="{ name: 'login' }">Go back</router-link> and login using your password.</small>

</template>