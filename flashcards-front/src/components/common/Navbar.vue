<script setup>
import { useUserStore } from '../../stores/user';

const { user } = useUserStore()

</script>

<template>
    
<nav class="main-navbar">
    <div class="container flex py-4">
        <div class="navbar-logo flex-grow">
            Preview version
        </div>

        <div class="">
            {{ user.name }}
            <router-link :to="{ name: 'logout' }">Log out</router-link>
        </div>
    </div>
</nav>

</template>