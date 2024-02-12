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
    background-color: rgb( var( --content ) );
    box-shadow: 3px 3px 0 rgb( var( --shadow ) );
}

.card-component-hover:hover {
    @apply bg-card/80;
    box-shadow: none;
    /* background-color: var( --color-hover ); */
}

</style>