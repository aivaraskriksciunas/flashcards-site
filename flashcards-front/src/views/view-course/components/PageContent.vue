<script setup>
import RichTextContent from '@/components/ui/rich-text-content/RichTextContent.vue';

const props = defineProps({
    page: {
        required: true,
        type: Object
    }
})
</script>

<template>
    <div v-for="item of props.page.items">
        <div >
            <RichTextContent :content="item.content"/>
        </div>
    </div>
</template>