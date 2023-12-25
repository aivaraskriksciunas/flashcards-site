<script setup>
import AjaxForm from '../../components/forms/AjaxForm.vue';
import AccountButton from '../../components/ui/AccountButton.vue';
import { useUserStore } from '../../stores/user';
import useAccountSwitcher from '../../composables/useAccountSwitcher';
import Card from '../../components/ui/Card.vue';
import TextField from '../../components/forms/TextField.vue';
import Header from '../../components/common/Header.vue';
import AccountDropdown from '../../components/common/AccountDropdown.vue';
import { useRouter } from 'vue-router';
import { useStatusMessageService } from '../../services/StatusMessageService';

const user = useUserStore().user;
const { switchAccount } = useAccountSwitcher();
const router = useRouter()

const statusMessages = useStatusMessageService()

const onSuccess = () => {
    statusMessages.addStatusMessage( 'Changes saved.', 'Your profile information has been updated' )
    router.go()
}

</script>

<template>
    <div class="md:flex">
        <div class="container">
            <Header>Profile settings</Header>
            <AccountDropdown v-if='user.accounts.length > 1' class="mb-6 md:hidden"></AccountDropdown>
            <Card>
                <AjaxForm 
                    action="/api/accounts" 
                    method="patch" 
                    submit-text="Save changes" 
                    @success="onSuccess">
                    <TextField name="name" :value="user.name">Name</TextField>
                    <TextField 
                        name="email" 
                        type="email" 
                        :value="user.email" 
                        autocomplete="off" 
                        note="You will need to verify your email if you change it here.">Email</TextField>
                    <TextField name="password" type="password" autocomplete="new-password">New password</TextField>
                    <TextField name="password_confirmation" type="password">Confirm new password</TextField>
                </AjaxForm>
            </Card>
        </div>
        <div class='hidden md:block w-1/3 ml-8' id="accountPicker">
            <AccountButton v-for="account of user.accounts" :account="account" @click="switchAccount"></AccountButton>
        </div>
    </div>
</template>

<style scoped>

#accountPicker {
    max-width: 300px;
}

</style>