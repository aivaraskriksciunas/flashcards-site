<script setup>
import { computed } from 'vue';
import { RouterLink, RouterView } from 'vue-router'
import ServerConnectionWrapper from './components/wrappers/ServerConnectionWrapper.vue';
import { useUserSettingStore } from './stores/user-settings'

const userSettings = useUserSettingStore()
const colorThemeCss = computed( () => 'theme-' + userSettings.colorTheme )

</script>

<template>
  <div :class="[ colorThemeCss ]">
    <ServerConnectionWrapper>
      <RouterView />
    </ServerConnectionWrapper>
  </div>
</template>

<style scoped>
</style>
