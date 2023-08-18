<script setup>
import AjaxForm from '../../components/forms/AjaxForm.vue';
import Header from '../../components/common/Header.vue';
import TextField from '../../components/forms/TextField.vue';
import TextareaField from '../../components/forms/TextareaField.vue';
import SlimContainer from '../../components/ui/SlimContainer.vue';
import HiddenField from '../../components/forms/HiddenField.vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute()
const router = useRouter()

const onCreated = ( data ) => {
    router.push({ name: 'view-forum-post', params: { id: data.id } })
}

</script>

<template>
    <SlimContainer>
        <Header>New forum post</Header>
        <AjaxForm action="/api/forum-posts" method="POST" @success="onCreated">
            <HiddenField name="forum_topic" :value="route.params.topic"></HiddenField>
            <TextField name="title">Title:</TextField>
            <TextareaField name="content">Content:</TextareaField>
        </AjaxForm>
    </SlimContainer>
</template>