<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();
const props = defineProps({
    label: {
        type: String,
    },
    value: {
        default: null,
    },
    to: {
        default: null,
    }
})

const emit = defineEmits([ 'click' ]);

const onClick = () => {
    if ( props.to ) {
        router.push( props.to );
    }

    emit( 'click', props.value );
}

</script>

<template>
<div class="dropdown-button-item" @click="onClick">
    <slot></slot>
</div>

</template>

<style>
.dropdown-button-item {
    padding: 6px 12px;
    cursor: pointer;
}

.dropdown-button-item:hover {
    background-color: var( --color-hover );
}
</style>