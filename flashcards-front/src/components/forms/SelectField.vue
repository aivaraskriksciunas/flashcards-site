<script setup>
import { useFormElement } from './composables/FormElement'
import FieldErrors from './_FieldErrors.vue'
import {
    Select,
    SelectTrigger,
    SelectValue,
    SelectItem,
    SelectContent,
} from '@/components/ui/select'

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

const onChange = ( newVal ) => {
    data.value = newVal
    emits( 'change', data.value )
}

</script>

<template>
    <div class='form-group'>
        <label class="mb-2 block">
            <slot/>
        </label>
        
        <Select :model-value="data" :name="props.name" @update:model-value="onChange">
            <SelectTrigger>
                <SelectValue></SelectValue>
            </SelectTrigger>
            <SelectContent>
                <SelectItem v-for="choice of props.choices" :value="choice.value" :key="choice.value">{{ choice.label }}</SelectItem>
            </SelectContent>
        </Select>

        <FieldErrors :errors="error"></FieldErrors>
    </div>
</template>

<style>

</style>