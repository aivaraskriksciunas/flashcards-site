<script setup>
import { RouterView } from 'vue-router'
</script>


<template>

<div id="auth-page" class="flex">
    <div class="auth-form-container mx-auto">
        <div id="auth-page-logo">
            <img src="/LOGO.png">
        </div>
        <RouterView></RouterView>
    </div>
</div>

</template>

<style>

#auth-page {
    background-color: #393939;
    min-height: 100vh;
    align-items: flex-start;
    padding: 3em 0;
}

#auth-page-logo {
    display: flex;
    justify-content: center;
    margin-bottom: 4em;
}

.auth-form-container {
    background-color: #F5F5F5;
    width: 100%;
    max-width: 500px;
    padding: 3em 4em;
    box-shadow: 8px 8px 0 #252525;
    font-size: 1.1em;
}

.auth-form-container label {
    font-weight: 500;
    display: block;
    margin-bottom: 6px;
    font-size: 1.2em;
}

.auth-form-container input[type=text],
.auth-form-container input[type=email],
.auth-form-container input[type=password] {
    background-color: white;
    font-weight: 400;
    border-radius: 1000px;
    border: none;
    box-shadow: 0 0 6px rgba( 50, 50, 50, 0.1 );
    padding: 0.6em 1.2em;
    width: 100%;
    margin-bottom: 24px;
    border-bottom: 1px solid rgba( 200, 200, 200, 1 );
}



</style>