<script setup>
import { useAuthenticationState, AUTH_STATES } from './stores/authenticationState.js'
import LinkGoogleAccount from './components/LinkGoogleAccount.vue'
import LoginForm from './components/LoginForm.vue'

const authState = useAuthenticationState()
authState.setStateLogin()

</script>

<template>

    <div v-if="authState.authState.value == AUTH_STATES.LOGIN">
        <LoginForm></LoginForm>
    </div>
    <div v-else-if="authState.authState.value == AUTH_STATES.GOOGLE_LINK">
        <LinkGoogleAccount></LinkGoogleAccount>
    </div>

</template>