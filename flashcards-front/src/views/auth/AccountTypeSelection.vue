<script setup>
import { useRouter } from 'vue-router';
import AccountTypeSelector from './components/AccountTypeSelector.vue';
import { useUserStore } from '@/stores/user';
import { ref } from 'vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import { setAccountType } from '@/services/AccountService';
import { MESSAGE_TYPE, useStatusMessageService } from '@/services/StatusMessageService';

const router = useRouter()
const userStore = useUserStore()
const isLoading = ref( false )
const statusMessageService = useStatusMessageService()

if ( userStore.isLoggedIn && !userStore.isUndefinedAccountType() ) {
    router.replace({ name: 'home' })
}

const onSelect = async ( type ) => {
    isLoading.value = true;

    // If this user is logged in but their type is undefined, request to change it
    if ( userStore.isUndefinedAccountType() ) {
        try {
            await setAccountType( type )
            router.replace({ name: 'home' })
        }
        catch( e ) {
            statusMessageService.addStatusMessage( "Error setting type", "Could not set your account's type. Refresh the page or try again later.", MESSAGE_TYPE.ERROR )
            isLoading.value = false
        }

        return;
    }

    router.push({ name: 'register', query: { as: type } })
}

</script>

<template>
    <h2 class="text-center">Choose your account type</h2>

    <div class="mb-3">
        <AccountTypeSelector v-if="!isLoading" @selected="onSelect"/>
        <LoadingIndicator v-else/>
    </div>
</template>
