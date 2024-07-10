<template>
    <div class="loader-container">
        Loading...
        <span class="loader"></span>
    </div>
</template>

<style>
.loader-container {
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 300;
}

.loader {
    width: 24px;
    height: 24px;
    border: 5px solid rgb( var( --primary ) );
    border-bottom-color: transparent;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
    margin-left: 12px;
}

@keyframes rotation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
} 
</style>