<script setup>
import { provide } from 'vue'
import { FormDiscovery } from '../../keys'

/**
 * Init
 */
const props = defineProps({
    
})
defineEmits( 'submit' )

/**
 * 
 */
// List of all discovered for elements in this form
const formItems = {}

// Interface for nested form items to register themselves
const registerFormElement = ( name, elementControl ) => {
    formItems[name] = elementControl
}
provide( FormDiscovery, registerFormElement )

/**
 * Methods
 */
function onFormSubmit( ev ) {

    ev.preventDefault();
}

</script>

<template>
    <form @submit='onFormSubmit'>
        
        <slot></slot>

        <input type='submit'/>

    </form>
</template>