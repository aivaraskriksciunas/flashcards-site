<script setup>
import AjaxForm from '../../components/forms/AjaxForm.vue';
import TextareaField from '../../components/forms/TextareaField.vue';
import Card from '../../components/ui/Card.vue';
import SlimContainer from '../../components/ui/SlimContainer.vue';
import { useRouter } from 'vue-router';

const router = useRouter()

const onImport = ( data ) => {
    router.push({ name: 'edit-deck', params: { id: data.id } });
}

</script>

<template>
<SlimContainer>
    <h1>Import from Quizlet/Excel/Word list</h1>
    <p class="mb-4">Create an Aktulibre deck from a Quizlet, Excel, or any text list.</p>

    <Card>
        <AjaxForm action="/api/import/quizlet" @success="onImport">
            <TextareaField name="content" placeholder="word&Tab;definition&NewLine;question&Tab;answer">
                Words separated by tab
            </TextareaField>
        </AjaxForm>
    </Card>
</SlimContainer>
</template>