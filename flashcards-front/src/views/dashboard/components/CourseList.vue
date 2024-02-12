<script setup>
import { ref } from 'vue';
import DataLoaderWrapper from '../../../components/wrappers/DataLoaderWrapper.vue';
import Card from '@/components/ui/Card.vue';

const courses = ref([])

const onDataLoad = ( data ) => { courses.value = data }

</script>

<template>
    <DataLoaderWrapper url="/api/courses" @load="onDataLoad">
        
        <div class="md:flex deck-list">
            <div v-for="course of courses" :key="course.id">
                <router-link :to="{ name: 'edit-course', params: { id: course.id }}">
                    <Card hover>
                        <div class="deck-name">{{ course.title }}</div>
                    </Card>
                </router-link>
            </div>
            
        </div>
    </DataLoaderWrapper>
</template>

<style scoped>
.deck-list {
    margin: 0 -12px;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 12px;
}

.deck-list-item {
    margin-bottom: 1em;
}

@media screen and ( min-width: 768px ) {
    .deck-list-item {
        width: 30%;
        max-width: 280px;
        margin-bottom: 0em;
    }
}
</style>

