<script setup>

const props = defineProps({
    variant: {
        default: 'success'
    },
    filled: {
        default: false,
        type: Boolean
    },
    text: {
        default: '',
        type: String
    }
})

let iconStyle = 'icon-plain-button-success'
if ( props.variant == 'destructive' ) {
    iconStyle = 'icon-plain-button-danger'
}
if ( props.variant == 'secondary' ) {
    iconStyle = 'icon-plain-button-secondary'
}

</script>

<template>
    <div class="icon-plain-button" :class="[ iconStyle ]">
        <slot/>
    </div>
</template>

<style>
.icon-plain-button {
    border-radius: 1000px;
    padding: 0 10px;
    height: 38px;
    width: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.icon-plain-button:hover {
    background-color: rgb( var( --shadow ) );
}

.icon-plain-button .icon {
    margin-right: 0px;
}

.icon-plain-button-danger {
    color: rgb( var( --destructive ) );
}

</style>