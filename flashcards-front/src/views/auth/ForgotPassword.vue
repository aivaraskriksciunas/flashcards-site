<script setup>
import { useRouter, useRoute } from 'vue-router'
import AjaxForm from '@/components/forms/AjaxForm.vue';
import TextField from '@/components/forms/TextField.vue';
import { ref } from 'vue';
import { Button } from '@/components/ui/button'

const submitted = ref( false )

</script>

<template>
    
    <div v-if="!submitted">
        <h2>Forgot password</h2>
        <p class="mb-4">Enter your email and we will send you a password reset link.</p>
        <AjaxForm action="/api/forgot-password" @success="submitted = true" submit-text="Reset password">
            <TextField type="email" name="email">Email:</TextField>
        </AjaxForm>

        <small><router-link :to="{ name: 'login' }">Back to login</router-link></small>
    </div>
    <div v-else>
        <h2>Check your inbox</h2>
        <p class="mb-4">A reset link has been sent to your inbox. Use it to create a new password.</p>

        <router-link :to="{ name: 'login' }">
            <Button>Go to login</Button>
        </router-link>
    </div>

</template>