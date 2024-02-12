<script setup>
import Header from '../../../components/common/Header.vue'
import DeckList from '../components/DeckList.vue'
import Button from '@/components/ui/button/Button.vue';
import { Plus } from 'lucide-vue-next';
import { useUserStore } from '../../../stores/user';
import CourseList from '../components/CourseList.vue';

const user = useUserStore().user;

</script>

<template>
    <Header>
        {{ user.organization.name }}
        <template v-slot:actions>
            <div class="flex">
                <router-link :to="{ name: 'create-deck' }">
                    <Button class='mr-2' icon="fas fa-plus">
                        <Plus size="16" class="mr-2"/>
                        Create deck
                    </Button>
                </router-link>
            </div>
        </template>
    </Header>

    <CourseList></CourseList>
    <DeckList></DeckList>

</template>