<script setup>
import AjaxForm from '@/components/forms/AjaxForm.vue';
import TextField from '@/components/forms/TextField.vue';
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Header from '@/components/common/Header.vue'
import DataLoaderWrapper from '@/components/wrappers/DataLoaderWrapper.vue';
import CourseLayout from './components/CourseLayout.vue';
import { useCourseStore } from './stores/course-store';
import { storeToRefs } from 'pinia';

const router = useRouter();
const route = useRoute();

const { course } = storeToRefs( useCourseStore() );
const onLoad = ( data ) => { course.value = data }

</script>

<template>
    <DataLoaderWrapper :url="`/api/courses/${route.params.id}`" @load="onLoad">
        <Header>{{ course.title }}</Header>

        <div class="flex">
            <div class="w-64" id="courseLayoutContainer">
                <CourseLayout/>
            </div>

            <div class="w-full pl-4">
                <router-view></router-view>
            </div>
        </div>
    </DataLoaderWrapper>
</template>

<style scoped>
#courseLayoutContainer {
    border-right: 1px solid rgb( var( --muted ) );
}
</style>