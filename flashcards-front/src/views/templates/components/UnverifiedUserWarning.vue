<script setup>
import { storeToRefs } from "pinia";
import { ref } from "vue";
import { useUserStore } from "../../../stores/user";

const { user, isLoggedIn } = storeToRefs( useUserStore() )
const { resendVerificationCode } = useUserStore()

const resendState = ref( 'initial' )
const errorMessage = ref( '' )

const onResend = () => {
    resendState.value = 'loading'

    const res = resendVerificationCode()
    if ( !res ) {
        resendState.value = 'error'
        errorMessage.value = 'There was an error while sending the email. Please refresh the page and try again.'
        return;
    }

    res.then( () => {
        resendState.value = "sent"
    } )
    .catch( ( err ) => {
        resendState.value = "error"
        errorMessage.value = "There was an error while sending the email. Please refresh the page and try again."
        if ( err.response.data.message ) {
            errorMessage.value = err.response.data.message
        }
    } ) 
    .finally( () => {
        setTimeout(() => {
            resendState.value = "initial";
        }, 10000 )
    })
}

</script>

<template>
<div v-if='isLoggedIn && !user.is_valid' class='unverified-warning'>
    <div class='text-white'>You may not perform any actions until your account is verified. Check your inbox for verification code.</div>
    <div v-if='resendState == "initial"' 
        class='text-white'>
        Email didn't arrive? <span @click="onResend" class='text-white underline cursor-pointer'>Resend email</span>
    </div>
    <div v-else-if='resendState == "loading"'
        class='text-white'>
        Loading...
    </div>
    <div v-else-if='resendState == "sent"'
        class='text-white'>
        Confirmation email resent. Please check your spam if you still do not receive it.
    </div>
    <div v-else-if='resendState == "error"'
        class='text-white'>
        {{ errorMessage }}
    </div>
</div>
</template>

<style scoped>
.unverified-warning {
    
    background-color: rgb( var( --destructive ) );
    color: white;
    width: 100%;
    padding: 8px 16px;
    text-align: center;
}
</style>