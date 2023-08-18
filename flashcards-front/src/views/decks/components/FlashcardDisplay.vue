<script setup>
import Card from '../../../components/ui/Card.vue';
</script>

<template>

<Card>
    <div class="card-content">
        <slot></slot>
    </div>

    <div class="controls">
        <slot name="controls"></slot>
    </div>
</Card>

</template>

<style scoped>
.card-content {
    text-align: center;
    padding-top: 1em;
}
</style>