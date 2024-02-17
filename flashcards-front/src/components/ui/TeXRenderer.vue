<script setup>

import { ref, onMounted, onUpdated } from 'vue';
import katex from 'katex';
import 'katex/dist/katex.min.css';

const props = defineProps({
    text: {
        required: true,
        type: String,
    },
    highlightErrors: {
        required: false,
        type: Boolean,
        default: false,
    }
})

const renderContainer = ref( null );
const hasError = ref( false )

const render = () => {
    try {
        katex.render( props.text, renderContainer.value, {
            throwOnError: !props.highlightErrors,
            displayMode: true,
            maxSize: 4,
        } )
    }
    catch( e ) {
        hasError.value = true
    }
}

onMounted( render )
onUpdated( render )

</script>

<template>
    <div ref="renderContainer"></div>
    <div v-if="hasError" class="text-destructive">The provided formula contains an error and could not be shown</div>
</template>