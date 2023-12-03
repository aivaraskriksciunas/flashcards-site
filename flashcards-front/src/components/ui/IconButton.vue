<script setup>
import { useRouter } from 'vue-router';

const router = useRouter()
const props = defineProps({
    icon: {
        required: true
    },
    type: {
        default: 'success'
    },
    size: {
        default: 'lg'
    },
    filled: {
        default: false,
        type: Boolean
    },
    text: {
        default: '',
        type: String
    },
    to: {
        default: null,
    }
})

let iconStyle = 'icon-button-success'
if ( props.type == 'danger' ) {
    iconStyle = 'icon-button-danger'
}
else if ( props.type == 'secondary' ) {
    iconStyle = 'icon-button-secondary'
}

const emit = defineEmits( 'click' )
const onClick = () => {
    if ( props.to ) {
        router.push( props.to )
    }

    emit( 'click' )
}

</script>

<template>
    <div class="icon-button" :class="[{ 'filled': props.filled, 'round': props.text == '' }, iconStyle ]" @click="onClick">
        <font-awesome-icon class="icon" :icon="props.icon" :size="props.size"></font-awesome-icon>
        {{ props.text }}
    </div>
</template>

<style>
.icon-button {
    border: 1px solid black;
    border-radius: 1000px;
    padding: 0 16px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.icon-button .icon {
    margin-right: 6px;
}

.icon-button.round {
    width: 38px;
    padding: 0;
}

.icon-button.round .icon {
    margin-right: 0;
}

.icon-button-success {
    border-color: var( --color-primary );
}

.icon-button-danger {
    border-color: var( --color-danger );
}

.icon-button-secondary {
    border: 0;
}

.icon-button-success .icon path {
    fill: var( --color-success );
}

.icon-button-danger .icon path {
    fill: var( --color-danger );
}

.icon-button-secondary .icon path {
    fill: var( --color-text );
}

.icon-button-success:hover {
    background-color: var( --color-primary );
    color: var( --color-white );
}

.icon-button-danger:hover  {
    background-color: var( --color-danger );
    color: var( --color-white );
}

.icon-button-secondary:hover  {
    background-color: var( --color-shadow );
    color: var( --color-white );
}

.icon-button-success:hover  .icon path {
    fill: white;
}

.icon-button-danger:hover  .icon path {
    fill: white;
}

/* Filled styles */
.icon-button-danger.filled {
    background-color: var( --color-danger );
    color: var( --color-white );
}
.icon-button-danger.filled:hover {
    background-color: var( --color-danger-hover );
    border-color: var( --color-danger-hover );
}

.icon-button-success.filled {
    background-color: var( --color-primary );
    color: var( --color-white );
}
.icon-button-success.filled:hover {
    background-color: var( --color-primary-active );
    border-color: var( --color-primary-active );
}

.icon-button-success.filled .icon path,
.icon-button-danger.filled .icon path {
    fill: white;
}
</style>