<script setup>
import AjaxForm from '@/components/forms/AjaxForm.vue';
import TextField from '@/components/forms/TextField.vue';
import { useRouter } from 'vue-router';
import Header from '@/components/common/Header.vue'
import SlimContainer from '@/components/ui/SlimContainer.vue';
import Card from '@/components/ui/Card.vue';

const router = useRouter();

const onCourseCreate = ( data ) => {
    router.push({ name: 'edit-course', params: { id: data.id } } );
}

</script>

<template>
    <SlimContainer>
        <Card>
            <Header>New course</Header>

            <AjaxForm method="post" action="/api/courses" @success="onCourseCreate">
                <TextField name="title">Course title</TextField>
            </AjaxForm>
        </Card>
    </SlimContainer>

</template>
