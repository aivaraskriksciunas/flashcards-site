<script setup>
import { provide, ref } from 'vue'
import { FormDiscovery, FormDeregister } from '../../keys'
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
const emit = defineEmits([ 'success' ])

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
const deregisterFormElement = ( name ) => {
    delete formItems[name]
}
provide( FormDiscovery, registerFormElement )
provide( FormDeregister, deregisterFormElement )

/**
 * Methods
 */
function onFormSubmit( ev ) {
    ev.preventDefault();

    if ( props.action == null ) return;

    const availableRequestMethods = [ 'GET', 'POST', 'PUT', 'PATCH', 'DELETE' ]
    if ( !availableRequestMethods.includes( props.method.toUpperCase() ) ) {
        return;
    }

    axios({
        url: props.action,
        method: props.method.toLocaleLowerCase(),
        data: getFormData()
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

function createNestedData( base, names, value ) {
    let lastName = names.pop()

    for ( let i = 0; i < names.length; i++ ) {
        base = base[ names[i] ] = base[ names[i] ] || {}
    }

    base[lastName] = value
}

function getFormData() {
    let data = {}

    for ( const name in formItems ) {
        // Support for complex field names allowing nested content 
        // E.g. name like parent.child=1 will be parsed as { parent: { child: 1 }}
        createNestedData( data, name.split( '.' ), formItems[name].getValue() )
    }

    return data
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