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
    },
    placeholder: {
        type: String,
        default: '',
    },
    autocomplete: {
        type: String,
        default: 'on',
    },
    note: {
        type: String,
        default: null,
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
            :placeholder="props.placeholder"
            :autocomplete="props.autocomplete"
            @change="onChange">

        <small v-if="props.note">{{ props.note }}</small>

        <FieldErrors :errors="error"></FieldErrors>
    </div>
</template>

<style scoped>

.form-group {
    display: flex;
    flex-direction: column;
    font-size: 1em;
    margin-bottom: 12px;
}

label {
    display: block;
    font-weight: 400;
    margin-bottom: 4px;
}

.form-control {
    border: none;
    border: 1px solid rgb( var( --border ) );
    padding: 0.4em 0.7em;
    border-radius: 4px;
    background-color: rgb( var( --input ) );
}

.form-control:focus {
    background-color: var( --color-bg-form-control-active );
    outline: 1px solid rgb( var( --primary ) );
    box-shadow: 0 2px 6px 2px rgb( var( --shadow ) );
}
</style>