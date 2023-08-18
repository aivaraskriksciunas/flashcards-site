<script setup>
import { computed } from 'vue';
import { useUserSettingStore } from "../../stores/user-settings";

const userSettings = useUserSettingStore();
const logo = computed( () => {
    if ( userSettings.colorTheme == 'dark' ) {
        return "/LOGODARK.png";
    }

    return "/LOGO.png";
})

</script>

<template>
    <img :src="logo">
</template>