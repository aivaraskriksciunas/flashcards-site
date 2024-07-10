<script setup>
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/button/Button.vue';
import { Play, Share2, Pencil } from 'lucide-vue-next';
import { useUserStore } from '@/stores/user';

import DataTable from '@/components/ui/datatable/DataTable.vue';
import DataTableCell from '@/components/ui/datatable/DataTableCell.vue';

const userStore = useUserStore();

</script>

<template>

    <Card>
        <DataTable url="/api/my-courses">
            <template #empty>
                <p>
                    You do not have any courses yet.
                    <router-link :to="{ name: 'create-course' }">Create course</router-link>
                </p>
            </template>

            <template #row="{ row }" >
                <DataTableCell class="course-list-item md:flex">
                    <div class="course-info flex-grow">
                        <div class="course-title">
                            <router-link :to="{ name: 'course-summary', params: { id: row.id }}">
                                {{ row.title }}
                            </router-link>
                        </div>
                    </div>

                    <div class="course-actions">
                        <router-link :to="{ name: 'course-summary', params: { id: row.id }}">
                            <Button variant="ghost" size="icon" class='text-foreground mr-2'>
                                <Share2 size="16" />
                            </Button>
                        </router-link>
                        <router-link v-if="row.user_id = userStore.user.id" 
                            :to="{ name: 'edit-course', params: { id: row.id }}">
                            <Button variant="ghost" size="icon" class='text-foreground mr-2'>
                                <Pencil size="16" />
                            </Button>
                        </router-link>
                        <router-link :to="{ name: 'view-course', params: { access_link: row.link } }">
                            <Button variant="outline" size="icon" class="border-primary">
                                <Play size="16"/>
                            </Button>
                        </router-link>
                        
                    </div>
                </DataTableCell>
            </template>
        </DataTable>
    </Card>


</template>

<style scoped>
.course-list-item {
    @apply p-6;
    display: flex;
    border-bottom: 1px dashed rgb( var( --border ) );
    align-items: center;
}

.course-list-item:last-child {
    border: none;
}

.course-title {
    font-size: 1.5em;
    font-weight: 500;
}


</style>

