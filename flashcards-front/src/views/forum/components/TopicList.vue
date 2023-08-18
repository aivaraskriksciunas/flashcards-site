<script setup>
import DataLoaderWrapper from '../../../components/wrappers/DataLoaderWrapper.vue';
import { ref } from 'vue';
import PlainButton from '../../../components/ui/PlainButton.vue';
import { useRouter } from 'vue-router';

const topics = ref([])
const router = useRouter()

const onLoad = ( data ) => {
    topics.value = data;
}

</script>

<template>

<DataLoaderWrapper url="/api/forum-topics" @load="onLoad">
    <div class="pl-8">
        <PlainButton 
            class='topic' 
            @click="() => router.push({ name: 'forum' })">
            All
        </PlainButton>

        <PlainButton 
            class='topic' 
            v-for="topic of topics"
            @click="() => router.push({ name: 'forum', params: { 'topic': topic.slug }})">
            {{ topic.title }}
        </PlainButton>
    </div>

</DataLoaderWrapper>

</template>

<style scoped>
.topic {
    font-weight: 500;
    color: var( --color-text-light );
}
</style>