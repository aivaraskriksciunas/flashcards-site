<script setup>
import TextareaField from '@/components/forms/TextareaField.vue';

const props = defineProps({
    placeholder: {
        type: String,
    }
})

const model = defineModel()

</script>

<template>
    <TextareaField 
        class="deck-item-control" 
        name=""
        :value="model"
        @change="val => model = val" 
        :placeholder="props.placeholder">
    </TextareaField>
</template>