<script setup>
import DataLoaderWrapper from '@/components/wrappers/DataLoaderWrapper.vue';
import PageContent from './components/PageContent.vue';
import { useRoute } from 'vue-router';
import { ref } from 'vue';

const route = useRoute()
const page = ref( null )
const onLoad = ( data ) => {
    page.value = data
}

</script>

<template>
<DataLoaderWrapper :url="`/api/courses/${route.params.id}/course_pages/${route.params.page_id}`" @load="onLoad" :key="route.params.page_id">
    <h1 class="text-center">{{ page.title }}</h1>
    
    <PageContent :page="page"/>
</DataLoaderWrapper>

</template>