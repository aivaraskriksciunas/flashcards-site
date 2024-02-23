<script setup>
import AjaxForm from '@/components/forms/AjaxForm.vue';
import TextField from '@/components/forms/TextField.vue';
import DataLoaderWrapper from '@/components/wrappers/DataLoaderWrapper.vue';
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import useAuthentication from './composables/useAuthentication';

const route = useRoute();
const router = useRouter();
const { setToken } = useAuthentication()

const invitation = ref( null );
const onLoad = ( data ) => {
    invitation.value = data;
}

const onAccepted = ( data ) => {
    setToken( data.token );

    router.push({ name: 'home' });
}

</script>

<template>
    <DataLoaderWrapper :url="`/api/invitations/${route.params.invitation_code}`" @load="onLoad">
        <h2 class="text-center">Join "{{ invitation.organization.name }}"</h2>

        <AjaxForm :action="`/api/invitations/${route.params.invitation_code}/accept`" method="POST" @success="onAccepted">
            <TextField disabled :value="invitation.name">
                Name
            </TextField>

            <TextField disabled :value="invitation.email">
                Email
            </TextField>

            <TextField 
                type="password" 
                name="password" 
                note="If you already have an account on Aktulibre, this will be your new password.">
                Password
            </TextField>
        </AjaxForm>
    </DataLoaderWrapper>

</template>