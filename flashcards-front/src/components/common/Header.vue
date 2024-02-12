<script setup>

const props = defineProps({
    level: {
        type: Number,
        default: 1,
    }
})

</script>

<template>
    
<div class="header lg:flex items-center mb-3">
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