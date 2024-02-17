<script setup>
import TextareaField from '@/components/forms/TextareaField.vue';
import TeXRenderer from '@/components/ui/TeXRenderer.vue';
import { ref, onMounted, watch } from 'vue';

const model = defineModel()
const enteredValue = ref( model.value )

const renderFormula = () => {
    if ( typeof enteredValue.value != 'string' ) {
        enteredValue.value = '';
    }

    model.value = enteredValue.value
}

onMounted(() => {
    if ( enteredValue.value != '' ) {
        renderFormula()
    }
})

watch( enteredValue, renderFormula )

</script>

<template>
    <div ref="drawArea" class="formula-draw-area">
        <TeXRenderer :text="model" highlight-errors/>
    </div>
    <TextareaField v-model="enteredValue" placeholder="Type your TeX expression here"/>
    <small class="text-muted-foreground">You may find a list of supported functions <a href="https://katex.org/docs/supported" target="_blank">here</a>.</small>
</template>

<style>
.formula-draw-area {
    @apply p-2 bg-background my-2;
    border: 1px dashed rgb( var( --border ) );
    font-size: 1.1em;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-height: 80px;
}

</style>