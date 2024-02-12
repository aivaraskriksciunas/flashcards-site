<script setup>
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-vue-next';
import AjaxForm from '@/components/forms/AjaxForm.vue';
import TextField from '@/components/forms/TextField.vue';
import HiddenField from '@/components/forms/HiddenField.vue';
import { useRouter } from 'vue-router';
import { useCourseStore } from '../stores/course-store';
import { ref } from 'vue';
import { storeToRefs } from 'pinia';

const router = useRouter();
const modalOpen = ref( false );

const props = defineProps({
    page: {
        default: null
    }
})

const emit = defineEmits([ 'updated' ])
const store = useCourseStore()
const { course } = storeToRefs( store )

const onSuccess = ( data ) => {
    // Check if updating or creating page
    if ( props.page ) {
        emit( 'updated', data )
        store.refreshCourse()
        modalOpen.value = false
    }
    else {
        store.addCoursePage( data )
        modalOpen.value = false
        
        router.push({ 
            name: 'edit-course-page', 
            params: { id: course.value.id, page_id: data.id } 
        });
    }
}

const url = props.page ? 
    `/api/courses/${course.value.id}/course_pages/${props.page.id}` :
    `/api/courses/${course.value.id}/course_pages`;

const method = props.page ? 'PATCH' : 'POST';

</script>

<template>
    <Dialog v-model:open="modalOpen">
        <DialogTrigger>
            <slot name="trigger">
                Open
            </slot>
        </DialogTrigger>
        <DialogContent>
            <AjaxForm :action="url" :method="method" @success="onSuccess">
                <DialogHeader>
                    <DialogTitle v-if="props.page">
                        Edit course page
                    </DialogTitle>
                    <DialogTitle v-else>
                        Add course page
                    </DialogTitle>

                    <DialogDescription>
                        <TextField name="title" :value="props.page ? props.page.title : ''">Title</TextField>
                        <HiddenField name="type" :value="props.page ? props.page.type : 'page'"></HiddenField>
                    </DialogDescription>
                </DialogHeader>

                <template v-slot:submit/>
            </AjaxForm>

        </DialogContent>
    </Dialog>
</template>