<script setup>
import AjaxForm from '../../components/forms/AjaxForm.vue';
import TextField from '../../components/forms/TextField.vue';
import SelectField from '../../components/forms/SelectField.vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const organizationTypes = [
    { value: 'education', label: 'Education' },
    { value: 'for-profit', label: 'For-profit business' },
    { value: 'non-profit', label: 'Non-profit (NGO)' },
    { value: 'government', label: 'Government' },
]

const onRegister = ( data ) => {
    router.push({ name: 'home' })
}

</script>

<template>
    Register Organization

    <AjaxForm action="/api/register/org" @success="onRegister">
        <TextField type="text" name="name" placeholder="Learning Inc.">Name of your organization:</TextField>
        <SelectField name="type" :choices="organizationTypes" class="mb-6">
            What is the category of your organization?
        </SelectField>
    </AjaxForm>
</template>