<script setup>
import AccountDropdown from '@/components/common/AccountDropdown.vue';
import Logo from '@/components/common/Logo.vue';
import { useUserStore } from '@/stores/user';
import { storeToRefs } from 'pinia';

const { user, isLoggedIn } = storeToRefs( useUserStore() )

</script>

<template>

<nav class="course-navbar p-6">
    <div class="container flex items-center">
        <div class="navbar-logo flex-grow">
            <router-link to="/"><Logo/></router-link>
        </div>

        <div class="">
            <div v-if="isLoggedIn" class="flex items-center">
                <AccountDropdown/>
            </div>
            <div v-else>
                <router-link :to="{ name: 'login' }">Log in</router-link>
                or 
                <router-link :to="{ name: 'register' }">Register</router-link>
            </div>
        </div>
    </div>
</nav>

</template>

<style>
.course-navbar {
    background-color: rgb( var( --page-background ) );
}
</style>