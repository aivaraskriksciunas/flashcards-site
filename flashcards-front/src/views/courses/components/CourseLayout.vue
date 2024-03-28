<script setup>
import { onMounted, ref } from 'vue';
import { useCourseStore } from '../stores/course-store';
import { Button } from '@/components/ui/button';
import Header from '@/components/common/Header.vue';
import { storeToRefs } from 'pinia';
import Shade from '@/components/ui/Shade.vue';
// import Sortable from 'sortablejs';
import SortableWrapper from '@/components/wrappers/SortableWrapper.vue';

const store = useCourseStore();
const { course, isLoading } = storeToRefs( store );

const onOrderChange = ( from, to ) => {
    store.movePage( from, to );
    store.persistPageOrder();
}

</script>

<template>
    <Shade :enabled="isLoading">
        <div class="p-3">
        
            <Header level="3" class="mb-0">Layout</Header>

            <SortableWrapper @change="onOrderChange">
                <TransitionGroup name="list" >
                    <div v-for="page of course.pages" :key="page.id" class="draggable-item">
                        <router-link :to="{ name: 'edit-course-page', params: { id: course.id, page_id: page.id } }" class='block'>
                            <Button variant="ghost" class="course-page-link w-full text-left " as="div">
                                {{ page.title }}
                            </Button>
                        </router-link>
                    </div>
                </TransitionGroup>
            </SortableWrapper>
        </div>
    </Shade>

    
</template>

<style scoped>
.course-page-link {
    text-align: left;
    justify-content: flex-start;
    color: rgb( var( --muted-foreground ) );
    cursor: grab;
}

.sortable-chosen {
    border: 1px dashed rgb( var( --border ) );
    opacity: 0.7;
}

.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}

/* ensure leaving items are taken out of layout flow so that moving
   animations can be calculated correctly. */
.list-leave-active {
  position: absolute;
}
</style>