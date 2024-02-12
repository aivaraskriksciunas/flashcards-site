<script setup>
import Header from '@/components/common/Header.vue';
import EditPageDialog from './components/EditPageDialog.vue';
import { useCourseStore } from './stores/course-store';
import Card from '@/components/ui/Card.vue';
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-vue-next';
import { storeToRefs } from 'pinia';

const { course } = storeToRefs( useCourseStore() )

</script>

<template>
    <Header level="2">
        Content 

        <template v-slot:actions>
            <EditPageDialog>
                <template v-slot:trigger>
                    <Button>
                        <Plus size="16" class="mr-2"/>
                        Add page
                    </Button>
                </template>
            </EditPageDialog>
        </template>
    </Header>

    <div v-if="course.pages.length == 0">
        The course is currently empty.
    </div>

    <TransitionGroup name="course-page-list">
        <Card v-for="page of course.pages" :key="page.id" class="w-full mb-4" hover>
            <router-link :to="{ name: 'edit-course-page', params: { id: course.id, page_id: page.id }}">
                {{ page.title }}
            </router-link>
        </Card>
    </TransitionGroup>
    

</template>

<style scoped>
.course-page-list-move, /* apply transition to moving elements */
.course-page-list-enter-active,
.course-page-list-leave-active {
  transition: all 0.3s ease;
}

.course-page-list-enter-from,
.course-page-list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}

/* ensure leaving items are taken out of layout flow so that moving
   animations can be calculated correctly. */
.course-page-list-leave-active {
  position: absolute;
}
</style>