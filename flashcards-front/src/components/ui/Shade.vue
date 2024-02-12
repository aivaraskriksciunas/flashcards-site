<script setup>

const props = defineProps({
    enabled: {
        default: false,
        type: Boolean,
    }
})

</script>

<template>

    <div id="shadeParent" :class="{ 'shade-enabled': props.enabled }">
        <div v-if="props.enabled" id="shade"></div>
        <slot></slot>
    </div>

</template>

<style>
#shadeParent {
    position: relative;
}

.shade-enabled {
    opacity: 0.4;
}

#shade {
    position: absolute;
    width: 100%;
    height: 100%;
}

</style>