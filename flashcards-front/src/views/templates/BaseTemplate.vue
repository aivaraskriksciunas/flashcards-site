<script setup>
import { RouterView } from 'vue-router'
import Navbar from '../../components/common/Navbar.vue';
import Sidebar from '../../components/common/Sidebar.vue';
import StatusMessages from '../../components/common/StatusMessages.vue';
import UnverifiedUserWarning from './components/UnverifiedUserWarning.vue';
import { useUserSettingStore } from '../../stores/user-settings';
import { computed } from 'vue';

const userSettings = useUserSettingStore()
const colorThemeCss = computed( () => 'theme-' + userSettings.colorTheme )

</script>

<template>
    <div :class="[ colorThemeCss ]">
        <UnverifiedUserWarning/>
        <div class="flex application-container">
            <div class="sidebar lg:w-1/3">
                <Sidebar></Sidebar>
            </div>
            <div class="content px-8 py-4">
                <Navbar/>

                <div class="container">
                    <StatusMessages/>
                    <RouterView/>
                </div>
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
    background-color: var( --color-content-bg );
}
</style>