<script setup>
import { provide, ref } from 'vue'
import { FormDiscovery } from '../../keys'
import axios from 'axios'

/**
 * Init
 */
const props = defineProps({
    method: {
        type: String,
        default: 'POST'
    },
    action: {
        type: String,
    }
})
const emit = defineEmits( 'success' )

const formErrorMessage = ref( '' )

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

    let data = {}
    for ( const name in formItems ) {
        data[name] = formItems[name].getValue()
    }

    if ( props.action == null ) return;

    const availableRequestMethods = [ 'GET', 'POST', 'PUT', 'PATCH', 'DELETE' ]
    if ( !availableRequestMethods.includes( props.method.toUpperCase() ) ) {
        return;
    }

    axios({
        url: props.action,
        method: props.method.toLocaleLowerCase(),
        data
    })
    .then( ( response ) => {
        emit( 'success', response.data )
    })
    .catch( ( error ) => {
        const data = error.response.data 
        
        if ( data.message ) {
            formErrorMessage.value = data.message
        }
        
        if ( data.errors ) {
            for ( const field in formItems ) {
                if ( field in data.errors ) {
                    formItems[field].setError( data.errors[field] )
                }
                else {
                    formItems[field].setError([])
                }
            }
        }
    })
}

</script>

<template>
    <form @submit='onFormSubmit'>
        
        <div v-if="formErrorMessage" class="alert alert-error">
            {{ formErrorMessage }}
        </div>

        <slot></slot>

        <input type='submit'/>

    </form>
</template>