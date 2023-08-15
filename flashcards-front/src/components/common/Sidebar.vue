<script setup>
import { storeToRefs } from "pinia";
import { useUserSettingStore } from "../../stores/user-settings";
import { useSidebarStore } from "../../stores/sidebar";
import { useUserStore } from "../../stores/user";
import Button from '../../components/ui/Button.vue';
import Logo from './Logo.vue'

const userSettings = useUserSettingStore();
const sidebar = useSidebarStore();
const { user, isLoggedIn } = storeToRefs( useUserStore() )

</script>

<template>
    <div class="sidebar-blocker md:hidden" 
        :class="{ 'hidden': !sidebar.isSidebarVisible }"
        @click="sidebar.hideSidebar"></div>

    <aside class="sidebar md:block p-8" 
        :class="{ 'hidden': !sidebar.isSidebarVisible }"
        @click="sidebar.hideSidebar">

        <div class="sidebar-logo">
            <router-link :to="{name: 'home'}">
                <Logo></Logo>
            </router-link>
        </div>

        <div class="sidebar-profile md:hidden" v-if="isLoggedIn">
            <div>{{ user.name }}</div>
            <router-link :to="{ name: 'logout' }">Log out</router-link>
        </div>
        <div class="sidebar-profile md:hidden" v-else>
            <router-link :to="{ name: 'login' }">Log in</router-link>
            or 
            <router-link :to="{ name: 'register' }">Register</router-link>
        </div>

        <div class="links">
            <router-link :to="{name: 'home'}">
                <div class='sidebar-link'>Home</div>
            </router-link>
            <router-link :to="{name: 'home'}">
                <div class='sidebar-link'>Profile</div>
            </router-link>
            <router-link :to="{name: 'forum'}">
                <div class='sidebar-link'>Forum</div>
            </router-link>
        </div>

        <div class="sidebar-header">
            My library
        </div>

        <Button :to="{name: 'create-deck'}">New deck</Button>

        <div @click="() => userSettings.toggleColorTheme()">Change theme</div>

    </aside>
</template>

<style scoped>
.sidebar {
    background-color: var( --color-white );
    height: 100%;
}

@media (max-width: 768px) {
    .sidebar {
        position: absolute;
        animation: slideshow-animation 0.3s;
    }

    .sidebar-blocker {
        position: absolute;
        animation: slideshow-blocker-animation 0.3s;
        width: 100%;
        height: 100%;
        background-color: rgba( 100, 100, 100, 0.2 );
    }

    @keyframes slideshow-animation {
        0% {
            left: -100%;
        }
        100% {
            left: 0;
        }
    }

    @keyframes slideshow-blocker-animation {
        0% {
            background-color: #ffffff00;
        }
        100% {
            background-color: rgba( 100, 100, 100, 0.2 );
        }
    }
}

.sidebar-profile {
    font-weight: 300;
    margin: 22px 0;
}

.sidebar-logo {
    padding-top: 10px;
    padding-bottom: 16px;
    text-align: center;
    display: flex;
    align-items: center;
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