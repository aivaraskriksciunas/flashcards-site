<script setup>
import { UserRound } from 'lucide-vue-next';
import PlainButton from './PlainButton.vue';

const props = defineProps({
    account: {
        type: Object,
        required: true
    }
})

const emit = defineEmits([ 'click' ]);

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

const onSelect = () => {
    emit( 'click', props.account.id );
}

</script>

<template>
<PlainButton class="flex items-center" @click="onSelect">
    <div class="p-2">
        <UserRound color="rgb( var( --muted-foreground ))"/>
    </div>
    <div class="p-2">
        <div class="account-name">{{ account.name }}</div>
        <div class="account-type">{{ getAccountTypeName( account.account_type ) }}</div>
    </div>
</PlainButton>
</template>

<style>
.account-type {
    color: rgb( var( --muted-foreground ) );
    font-style: italic;
    font-size: 0.9em;
}

</style>