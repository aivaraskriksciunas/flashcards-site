<script setup>
import TextField from '@/components/forms/TextField.vue';
import { useRouter, useRoute } from 'vue-router';
import Header from '@/components/common/Header.vue';
import MemberSearchForm from '@/components/common/MemberSearchForm.vue';
import Card from '@/components/ui/Card.vue';
import DataLoaderWrapper from '@/components/wrappers/DataLoaderWrapper.vue'
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import AssignedMemberList from './components/AssignedMemberList.vue'
import { useUserStore } from '@/stores/user';
import { Link } from 'lucide-vue-next'
import CourseAccessLinkList from './components/CourseAccessLinkList.vue'

const router = useRouter();
const route = useRoute();

const { isOrgManager } = useUserStore()
const course = ref( null )

</script>

<template>
    <DataLoaderWrapper :url="`/api/courses/${route.params.id}`" @load="( c ) => course = c">
        <Header>{{ course.title }}</Header>
        <div class="md:flex gap-4">
            <div class="md:w-1/2">
                <Card class="mb-4">
                    <Header :level="2">
                        Students

                        <template #actions>
                            <router-link v-if="isOrgManager()" :to="{ name: 'course-assign-members'}">
                                <Button><Link size="16" class="mr-2" />Assign students</Button>
                            </router-link>
                        </template>
                    </Header>
                    <AssignedMemberList :course="course"/>
                </Card>
            </div>

            <div class="md:w-1/2">
                <CourseAccessLinkList :course="course"/>
            </div>
        </div>
        
    </DataLoaderWrapper>

</template>
