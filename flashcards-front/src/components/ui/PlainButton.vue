<script setup>
const emit = defineEmits([ 'click' ])
</script>

<template>

<div class="plain-button" @click="() => emit( 'click' )">
    <slot></slot>
</div>

</template>

<style>

.plain-button {
    padding: 6px 12px;
    cursor: pointer;
    border-radius: 3px;
    user-select: none;
}

.plain-button:hover {
    background-color: var( --color-hover );
}

</style>