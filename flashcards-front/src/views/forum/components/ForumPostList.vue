<script setup>
import DataLoaderWrapper from '../../../components/wrappers/DataLoaderWrapper.vue';
import { ref } from 'vue';
import Card from '../../../components/ui/Card.vue';
import ForumPostListItem from './ForumPostListItem.vue';

const props = defineProps({
    topic: {
        required: false,
        default: '',
    }
})

const posts = ref([])
const onLoad = ( data ) => {
    posts.value = data.data
}

</script>

<template>
<DataLoaderWrapper :url="`/api/forum-posts/list/${props.topic}`" :key="props.topic" @load="onLoad">
    <Card>
        <ForumPostListItem v-for="post of posts" :item="post">
        </ForumPostListItem>
    </Card>
</DataLoaderWrapper>
</template>