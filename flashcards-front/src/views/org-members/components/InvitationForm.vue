<script setup>
import AjaxForm from '@/components/forms/AjaxForm.vue';
import TextField from '@/components/forms/TextField.vue';
import SelectField from '@/components/forms/SelectField.vue';
import { useMemberStore } from '../stores/member-store.js';

const accountTypeChoices = [
    { value: 'orgmember', label: 'Member' },
    { value: 'orgmanager', label: 'Manager' },
    { value: 'orgadmin', label: 'Administrator' },
]

const store = useMemberStore()

const onSuccess = ( data ) => {
    store.addInvitation( data );
}
</script>

<template>
    <AjaxForm method="POST" action="/api/invitations/create" @success="onSuccess">
        <TextField name="name">
            Name:
        </TextField>

        <TextField name="email" type="email">
            Email:
        </TextField>

        <SelectField name="account_type" :choices="accountTypeChoices" class="mb-4">
            New member type:
        </SelectField>
    </AjaxForm>

</template>