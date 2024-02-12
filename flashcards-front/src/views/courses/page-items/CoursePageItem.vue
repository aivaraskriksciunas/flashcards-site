<script setup>
import { COURSE_PAGE_TYPE } from '../stores/page-store';
import ParagraphItem from './ParagraphItem.vue';

const props = defineProps({
    item: {
        type: Object,
        required: true,
    }
})

const determineItemComponent = () => {
    if ( props.item == null ) return;
    
    if ( props.item.type === COURSE_PAGE_TYPE.paragraph ) {
        return ParagraphItem;
    }

    return ParagraphItem
}

</script>

<template>
    <component :is="determineItemComponent()" :item="item"></component>
</template>

<style>

</style>