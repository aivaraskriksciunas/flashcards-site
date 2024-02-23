<script setup>
import { useRouter } from 'vue-router'
import StudentRegisterForm from './components/StudentRegisterForm.vue';
import OrganizationRegisterForm from './components/OrganizationRegisterForm.vue';
import PlainButton from '../../components/ui/PlainButton.vue';
import { ref } from 'vue';
import useAuthentication from './composables/useAuthentication';
import { UserRound, Building2 } from 'lucide-vue-next';

const router = useRouter()
const accountType = ref( 'student' )
const { setToken } = useAuthentication()

function onRegister( data ) {
    if ( data.token ) {
        setToken( data.token )
    }
    
    if ( accountType.value == 'organization' ) {
        router.push({ name: 'register-org' })
    }
    else {
        router.push({ name: 'home' })
    }
}

</script>

<template>

    <div>
        Register as:
    </div>
    <div class="mb-3">
        <PlainButton @click="accountType = 'student'" :selected="accountType == 'student'" class="mb-2">
            <div class="flex account-type-btn">
                <div class="account-type-btn__icon">
                    <UserRound/>
                </div>
                <div class="account-type-btn__text">
                    Student
                </div>
            </div>
        </PlainButton>
        
        <PlainButton @click="accountType = 'organization'" :selected="accountType == 'organization'">
            <div class="flex account-type-btn">
                <div class="account-type-btn__icon">
                    <Building2 />
                </div>
                <div class="account-type-btn__text">
                    Organization
                </div>
            </div>
        </PlainButton>
    </div>

    <div v-if="accountType == 'student'">
        <StudentRegisterForm :on-register="onRegister"></StudentRegisterForm>
    </div>
    <div v-else-if="accountType == 'organization'">
        <OrganizationRegisterForm :on-register="onRegister"></OrganizationRegisterForm>
    </div>

    <small>Already have an account? <router-link :to="{ name: 'login' }">Log in</router-link></small>

</template>

<style scoped>

.account-type-btn__icon {
    @apply p-2 mr-2;
}

.account-type-btn__text {
    display: flex;
    align-items: center;
}

</style>