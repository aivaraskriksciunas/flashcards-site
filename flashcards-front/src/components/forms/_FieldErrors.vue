<script setup>

/**
 * Element Properties
 */
const props = defineProps({
    errors: {
        type: Array,
        default: []
    },
})

</script>

<template>
    
    <div v-if="props.errors" class="field-errors">
        <div v-for="( err, index ) in props.errors" :key="index" class="error">
            {{ err }}
        </div>
    </div>

</template>