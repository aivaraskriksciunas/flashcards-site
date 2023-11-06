<script setup>
import { onMounted } from 'vue'
import useGoogleLogin from '../composables/useGoogleLogin.js'

const { initializeGoogleLogin } = useGoogleLogin();
initializeGoogleLogin()

onMounted(() => {
    google.accounts.id.renderButton(
        document.getElementById( "googleSignIn" ),
        { 
            theme: "outline", 
            size: "large",
            text: "signin_with",
        }
    );
})

</script>

<template>

    <div id='googleSignIn'></div>

</template>