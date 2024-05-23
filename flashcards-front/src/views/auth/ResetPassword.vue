<script setup>
import { useRouter, useRoute } from 'vue-router'
import AjaxForm from '@/components/forms/AjaxForm.vue';
import TextField from '@/components/forms/TextField.vue';
import { ref } from 'vue';
import { Button } from '@/components/ui/button'
import { useStatusMessageService, MESSAGE_TYPE } from '@/services/StatusMessageService';

const route = useRoute()
const router = useRouter()
const statusMessage = useStatusMessageService();

const onSubmit = () => {
    statusMessage.addStatusMessage( 'Password changed', 'Your password has been updated. You may now login.', MESSAGE_TYPE.SUCCESS );
    router.push({ name: 'login' })
}

</script>

<template>
    
    <h2>Reset password</h2>

    <AjaxForm :action="`/api/reset-password/${route.params.reset_code}`" 
        @success="onSubmit" 
        submit-text="Change password">
        <TextField type="password" name="password">New password:</TextField>
        <TextField type="password" name="password_confirmation">Confirm password:</TextField>
    </AjaxForm>

    <small><router-link :to="{ name: 'login' }">Back to login</router-link></small>

</template>