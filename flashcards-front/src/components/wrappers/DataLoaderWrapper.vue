<script setup>
import axios from 'axios';
import { ref } from 'vue';

const props = defineProps({
    url: {
        type: String
    }
})

const state = ref( 'loading' )
const emit = defineEmits( 'load' )

axios.get( props.url )
.then( ( response ) => {
    state.value = 'loaded'
    emit( 'load', response.data )
})
.catch( ( err ) => {
    state.value = 'error'
})

</script>

<template>
    
<div v-if="state == 'loading'">Loading...</div>
<div v-else-if="state == 'loaded'">
    <slot></slot>
</div>
<div v-else-if="state == 'error'">
    Whoops! An error occurred.
</div>

</template>