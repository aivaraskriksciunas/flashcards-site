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
    },
    autogrow: {
        type: Boolean,
        default: false,
    },
    autogrowMax: {
        type: Number,
        default: 200,
    }
})

const emits = defineEmits([ 'change' ])

const model = defineModel()
const { data, error } = useFormElement( props.name, model )

if ( props.value ) {
    data.value = props.value
}

const onChange = ( ev ) => {
    emits( 'change', data.value )
}

const autogrow = ( ev ) => {
    if ( !props.autogrow ) return;

    ev.target.style.height = 'auto';
    ev.target.style.height = Math.min( props.autogrowMax, ev.target.scrollHeight ) + 'px';
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
            @change="onChange"
            @input="autogrow">
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