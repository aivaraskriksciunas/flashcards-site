<script setup>
import { useRoute, useRouter } from 'vue-router';
import { useUserStore } from '../../stores/user';
import AccountButton from '../../components/ui/AccountButton.vue';
import useAccountSwitcher from './composables/useAccountSwitcher';

const user = useUserStore().user;
const router = useRouter()
const route = useRoute()

const redirectUser = () => {
    if ( route.query.r ) {
        router.push({ to: route.query.r, replace: true })
    }
    else {
        router.push({ name: 'home', replace: true })
    }
}

if ( user.accounts.length === 1 ) {
    redirectUser();
}

const { switchAccount } = useAccountSwitcher( redirectUser );

</script>

<template>
    <AccountButton 
        v-for="account of user.accounts" 
        :account="account"
        @click="switchAccount">
    </AccountButton>
</template>