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
import { storeToRefs } from 'pinia';
import { Link } from 'lucide-vue-next'

const router = useRouter();
const route = useRoute();

const { isOrgManager } = useUserStore()
const course = ref( null )

</script>

<template>
    <DataLoaderWrapper :url="`/api/courses/${route.params.id}`" @load="( c ) => course = c">
        <Header>{{ course.title }}</Header>
        <div class="md:flex">
            <div class="md:w-2/3">
                <Card class="mb-4">
                    <Header :level="2">Students</Header>
                    <AssignedMemberList :course="course"/>
                </Card>
            </div>
            <div class="md:w-1/3 px-4">
                <router-link :to="{ name: 'course-assign-members'}">
                    <Button><Link size="16" class="mr-2" />Invite</Button>
                </router-link>
            </div>
        </div>
        
    </DataLoaderWrapper>

</template>
