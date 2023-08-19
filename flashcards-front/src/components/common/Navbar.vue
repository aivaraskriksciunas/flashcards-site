<script setup>
import { useUserStore } from '../../stores/user';
import Logo from './Logo.vue';
import HamburgerIcon from '../icons/HamburgerIcon.vue';
import { useSidebarStore } from '../../stores/sidebar';
import { storeToRefs } from 'pinia';

const { user, isLoggedIn } = storeToRefs( useUserStore() )
const { showSidebar } = useSidebarStore()

</script>

<template>
    
<nav class="main-navbar hidden lg:flex container py-4">
    <div class="navbar-logo flex-grow">
        Preview version v0.5
    </div>

    <div class="">
        <div v-if="isLoggedIn">
            {{ user.name }}
            <router-link :to="{ name: 'logout' }">Log out</router-link>
        </div>
        <div v-else>
            <router-link :to="{ name: 'login' }">Log in</router-link>
            or 
            <router-link :to="{ name: 'register' }">Register</router-link>
        </div>
    </div>
</nav>

<nav class="mobile-navbar flex lg:hidden container py-4">
    <HamburgerIcon class="sidebar-expand" @click="showSidebar"></HamburgerIcon>
    <div class="navbar-logo">
        <Logo></Logo>
    </div>
</nav>

</template>

<style>
.mobile-navbar {
    align-items: center;
}

.navbar-logo {
    max-height: 100%;
}

.sidebar-expand {
    height: 32px;
    margin-right: 16px;
}
</style>