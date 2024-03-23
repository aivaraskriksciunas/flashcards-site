<script setup>
import ThemeSwitcher from '@/components/common/ThemeSwitcher.vue';
</script>

<template>

<footer class="course-footer p-6">
    <div class="container flex items-center">
        <div class="mr-2 flex-grow">
            AktuLibre © 2024
        </div>
        <div>
            <ThemeSwitcher/>
        </div>
    </div>
</footer>
    
</template>

<style>
.course-footer {
    background-color: rgb( var( --page-background ) );    
}
</style>