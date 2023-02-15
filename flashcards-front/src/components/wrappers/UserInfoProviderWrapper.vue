<script setup>
import axios from 'axios'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '../../stores/user'

const state = ref( 'loading' )
const router = useRouter()
const { isLoggedIn, setCurrentUser } = useUserStore()

if ( isLoggedIn ) {
    state.value = 'loaded'
}

else {
    // User information is not stored, retrieve it from the server
    axios.get( '/api/user' )
    .then( ( response ) => {
        setCurrentUser( response.data )
        state.value = 'loaded'
    })
    .catch( () => {
        // Error, maybe user is not logged in? Go to login page
        router.push({ name: 'login' })
    })
}

</script>

<template>

<div v-if="state == 'loading'">Loading user information...</div>

<div v-else-if="state == 'loaded'">
    <slot></slot>
</div>

</template>