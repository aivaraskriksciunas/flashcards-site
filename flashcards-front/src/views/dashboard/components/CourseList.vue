<script setup>
import { ref } from 'vue';
import DataLoaderWrapper from '../../../components/wrappers/DataLoaderWrapper.vue';
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/button/Button.vue';
import { Play } from 'lucide-vue-next';
import { Pencil } from 'lucide-vue-next';
import { useUserStore } from '@/stores/user';

const courses = ref([])
const userStore = useUserStore();

const onDataLoad = ( data ) => { courses.value = data }

</script>

<template>
    <DataLoaderWrapper url="/api/courses" @load="onDataLoad">
        
        <Card class="xl:w-3/4">
            <div v-for="course of courses" :key="course.id" class="course-list-item md:flex">
                <div class="course-info flex-grow">
                    <div class="course-title">
                        <router-link :to="{ name: 'course-summary', params: { id: course.id }}">
                            {{ course.title }}
                        </router-link>
                    </div>
                </div>
                <div class="course-actions">
                    <router-link :to="{ name: 'view-course', params: { id: course.id } }">
                        <Button variant="default" size="icon" class="mr-2">
                            <Play size="16"/>
                        </Button>
                    </router-link>
                    <router-link v-if="userStore.isOrgManager()" 
                        :to="{ name: 'edit-course', params: { id: course.id }}">
                        <Button variant="ghost" size="icon" class='text-foreground'>
                            <Pencil size="16" />
                        </Button>
                    </router-link>
                    
                </div>
            </div>
        </Card>
    </DataLoaderWrapper>
</template>

<style scoped>
.course-list-item {
    @apply p-4;
    display: flex;
    border-bottom: 1px dashed rgb( var( --border ) );
    align-items: center;
}

.course-list-item:last-child {
    border: none;
}

.course-title {
    font-size: 1.3em;
    font-weight: 500;
}


</style>

