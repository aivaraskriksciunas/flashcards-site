<script setup>
import PlainButton from '../../../components/ui/PlainButton.vue';

const props = defineProps({

})

const emit = defineEmits([ 'onPrev', 'onNext' ])
</script>

<template>
<div class="controls">
    <PlainButton @click="emit( 'onPrev' )">
        Prev
    </PlainButton>
    <div id="card-count">
        <slot>
        </slot>
    </div>
    <PlainButton @click="emit( 'onNext' )">
        Next
    </PlainButton>
</div>
</template>

<style scoped>
.controls {
    margin-top: 1em;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
</style>