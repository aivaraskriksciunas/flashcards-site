<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import DataLoaderWrapper from '../../components/wrappers/DataLoaderWrapper.vue';
import Card from '../../components/ui/Card.vue';
import Header from '../../components/common/Header.vue';
import ReactButtons from './components/ReactButtons.vue';
import SlimContainer from '../../components/ui/SlimContainer.vue';
import AjaxForm from '../../components/forms/AjaxForm.vue';
import TextareaField from '../../components/forms/TextareaField.vue';
import ForumPostComment from './components/ForumPostComment.vue';

const route = useRoute()
const post = ref({})
const comments = ref([])

const onLoad = ( data ) => post.value = data;
const onCommentsLoad = ( data ) => comments.value = data.data

const commentAdded = ( data ) => {
    comments.value = [ data, ...comments.value ]
}

</script>

<template>
<DataLoaderWrapper :url="`/api/forum-posts/${route.params.id}`" @load="onLoad">
    <SlimContainer>
        <Card>
            <div class="lg:flex">
                <Header class="flex-grow">{{ post.title }}</Header>
                <ReactButtons :postId="post.id" :reactions="post.reactions"></ReactButtons>
            </div>
            <div class="post-content">
                {{ post.content }}
            </div>
        </Card>

        <DataLoaderWrapper class="mt-6" :url="`/api/forum-posts/${route.params.id}/comments`" @load="onCommentsLoad">
            <Card>
                <Header>Comments</Header>
                
                <AjaxForm 
                    :action="`/api/forum-posts/${route.params.id}/comments`" 
                    @success="commentAdded" 
                    clearOnSubmit=""
                    class="mb-8">
                    <TextareaField name="content" placeholder="Write your reply here..."></TextareaField>
                </AjaxForm>

                <ForumPostComment v-for="comment of comments" :comment="comment" :key="comment.id">
                </ForumPostComment>

            </Card>
        </DataLoaderWrapper>
    </SlimContainer>
</DataLoaderWrapper>
</template>

<style scoped>
.post-content {
    padding: 8px;
}
</style>