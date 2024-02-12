<script setup>
import { onMounted, ref } from 'vue'
import useGoogleLogin from '../composables/useGoogleLogin.js'

const { initializeGoogleLogin } = useGoogleLogin();
const buttonContainer = ref( null )
initializeGoogleLogin()

onMounted(() => {
    google.accounts.id.renderButton(
        document.getElementById( "googleSignIn" ),
        { 
            theme: "outline", 
            size: "large",
            text: "signin_with",
            width: buttonContainer.value.scrollWidth,
        }
    );
})

</script>

<template>

    <div id='googleSignIn' ref="buttonContainer"></div>

</template>