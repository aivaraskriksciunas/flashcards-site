<script setup>
import { ref, onMounted } from 'vue';
import AjaxForm from '@/components/forms/AjaxForm.vue';
import HiddenField from '@/components/forms/HiddenField.vue';
import { useCourseStore } from '../stores/course-store';
import { usePageStore } from '../stores/page-store';
import { storeToRefs } from 'pinia';
import Button from '@/components/ui/button/Button.vue';
import TextField from '@/components/forms/TextField.vue';
import SlimContainer from '@/components/ui/SlimContainer.vue';
import { 
    AlertDialog,
    AlertDialogContent,
    AlertDialogHeader,
    AlertDialogFooter,
    AlertDialogDescription,
    AlertDialogTitle,
    AlertDialogCancel
} from '@/components/ui/alert-dialog';
import { GripVertical } from 'lucide-vue-next';

const courseStore = useCourseStore();
const pageStore = usePageStore();
const { course } = storeToRefs( courseStore )
const { page } = storeToRefs( pageStore )

const props = defineProps({
    item: {
        required: true,
        type: Object,
    },
    hasTitle: {
        required: false,
        type: Boolean,
        default: true,
    }
})

const isEditing = ref( props.item.id == null )
const confirmDeleteModalOpen = ref( false )

const action = ref( null )
const method = ref( null )
onMounted(() => {
    if ( props.item.id ) {
        action.value = `/api/courses/${course.value.id}/course_pages/${page.value.id}/course_page_items/${props.item.id}`
        method.value = 'PATCH'
    }
    else {
        action.value = `/api/courses/${course.value.id}/course_pages/${page.value.id}/course_page_items`
        method.value = 'POST'
    }
})

const onSuccess = ( data ) => {
    pageStore.refreshPage()
    isEditing.value = false
}

const deletePageItem = () => {
    pageStore.deletePageItem( props.item.id )
    confirmDeleteModalOpen.value = false
}

</script>

<template>
    <div class="my-4 p-4" v-if="isEditing">
        <SlimContainer>
            <AjaxForm :action="action" :method="method" @success="onSuccess">
                <HiddenField v-if="!props.hasTitle" name="title" :value="props.item.title || ''">Title: </HiddenField>
                <TextField v-if="props.hasTitle" name="title" :value="props.item.title || ''">Title: </TextField>

                <slot name="edit-content"></slot>

                <template v-slot:actions>
                    <Button variant="secondary" class="ml-2" @click="isEditing = false">Cancel</Button>
                    <div class="flex-grow"></div>
                    <Button 
                        v-if="props.item.id != null" 
                        @click="confirmDeleteModalOpen = true"
                        variant="destructive" 
                        class="ml-2" >
                        Delete
                    </Button>
                </template>
            </AjaxForm>
        </SlimContainer>
    </div>
    <div class='flex' v-if="!isEditing" @click="isEditing = true">
        <div class="page-item-drag-handle text-muted-foreground">
            <GripVertical color="currentColor"/>
        </div>
        <div class="show-page-item py-2">
            <SlimContainer>
                <slot name="show-content"></slot>
            </SlimContainer>
        </div>
    </div>

    <AlertDialog :open="confirmDeleteModalOpen">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Confirm delete</AlertDialogTitle>
                <AlertDialogDescription>Are you sure you want to delete this item?</AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel @click="confirmDeleteModalOpen = false">Cancel</AlertDialogCancel>
                <Button variant="destructive" @click="deletePageItem">
                    Delete
                </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>

<style>
.show-page-item {
    flex-grow: 1;
}
.show-page-item:hover {
    @apply bg-muted;
    cursor: pointer;
}

.page-item-drag-handle {
    flex-shrink: 1;
    display: flex;
    align-items: center;
    cursor: grab;

}

.page-item-drag-handle:hover {
    @apply bg-muted;
}

</style>