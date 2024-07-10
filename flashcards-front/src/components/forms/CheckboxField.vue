<script setup>
import { useFormElement } from "./composables/FormElement";
import FieldErrors from "./_FieldErrors.vue";
import { Checkbox } from "../ui/checkbox";

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

const model = defineModel()
const { data, error } = useFormElement( props.name, model );

if ( model.value == null ) {
    data.value = props.value ? true : false
}

</script>

<template>
    <div class="checkbox-input">
        <Checkbox :checked="data" @update:checked="v => data = v" :id="`${props.name}-checkbox`" class="mr-2"/>
        <label :for="`${props.name}-checkbox`"><slot></slot></label>
    </div>
    <FieldErrors :errors="error"></FieldErrors>
</template>

<style>
.checkbox-input {
    display: flex;
    align-items: center;
    margin: 16px 0;
}

.checkbox-input input[type=checkbox] {
    margin: 0 8px 0 0;
}

.checkbox-input label {
    font-weight: 400;
    margin: 0;
}

</style>