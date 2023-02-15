<script setup>
import AjaxForm from '../../components/forms/AjaxForm.vue'
import TextField from '../../components/forms/TextField.vue'
import { useRouter } from 'vue-router'

const router = useRouter()

function onLogin() {
    router.push({ name: 'home' })
}

</script>

<template>

    <AjaxForm action="/api/login" @success="onLogin">
        <TextField type="email" name="email">Email:</TextField>
        <TextField type="password" name="password">Password:</TextField>
    </AjaxForm>

</template>