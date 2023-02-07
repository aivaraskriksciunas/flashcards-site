<script setup>
import AjaxForm from '../../components/forms/AjaxForm.vue'
import TextField from '../../components/forms/TextField.vue'

</script>

<template>

    <form>
        <AjaxForm>
            <TextField type="email" name="email">Email:</TextField>

            <p>A long paragraph about something</p>
            <TextField type="password" name="password">Password:</TextField>

            <div class='nest'>
                <TextField type="text" name="findme">Will you find me?</TextField>
            </div>
        </AjaxForm>
    </form>

</template>