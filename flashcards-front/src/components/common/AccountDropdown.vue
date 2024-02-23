<script setup>
import { useRouter } from 'vue-router';
import { useUserStore } from '../../stores/user';
import useAccountSwitcher from '../../composables/useAccountSwitcher';
import AccountButton from '../ui/AccountButton.vue';
import { 
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '../ui/dropdown-menu';
import { Button } from '../ui/button';
import { UserRound, LogOut } from 'lucide-vue-next';

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

const getAccountTypeName = ( type ) => {
    switch ( type ) {
        case 'admin':
            return 'Administrator';
        case 'orgadmin': 
            return 'Organization';
        case 'student':
        default: 
            return 'Student'
    }
}

</script>

<template>

<DropdownMenu>
    <DropdownMenuTrigger>
        <Button variant="ghost">
            <UserRound class="mr-2" />
            {{ userStore.user.name }}
        </Button>
    </DropdownMenuTrigger>

    <DropdownMenuContent>
        <DropdownMenuItem v-for="account of userStore.user.accounts" @click="() => switchAccount( account.id )">
            <div class="flex account-button">
                <div class="account-button__icon">
                    <UserRound/>
                </div>
                <div class="account-button__info">
                    <div class="account-button__name">
                        {{ account.name }}
                    </div>
                    <div class="account-button__type">
                        {{ getAccountTypeName( account.account_type ) }}
                    </div>
                </div>
            </div>
        </DropdownMenuItem>
        <router-link :to="{ name: 'logout' }">
            <DropdownMenuItem v-if="props.showLogout">
                <LogOut class="mr-2" size="16"/>
                Logout
            </DropdownMenuItem>
        </router-link>
    </DropdownMenuContent>
    
</DropdownMenu>

</template>

<style scoped>
.account-button {
    
}

.account-button__info {
    @apply p-2;
}

.account-button__icon {
    @apply px-1;
    display: flex;
    align-items: center;
    color: rgb( var( --muted-foreground ) );
}

.account-button__name {
    font-weight: 500;
}

.account-button__type {
    font-weight: 300;
    font-size: 0.9em;
}
</style>