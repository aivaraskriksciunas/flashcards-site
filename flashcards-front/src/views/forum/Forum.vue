<script setup>
import { ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import Header from '../../components/common/Header.vue';
import Button from '../../components/ui/Button.vue';
import ForumPostList from './components/ForumPostList.vue';
import TopicList from './components/TopicList.vue';

const route = useRoute()

</script>

<template>

    <Header>
        Forum
        <template v-slot:actions>
            <Button 
                v-if="route.params.topic" 
                :to="{ name: 'create-forum-post', params: { topic: route.params.topic } }">
                New post
            </Button>
        </template>
    </Header>
    
    <div class="flex">
        <div class="content flex-grow">
            <ForumPostList :topic="route.params.topic"></ForumPostList>
        </div>

        <TopicList></TopicList>

    </div>

</template>