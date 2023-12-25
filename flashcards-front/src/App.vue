<script setup>
import { RouterView, useRouter } from 'vue-router'
import ServerConnectionWrapper from './components/wrappers/ServerConnectionWrapper.vue';
import axios from 'axios';

const router = useRouter()

axios.interceptors.response.use( 
    response => response,
    ( error ) => {
        
        if ( error.response.status === 401 ) {
            router.push({ name: 'login' });
        }

        const action = error.response.data.required_action;
        if ( action === 'register-organization' ) {
            router.push({ name: 'register-org' });
        }

        return Promise.reject( error )
    }
)

</script>

<template>
    <ServerConnectionWrapper>
        <RouterView />
    </ServerConnectionWrapper>
</template>

<style scoped>
</style>
