<script setup>
import axios from 'axios';
import { ref } from 'vue';
import NumberPaginator from '../pagination/NumberPaginator.vue';

const props = defineProps({
    url: {
        type: String
    },
    queryParams: {
        type: Object,
        default: {},
    }
})

const state = ref( 'loading' )
const emit = defineEmits([ 'load' ])
const pagination = ref( false )

const loadData = ( url ) => {
    state.value = 'loading'
    
    axios.get( url, { params: props.queryParams } )
    .then( ( response ) => {
        state.value = 'loaded'
        pagination.value = response.data.meta
        emit( 'load', response.data )
    })
    .catch( ( err ) => {
        state.value = 'error'
    })
}

loadData( props.url )

</script>

<template>
    
<div v-if="state == 'loading'">Loading...</div>
<div v-else-if="state == 'loaded'">
    <slot></slot>
    
    <NumberPaginator v-if="pagination" :pagination="pagination" @page-change="loadData"></NumberPaginator>
</div>
<div v-else-if="state == 'error'">
    Whoops! An error occurred.
</div>

</template>