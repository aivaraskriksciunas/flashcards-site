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
    <AjaxForm action="/api/register" captcha="REGISTER" @success="props.onRegister">
        <TextField type="text" name="name">Your name:</TextField>
        <TextField type="email" name="email">Email:</TextField>
        <TextField type="password" name="password">Password:</TextField>
        <TextField type="password" name="password_confirmation">Confirm password:</TextField>
    </AjaxForm>
</template>