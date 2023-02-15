<script setup>
import { useUserStore } from '../../stores/user';

const { user } = useUserStore()

</script>

<template>
    
<nav class="main-navbar border-b">
    <div class="container flex py-4">
        <div class="navbar-logo flex-grow">
            Flashcards
        </div>

        <div class="">
            {{ user.name }}
        </div>
    </div>
</nav>

</template>