<script setup>
import { computed } from 'vue'
 
const props = defineProps({
    type: {
        type: String,
        default: 'secondary'
    }
})

const buttonStyle = computed(() => {
    switch (props.type) {
        case 'secondary':
            return 'outline-secondary'
        case 'danger':
            return 'outline-danger'
        case 'primary': 
            return 'outline-primary'
        default: 
            return 'outline-secondary'
    }
})

</script>

<template>

    <div class="outline-button" :class="[buttonStyle]">
        <slot></slot>
    </div>

</template>

<style>

.outline-button {
    padding: 8px 16px;
    border: 1px solid black;
    border-radius: 4px;
    text-align: center;
    cursor: pointer;
    user-select: none;
    font-weight: 500;
    border-radius: 1000px;
}

.outline-secondary {
    border-color: rgb( var( --foreground ) );
    color: rgb( var( --foreground ) );
    border-width: 1px;
}

.outline-secondary:hover {
    background-color: rgb( var( --muted-foreground ) );
    color: rgb( var( --background ) );
}

.outline-primary {
    border-color: rgb( var( --primary ) );
}

.outline-primary:hover {
    background-color: rgb( var( --primary ) );
}

.outline-danger {
    border-color: var( --color-danger );
}

.outline-danger:hover {
    background-color: var( --color-danger );
}

</style>