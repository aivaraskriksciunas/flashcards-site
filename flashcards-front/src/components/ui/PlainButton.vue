<script setup>
import { computed } from 'vue';

const emit = defineEmits([ 'click' ])
const props = defineProps({
    type: {
        type: String,
        default: 'secondary'
    },
    selected: { 
        type: Boolean,
        default: false,
    }
})

const buttonStyle = computed(() => {
    switch (props.type) {
        case 'secondary':
            return 'plain-secondary'
        case 'danger':
            return 'plain-danger'
        case 'primary': 
            return 'plain-primary'
        default: 
            return 'plain-secondary'
    }
})

</script>

<template>

<div class="plain-button" :class="[buttonStyle, { selected: props.selected }]" @click="() => emit( 'click' )">
    <slot></slot>
</div>

</template>

<style>

.plain-button {
    padding: 6px 12px;
    cursor: pointer;
    border-radius: 3px;
    user-select: none;
    display: flex;
    align-items: center;
}

.plain-button:hover {
    background-color: var( --color-hover );
}

.plain-danger {
    color: var( --color-danger );
}

.plain-primary {
    color: var( --color-primary );
}

.plain-button.selected {
    border: 2px solid var( --color-primary );
}

.plain-button.selected * {
    color: var( --color-primary );
    fill: var( --color-primary );
}

</style>