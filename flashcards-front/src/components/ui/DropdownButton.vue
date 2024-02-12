<script setup>
import PlainButton from './PlainButton.vue';
import DropdownContainer from './DropdownContainer.vue';

const props = defineProps({
    label: {
        type: String,
    }
});

</script>

<template>
<div>
    <DropdownContainer>
        <template v-slot:dropdown-button>
            <PlainButton class="dropdown-button">
                <div class="flex-1 flex items-center">
                    <div class="flex-1">
                        <slot name="label">
                            {{ props.label }}
                        </slot>
                    </div>

                    <font-awesome-icon icon="fas fa-chevron-down"></font-awesome-icon>
                </div>
            </PlainButton>
        </template>
    
        <slot></slot>
    </DropdownContainer>
</div>
</template>

<style>
.dropdown-button {
    @apply p-2 px-4;
    border: 1px solid rgb( var( --border ) );
    position: relative;
    min-width: 160px;
}
</style>