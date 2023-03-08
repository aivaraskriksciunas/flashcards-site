<script setup>
import { computed } from 'vue';
import { useUserSettingStore } from "../../stores/user-settings";
import Button from '../../components/ui/Button.vue';

const userSettings = useUserSettingStore();
const logo = computed( () => {
    if ( userSettings.colorTheme == 'dark' ) {
        return "/LOGODARK.png";
    }

    return "/LOGO.png";
})

</script>

<template>
    <div class="sidebar">
        <router-link :to="{name: 'home'}">
            <div class="sidebar-logo">
                <img :src="logo">
            </div>
        </router-link>

        <div class="px-8">
            <div class="links">
                <router-link :to="{name: 'home'}">
                    <div class='sidebar-link'>Home</div>
                </router-link>
                <router-link :to="{name: 'home'}">
                    <div class='sidebar-link'>Profile</div>
                </router-link>
                <router-link :to="{name: 'home'}">
                    <div class='sidebar-link'>Forum</div>
                </router-link>
            </div>

            <div class="sidebar-header">
                My library
            </div>

            <Button :to="{name: 'create-deck'}">New deck</Button>

            <div @click="() => userSettings.toggleColorTheme()">Change theme</div>
        </div>
    </div>
</template>

<style scoped>
.sidebar {
    background-color: var( --color-white );
    height: 100%;
}

.sidebar-logo {
    @apply p-8;
    min-height: 80px;
    text-align: center;
}

.sidebar-logo img {
    max-height: 25px;
    margin: 0 auto;
}

.sidebar-link {
    font-weight: 500;
    font-size: 18px;
    margin: 15px 0;
}

.links {
    margin-bottom: 40px;
}

.sidebar-header {
    margin-bottom: 15px;
    font-size: 20px;
    font-weight: 500;
}
</style>