<script setup>
import { provide, ref } from 'vue'
import { FormDiscovery, FormDeregister } from '../../keys'
import axios from 'axios'
import { MESSAGE_TYPE, useStatusMessageService } from '../../services/StatusMessageService';
import Button from '../ui/Button.vue';

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
    },
    showStatusMessage: {
        type: Boolean,
        default: false,
    },
    clearOnSubmit: {
        type: Boolean,
        default: false,
    },
    submitText: {
        type: String,
        default: 'Submit'
    },
    multipart: {
        type: Boolean,
        default: false,
    },
})
const emit = defineEmits([ 'success' ])

const formErrorMessage = ref( '' )
const isLoading = ref( false )

const statusMessage = useStatusMessageService()

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

    isLoading.value = true;

    let data = {};
    if ( props.multipart ) {
        data = getMultipartFormData();
    }
    else {
        data = getFormData();
    }

    axios({
        url: props.action,
        method: props.method.toLocaleLowerCase(),
        data
    })
    .then( ( response ) => {
        emit( 'success', response.data )

        if ( props.clearOnSubmit ) {
            clearForm();
        }

        if ( props.showStatusMessage ) {
            if ( response.status == 201 ) {
                statusMessage.addStatusMessage( "Item was created successfully", "", MESSAGE_TYPE.SUCCESS );
            }
            else if ( response.status == 200 ) {
                statusMessage.addStatusMessage( "Changes saved.", "Item has been updated succesfully.", MESSAGE_TYPE.SUCCESS );
            }
        }
    })
    .catch( ( error ) => {
        const data = error?.response?.data 
        
        if ( data.message ) {
            // formErrorMessage.value = data.message
            statusMessage.addStatusMessage( 'Action was not successful', data.message )
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
    .finally( () => {
        isLoading.value = false;
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

function createNestedMultipartData( base, names, value ) {
    let key = names[0];
    
    if ( typeof value == "boolean" ) value = value ? 1 : 0;

    for ( let i = 1; i < names.length; i++ ) {
        key += `[${names[i]}]`
    }

    base.append( key, value );
}

function getMultipartFormData() {
    let data = new FormData();

    for ( const name in formItems ) {
        createNestedMultipartData( data, name.split( '.' ), formItems[name].getValue() )
    }

    return data
}

function clearForm() {
    for ( const name in formItems ) {
        formItems[name].setValue( '' ); 
    }
}

</script>

<template>
    <form @submit='onFormSubmit'>

        <slot></slot>

        <button type="submit" v-if="!isLoading">
            <slot name="submit">
                <Button>{{ props.submitText }}</Button>
            </slot>
        </button>
        <div v-else="isLoading">
            Loading...
        </div>

    </form>
</template>