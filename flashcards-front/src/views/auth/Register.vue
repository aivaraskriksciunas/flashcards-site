<script setup>
import { useRoute, useRouter } from 'vue-router'
import StudentRegisterForm from './components/StudentRegisterForm.vue';
import OrganizationRegisterForm from './components/OrganizationRegisterForm.vue';
import { ref } from 'vue';
import useAuthentication from './composables/useAuthentication';

const router = useRouter()
const route = useRoute()
const accountType = ref( 'student' )
const { setToken } = useAuthentication()

if ( route.query["as"] == 'teacher' ) {
    accountType.value = 'teacher'
}
else if ( route.query["as"] == "orgadmin" ) {
    accountType.value = 'orgadmin'
}

function onRegister( data ) {
    if ( data.token ) {
        setToken( data.token )
    }
    
    if ( accountType.value == 'orgadmin' ) {
        router.push({ name: 'register-org' })
    }
    else {
        router.push({ name: 'home' })
    }
}

</script>

<template>

    <div v-if="accountType == 'student'">
        <StudentRegisterForm :on-register="onRegister"></StudentRegisterForm>
    </div>
    <div v-else-if="accountType == 'orgadmin'">
        <OrganizationRegisterForm :on-register="onRegister"></OrganizationRegisterForm>
    </div>
    <!-- <div v-else-if="accountType == 'teacher'">
        <OrganizationRegisterForm :on-register="onRegister"></OrganizationRegisterForm>
    </div> -->

    <small>Already have an account? <router-link :to="{ name: 'login' }">Log in</router-link></small>

</template>

