<script setup>
import { RouterView } from 'vue-router'
import UserInfoProviderWrapper from '../../components/wrappers/UserInfoProviderWrapper.vue';
import Navbar from '../../components/common/Navbar.vue';
import Sidebar from '../../components/common/Sidebar.vue';

</script>

<template>
    <UserInfoProviderWrapper>

        <div class="flex application-container">
            <div class="sidebar md:w-1/3">
                <Sidebar></Sidebar>
            </div>
            <div class="content">
                <Navbar/>

                <div class="container">
                    <RouterView/>
                </div>
            </div>
        </div>
        
    </UserInfoProviderWrapper>
</template>

<style scoped>
.application-container {
    min-height: 100vh;
}

.sidebar {
    max-width: 250px; 
}

.content {
    flex-grow: 1;
    background-color: var( --color-content-bg );
}
</style>