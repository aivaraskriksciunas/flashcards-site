<script setup>
import { watch, ref } from "vue";
import { useFormElement } from "./composables/FormElement";

/**
 * Element Properties
 */
const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    value: {
        default: '',
    }
})

const model = defineModel()
const { data, error } = useFormElement( props.name, model );

if ( props.value ) {
    data.value = props.value
}

</script>

<template>
    <input class='form-control' v-model='data' type='hidden' :name='props.name'>
</template>