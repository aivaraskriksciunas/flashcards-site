<script setup>

const props = defineProps({
    hover: {
        type: Boolean
    }
})
const emit = defineEmits([ 'click' ])

</script>


<template>
    <div class="card-component" :class="{ 'card-component-hover': props.hover }" @click="() => emit( 'click' )">
        <slot></slot>
    </div>
</template>

<style>

.card-component {
    padding: 20px;
    border-radius: 10px;
    background-color: var( --color-white );
    box-shadow: 3px 3px 0 var( --color-shadow );
}

.card-component-hover:hover {
    box-shadow: none;
    background-color: var( --color-shadow );
}

</style>