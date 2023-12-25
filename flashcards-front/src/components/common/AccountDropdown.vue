<script setup>
import { useRouter } from 'vue-router';
import { useUserStore } from '../../stores/user';
import useAccountSwitcher from '../../composables/useAccountSwitcher';
import AccountButton from '../ui/AccountButton.vue';
import DropdownContainer from '../ui/DropdownContainer.vue';
import PlainButton from '../ui/PlainButton.vue';

const props = defineProps({
    showLogout: {
        type: Boolean,
        default: true,
    }
})

const router = useRouter()

const userStore = useUserStore()
const { switchAccount } = useAccountSwitcher( () => {
    router.go() // Refresh the page
})

</script>

<template>

<DropdownContainer>
    <template v-slot:dropdown-button>
        <PlainButton class="flex">
            <div class="pr-2">
                <font-awesome-icon icon="fa-regular fa-user"></font-awesome-icon>
            </div>
            {{ userStore.user.name }}
        </PlainButton>
    </template>

    <AccountButton v-for="account of userStore.user.accounts" :account="account" @click="switchAccount"></AccountButton>
    <router-link :to="{ name: 'logout' }">
        <PlainButton v-if="props.showLogout" class="px-2 flex items-center">
            <div class="p-2">
                <font-awesome-icon icon="fa-solid fa-arrow-right-from-bracket"></font-awesome-icon>
            </div>
            Logout
        </PlainButton>
    </router-link>
</DropdownContainer>

</template>