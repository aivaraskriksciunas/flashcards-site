<script setup>
import { useFormElement } from './composables/FormElement'
import FieldErrors from './_FieldErrors.vue'

/**
 * Element Properties
 */
const props = defineProps({
    type: {
        type: String,
        default: 'text'
    },
    name: {
        type: String,
        required: true,
    },
    value: {
        type: String,
        default: '',
    }
})

const emits = defineEmits([ 'change' ])

const { data, error } = useFormElement( props.name )

if ( props.value ) {
    data.value = props.value
}

const onChange = ( ev ) => {
    emits( 'change', data.value )
}

</script>

<template>
    <div class='form-group'>
        <label>
            <slot/>
        </label>

        <input class='form-control' 
            v-model='data' 
            :type='props.type' 
            :name='props.name'
            @change="onChange">

        <FieldErrors :errors="error"></FieldErrors>
    </div>
</template>