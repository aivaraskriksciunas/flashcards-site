<script setup>
import { RouterView, useRouter } from 'vue-router'
import ServerConnectionWrapper from './components/wrappers/ServerConnectionWrapper.vue';
import axios from 'axios';
import StatusMessages from '@/components/common/StatusMessages.vue';
import TooltipProvider from './components/ui/tooltip/TooltipProvider.vue';

const router = useRouter()

axios.interceptors.response.use( 
    response => response,
    ( error ) => {
        
        if ( error.response.status === 401 && error.config.url != '/api/user' ) {
            router.push({ name: 'login' });
            return;
        }

        const action = error.response.data.required_action;
        if ( action === 'register-organization' ) {
            router.push({ name: 'register-org' });
        }
        else if ( action === 'select-account-type' ) {
            router.push({ name: 'account-type' });
        }

        return Promise.reject( error )
    }
)

</script>

<template>
    <ServerConnectionWrapper>
        <TooltipProvider :delay-duration="100">
            <RouterView />
        </TooltipProvider>
    </ServerConnectionWrapper>
    
    <StatusMessages/>
</template>

<style scoped>
</style>
