<script setup>
import Header from '@/components/common/Header.vue';
import { Button } from '@/components/ui/button';
import EditPageDialog from './components/EditPageDialog.vue';
import { Pencil } from 'lucide-vue-next';
import DataLoaderWrapper from '@/components/wrappers/DataLoaderWrapper.vue';
import { useRoute } from 'vue-router';
import Card from '@/components/ui/Card.vue';
import { storeToRefs } from 'pinia';
import { useCourseStore } from './stores/course-store';
import { usePageStore } from './stores/page-store';
import AddPageItemButton from './components/AddPageItemButton.vue';
import CoursePageItem from './page-items/CoursePageItem.vue';
import Shade from '@/components/ui/Shade.vue';
import SortableWrapper from '@/components/wrappers/SortableWrapper.vue';

const { course } = storeToRefs( useCourseStore() )
const pageStore = usePageStore()
const { page, isLoading } = storeToRefs( pageStore )

const route = useRoute()

const onUpdated = ( data ) => { pageStore.setPage( data ) }
const onReorder = ( from, to ) => {
    pageStore.movePageItem( from, to );
    pageStore.persistPageItemOrder();
}

</script>

<template>
    <DataLoaderWrapper 
        :url="`/api/courses/${ course.id }/course_pages/${ route.params.page_id }`" 
        @load="onUpdated"
        :key="route.params.page_id">

        <Shade :enabled="isLoading">
            <router-link :to="{ name: 'edit-course', params: { id: course.id }}">{{ course.title }}</router-link> / {{ page.title }}
            <Header level="2">
                Page &quot;{{ page.title }}&quot;

                <template v-slot:actions>
                    <EditPageDialog :page="page" @updated="onUpdated">
                        <template v-slot:trigger>
                            <Button variant="outline" class="mr-4">
                                <Pencil size="16" class="mr-2"/>
                                Modify
                            </Button>
                        </template>
                    </EditPageDialog>

                    <AddPageItemButton :page="page"/>
                </template>
            </Header>
            
            <Card>
                <SortableWrapper 
                    @change="onReorder"
                    drag-class="page-item-drag"
                    chosen-class="page-item-chosen"
                    ghost-class="page-item-ghost"
                    handle=".page-item-drag-handle">
                    <div v-for="item of page.items" :key="item.id || item._timestamp" class="draggable-item">
                        <CoursePageItem :item="item"></CoursePageItem>
                    </div>
                </SortableWrapper>
            </Card>
        </Shade>
       
    </DataLoaderWrapper>
</template>

<style>
.page-item-drag {
    max-height: 100px;
    background-color: blue;
}

.page-item-chosen {
    border: 2px dashed rgb( var( --border ) );
    max-height: 300px;
    overflow: hidden;
    position: relative;
}

.page-item-chosen:after {
    width: 100%;
    height: 20%;
    background-color: red;
    content: "";
    z-index: 20;
    bottom: 0;
    left: 0;
    position: absolute;
    background: linear-gradient( to bottom, rgb( var( --card ) / 0% ), rgb( var( --card ) ) );
}

.page-item-ghost {
    opacity: 0.4;
}
</style>