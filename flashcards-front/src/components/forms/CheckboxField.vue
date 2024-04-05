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
    value: {
        type: Boolean,
        default: false,
    }
})

const { data, error } = useFormElement( props.name );

data.value = props.value ? true : false;

</script>

<template>
    <div class="checkbox-input">
        <input class='form-control' v-model="data" type='checkbox' :name='props.name' :id="`${props.name}-checkbox`">
        <label :for="`${props.name}-checkbox`"><slot></slot></label>
    </div>
    <FieldErrors :errors="error"></FieldErrors>
</template>

<style>
.checkbox-input {
    display: flex;
    align-items: center;
    margin: 8px 0;
}

.checkbox-input input[type=checkbox] {
    margin: 0 8px 0 0;
}

.checkbox-input label {
    font-weight: 400;
    margin: 0;
}

</style>