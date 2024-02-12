<script setup>
import RichTextField from '@/components/forms/RichTextField.vue';
import CoursePageItemForm from './CoursePageItemForm.vue';
import RichTextContent from '@/components/ui/rich-text-content/RichTextContent.vue';

const props = defineProps({
    item: {
        type: Object,
    }
})

</script>

<template>
    <CoursePageItemForm :item="props.item" :has-title="false">
        <template v-slot:edit-content>
            <RichTextField name="content" :value="props.item.content"></RichTextField>
        </template>
        <template v-slot:show-content>
            <RichTextContent :content="props.item.content"/>
        </template>
    </CoursePageItemForm>
</template>