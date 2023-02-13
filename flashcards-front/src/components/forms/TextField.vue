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
})

const { data, error } = useFormElement( props.name )

</script>

<template>
    <div class='form-group'>
        <label>
            <slot/>
        </label>

        <input class='form-control' v-model='data' :type='props.type' :name='props.name'>

        <FieldErrors :errors="error"></FieldErrors>
    </div>
</template>