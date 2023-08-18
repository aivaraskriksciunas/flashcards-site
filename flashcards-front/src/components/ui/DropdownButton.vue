<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import PlainButton from './PlainButton.vue';

const props = defineProps({
    label: {
        type: String,
    }
});

const isOpen = ref( false );
const dropdownButton = ref( null );

const handleClickOutside = ( event ) => {
    if ( isOpen.value && !dropdownButton.value.contains( event.target ) ) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener( 'click', handleClickOutside );
})

onBeforeUnmount(() => {
    document.removeEventListener( 'click', handleClickOutside ); 
})

</script>

<template>
<div>
    <PlainButton class="dropdown-button" @click="isOpen = true">
        <div class="flex items-center" ref="dropdownButton">
            <div class="flex-1">
                <slot name="label">
                    {{ props.label }}
                </slot>
            </div>

            <font-awesome-icon icon="fas fa-chevron-down"></font-awesome-icon>
        </div>
    </PlainButton>

    <div class="relative">
        <div class="dropdown-container" v-if="isOpen">
            <slot></slot>
        </div>
    </div>
</div>
</template>

<style>
.dropdown-button {
    @apply p-2 px-4;
    border: 1px solid var( --color-shadow );
    position: relative;
    min-width: 160px;
}

.dropdown-container {
    position: absolute;
    background-color: var( --color-white );
    border: 1px solid var( --color-shadow );
    right: 0;
    border-radius: 5px;
    min-width: 100%;
}
</style>