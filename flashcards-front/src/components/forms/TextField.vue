<script setup>
import { useFormElement } from './composables/FormElement'

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

        <input class='form-control' v-model='data' type='{{ props.type }}' name='{{ props.name }}'>
    </div>
</template>