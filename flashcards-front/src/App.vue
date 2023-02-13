<script setup>
import { RouterLink, RouterView } from 'vue-router'
import ServerConnectionWrapper from './components/wrappers/ServerConnectionWrapper.vue';
</script>

<template>
  <ServerConnectionWrapper>
    <RouterView />
  </ServerConnectionWrapper>
</template>

<style scoped>
</style>
