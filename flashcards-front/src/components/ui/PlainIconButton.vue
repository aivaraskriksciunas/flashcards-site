<script setup>

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
    }
})

let iconStyle = 'icon-plain-button-success'
if ( props.type == 'danger' ) {
    iconStyle = 'icon-plain-button-danger'
}
if ( props.type == 'secondary' ) {
    iconStyle = 'icon-plain-button-secondary'
}

</script>

<template>
    <div class="icon-plain-button" :class="[ iconStyle ]">
        <font-awesome-icon class="icon" :icon="props.icon" :size="props.size"></font-awesome-icon>
    </div>
</template>

<style>
.icon-plain-button {
    border-radius: 1000px;
    padding: 0 16px;
    height: 38px;
    width: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.icon-plain-button:hover {
    background-color: var( --color-shadow );
}

.icon-plain-button .icon {
    margin-right: 0px;
}

.icon-button-success .icon path {
    fill: var( --color-primary );
}

.icon-plain-button-danger .icon path {
    fill: var( --color-danger );
}

.icon-plain-button-danger .icon path {
    fill: var( --color-text );
}

.icon-plain-button-success:hover  .icon path {
    fill: var( --color-primary-hover );
}

.icon-plain-button-danger:hover  .icon path {
    fill: var( --color-danger-hover );
}

</style>