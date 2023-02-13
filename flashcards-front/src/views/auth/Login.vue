<script setup>
import AjaxForm from '../../components/forms/AjaxForm.vue'
import TextField from '../../components/forms/TextField.vue'

</script>

<template>

    <AjaxForm action="/api/login">
        <TextField type="email" name="email">Email:</TextField>
        <TextField type="password" name="password">Password:</TextField>
    </AjaxForm>

</template>