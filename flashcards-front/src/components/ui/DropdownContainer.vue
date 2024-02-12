<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

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
    <div ref='dropdownButton' @click='isOpen = true'>
        <slot name='dropdown-button'>
            Dropdown
        </slot>
    </div>

    <div class="relative">
        <div class="dropdown-container" v-if="isOpen">
            <slot></slot>
        </div>
    </div>
</div>
</template>

<style>

.dropdown-container {
    position: absolute;
    background-color: rgb( var( --background ) );
    border: 1px solid rgb( var( --shadow ) );
    right: 0;
    border-radius: 5px;
    min-width: 100%;
    z-index: 100;
}
</style>