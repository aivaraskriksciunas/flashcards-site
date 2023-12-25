<script setup>

import AjaxForm from '../../../components/forms/AjaxForm.vue'
import TextField from '../../../components/forms/TextField.vue'

const props = defineProps({
    onRegister: {
        type: Function,
        required: true
    }
})

</script>

<template>
    <AjaxForm action="/api/register-org" captcha="REGISTER_ORG" @success="props.onRegister">
        <TextField type="text" name="name" placeholder="John Johnathan">Your full name:</TextField>
        <TextField type="email" name="email" placeholder="john@aktulibre.eu">Email:</TextField>
        <TextField type="password" name="password">Password:</TextField>
        <TextField type="password" name="password_confirmation">Confirm password:</TextField>
    </AjaxForm>
</template>