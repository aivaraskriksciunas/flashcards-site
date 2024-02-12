<script setup>
import Sortable from 'sortablejs'
import { onMounted, ref } from 'vue';

const props = defineProps({
    ghostClass: {
        type: String,
        default: 'sortable-ghost'
    },
    dragClass: {
        type: String,
        default: 'sortable-drag',
    },
    chosenClass: {
        type: String,
        default: 'sortable-chosen',
    },
    handle: {
        type: String,
        default: null,
    }
})

const wrapperElement = ref( null )
const emit = defineEmits([ 'change' ])

onMounted( () => {
    Sortable.create( wrapperElement.value, {
        draggable: '.draggable-item',
        animation: 150,
	    easing: "cubic-bezier(1, 0, 0, 1)",
        sortableClass: props.sortableClass,
        ghostClass: props.ghostClass,
        chosenClass: props.chosenClass,
        handle: props.handle,
        onUpdate: ( e ) => {
            let from = e.oldIndex;
            let to = e.newIndex;

            emit( 'change', from, to );
        }
    } )
})

</script>

<template>

<div ref="wrapperElement">
    <slot></slot>
</div>

</template>