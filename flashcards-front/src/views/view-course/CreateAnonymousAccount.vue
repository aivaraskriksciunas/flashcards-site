<script setup>
import { useRoute, useRouter } from 'vue-router';
import AjaxForm from '@/components/forms/AjaxForm.vue';
import TextField from '@/components/forms/TextField.vue';
import { useUserStore } from '@/stores/user';
import useAuthentication from '../auth/composables/useAuthentication';

const route = useRoute()
const router = useRouter()
const { setToken } = useAuthentication()

const onSuccess = ( data ) => {
    setToken( data.token )
    router.push({ name: 'view-course', params: { access_link: route.params.access_link } })
}

</script>

<template>
    <AjaxForm :action="`/api/courses/view/${route.params.access_link}/anonymous`" @success="onSuccess">
        <TextField type="text" name="name">Enter your name:</TextField>
    </AjaxForm>

    <small>
        Have an account? <router-link :to="{ name: 'login' }">Login</router-link> or <router-link :to="{ name: 'account-type' }">create an account</router-link>. 
        <br>
    </small>
</template>