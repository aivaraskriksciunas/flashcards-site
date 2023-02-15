<script setup>
import { RouterView } from 'vue-router'
import UserInfoProviderWrapper from '../../components/wrappers/UserInfoProviderWrapper.vue';
import Navbar from '../../components/common/Navbar.vue';

</script>

<template>
    <UserInfoProviderWrapper>
        <Navbar/>

        <div class="container">
            <RouterView/>
        </div>
    </UserInfoProviderWrapper>
</template>