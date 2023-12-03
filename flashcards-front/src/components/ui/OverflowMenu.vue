<script setup>
import PlainIconButton from './PlainIconButton.vue';
import DropdownContainer from './DropdownContainer.vue';

const props = defineProps({
    icon: {
        default: "fa-solid fa-ellipsis-vertical"
    }
});

</script>

<template>
<div>
    <DropdownContainer>
        <template v-slot:dropdown-button>
            <PlainIconButton :icon="props.icon" type='secondary'></PlainIconButton>
        </template>
    
        <slot></slot>
    </DropdownContainer>
</div>
</template>

<style>

</style>