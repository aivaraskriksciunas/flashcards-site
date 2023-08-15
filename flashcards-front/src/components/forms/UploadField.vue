<script setup>
import { useFormElement } from "./composables/FormElement";
import FieldErrors from "./_FieldErrors.vue";

/**
 * Element Properties
 */
 const props = defineProps({
    name: {
        type: String,
        required: true,
    },
})

const { data, error } = useFormElement( props.name );

const onFileChange = ( ev ) => {
    if ( ev.target.files[0] == null ) return;

    data.value = ev.target.files[0]
}

</script>

<template>
    <input class='form-control' @change="onFileChange" type='file' :name='props.name'>
    <FieldErrors :errors="error"></FieldErrors>
</template>