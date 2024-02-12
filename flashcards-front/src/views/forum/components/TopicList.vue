<script setup>
import DataLoaderWrapper from '../../../components/wrappers/DataLoaderWrapper.vue';
import { ref } from 'vue';
import PlainButton from '../../../components/ui/PlainButton.vue';
import { useRoute, useRouter } from 'vue-router';

const topics = ref([])
const router = useRouter()
const route = useRoute()

const onLoad = ( data ) => {
    topics.value = data;
}

</script>

<template>

<DataLoaderWrapper url="/api/forum-topics" @load="onLoad">
    <div class="flex lg:block flex-wrap lg:pl-3">
        <PlainButton 
            class='topic' 
            :class="{ 'current-topic': !route.params.topic }"
            @click="() => router.push({ name: 'forum' })">
            All
        </PlainButton>

        <PlainButton 
            class='topic' 
            v-for="topic of topics"
            :class="{ 'current-topic': route.params.topic == topic.slug }"
            @click="() => router.push({ name: 'forum', params: { 'topic': topic.slug }})">
            {{ topic.title }}
        </PlainButton>
    </div>

</DataLoaderWrapper>

</template>

<style scoped>
.topic {
    font-weight: 500;
    color: rgb( var( --muted-foreground ) );
}

.current-topic {
    color: rgb( var( --primary ) );

}
</style>