<script setup>
import { useFormElement } from './composables/FormElement'
import FieldErrors from './_FieldErrors.vue'

/**
 * Element Properties
 */
const props = defineProps({
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
        default: ''
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

        <textarea class='form-control' 
            v-model='data'
            :name='props.name'
            :placeholder="props.placeholder"
            @change="onChange">
        </textarea>

        <FieldErrors :errors="error"></FieldErrors>
    </div>
</template>

<style scoped>

.form-group {
    display: flex;
    flex-direction: column;
    font-size: 1.1em;
}

label {
    display: block;
    font-weight: 400;
    margin-bottom: 4px;
    font-size: 0.9em;
}

.form-control {
    border: 1px solid rgb( var( --border ) );
    padding: 0.4em 0.7em;
    border-radius: 4px;
    margin-bottom: 12px;
    background-color: rgb( var( --background ) );
}

.form-control:focus {
    background-color: var( --color-bg-form-control-active );
    outline: 1px solid rgb( var( --primary ) );
    box-shadow: 0 2px 6px 2px rgb( var( --shadow ) );
}
</style>