<script setup>
import DataLoaderWrapper from '@/components/wrappers/DataLoaderWrapper.vue';
import PageContent from './components/PageContent.vue';
import { useRoute } from 'vue-router';
import { ref } from 'vue';
import { useCourseStore } from './stores/course-store';
import Separator from '@/components/ui/separator/Separator.vue';

const route = useRoute()
const store = useCourseStore();

const page = ref( null )
const onLoad = ( data ) => {
    page.value = data
    store.reportProgress( page.value )
}


</script>

<template>
<DataLoaderWrapper 
    :url="`/api/courses/${route.params.id}/course_pages/${route.params.page_id}`" 
    @load="onLoad" 
    :key="route.params.page_id">
    <h1 class="text-center mb-2">{{ page.title }}</h1>
    <Separator class="mb-4"/>
    
    <PageContent :page="page"/>
</DataLoaderWrapper>

</template>