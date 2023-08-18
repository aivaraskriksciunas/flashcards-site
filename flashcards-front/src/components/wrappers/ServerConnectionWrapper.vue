<script setup>

import axios from 'axios'
import { ref } from 'vue'

const state = ref( 'loading' )

// Perform a request to server to obtain a csrf cookie
axios.get( '/sanctum/csrf-cookie' )
.catch( () => {
    state.value = 'error'
})
.then( () => {
    state.value = 'loaded'
})

</script>

<template>

<div v-if="state == 'loading'">Loading...</div>    
<div v-else-if="state == 'error'">Error, could not establish a connection to the server</div>
<div v-else-if="state == 'loaded'">
    <slot></slot>
</div>

</template>