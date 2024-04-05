<script setup>

const props = defineProps({
    level: {
        default: 1,
    }
})

</script>

<template>
    
<div class="header flex items-center mb-3 flex-wrap">
    <h1 v-if="props.level == 1" class="flex-grow">
        <slot></slot>
    </h1>
    <h2 v-else-if="props.level == 2" class="flex-grow">
        <slot></slot>
    </h2>
    <h3 v-else-if="props.level == 3" class="flex-grow">
        <slot></slot>
    </h3>

    <slot name="actions"></slot>
</div>

</template>

<style>
.header h1,
.header h2,
.header h3 {
    margin-bottom: 0;
}
</style>