<script setup>
import { RouterView } from 'vue-router'
import Navbar from '../../components/common/Navbar.vue';
import Sidebar from '../../components/common/Sidebar.vue';
import UnverifiedUserWarning from './components/UnverifiedUserWarning.vue';

</script>

<template>
    <UnverifiedUserWarning/>
    <div class="flex application-container">
        <div class="sidebar lg:w-1/3">
            <Sidebar></Sidebar>
        </div>
        <div class="content py-4">
            <Navbar/>

            <div class="container">
                <RouterView/>
            </div>
        </div>
    </div>
        
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
    background-color: rgb( var( --page-background ) );
}
</style>