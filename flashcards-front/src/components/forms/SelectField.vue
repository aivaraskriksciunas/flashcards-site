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
    choices: {
        required: true,
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

        <select 
            :name="props.name"
            v-model="data"
            @change="onChange"
            class="select-control">
            <option v-for="choice of props.choices" :value="choice.value" :key="choice.value">{{ choice.label }}</option>    
        </select>

        <FieldErrors :errors="error"></FieldErrors>
    </div>
</template>

<style>

.select-control {
    @apply px-2 py-2;
    border: 1px solid var( --color-accent );
    background-color: var( --color-content-bg );
    border-bottom-width: 2px;
    border-radius: 6px;
}

.select-control:hover {
    cursor: pointer;
    background-color: var( --color-hover );
}

</style>