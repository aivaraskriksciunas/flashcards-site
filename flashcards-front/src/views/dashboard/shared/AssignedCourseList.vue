<script setup>
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/button/Button.vue';
import { Play } from 'lucide-vue-next';
import Header from '@/components/common/Header.vue';
import DataLoaderWrapper from '@/components/wrappers/DataLoaderWrapper.vue';
import { ref } from 'vue';

const courses = ref([])

</script>

<template>
    
    <DataLoaderWrapper url="/api/my-courses/assigned" @load="data => courses = data">
        <template #loading>&nbsp;</template>

        <div v-if="courses.length">
            <Header :level="3" class="section-header">Your assignments</Header>
            <Card class="mb-12" >
                <div v-for="course of courses" :key="course.link" class="course-list-item md:flex">
                    <div>
                        <router-link :to="{ name: 'view-course', params: { access_link: course.link } }">
                            <Button variant="default" size="icon">
                                <Play size="16"/>
                            </Button>
                        </router-link>  
                    </div>
                    <div class="course-info flex-grow ml-4">
                        <div class="course-title">
                            {{ course.title }}
                        </div>
                    </div>
                </div>
            </Card>
        </div>
    </DataLoaderWrapper>
</template>

<style scoped>
.course-list-item {
    @apply py-2 px-2;
    display: flex;
    border-bottom: 1px dashed rgb( var( --border ) );
    align-items: center;
}

.course-list-item:last-child {
    border: none;
}

.course-title {
    font-size: 1.2em;
    font-weight: 500;
}

.section-header {
    margin-bottom: 0;
}

</style>

