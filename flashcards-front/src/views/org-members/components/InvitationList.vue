<script setup>
import DataLoaderWrapper from '@/components/wrappers/DataLoaderWrapper.vue';
import { storeToRefs } from 'pinia';
import { useMemberStore } from '../stores/member-store.js';
import { 
    Table,
    TableRow,
    TableCell,
    TableHeader,
    TableHead,
    TableBody,
} from '@/components/ui/table';

const { invitations } = storeToRefs( useMemberStore() )
const onLoad = ( data ) => {
    invitations.value = data.data
}

</script>

<template>
    <DataLoaderWrapper url="/api/organizations/invitations" @load="onLoad">
        <Table v-if="invitations.length">
            <TableHeader>
                <TableRow>
                    <TableHead>Name</TableHead>
                    <TableHead>Email</TableHead>
                    <TableHead>Created by</TableHead>
                    <TableHead>Valid until</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="invitation of invitations">
                    <TableCell>{{ invitation.name }}</TableCell>
                    <TableCell>{{ invitation.email }}</TableCell>
                    <TableCell>{{ invitation.creator.name }}</TableCell>
                    <TableCell>{{ new Date( invitation.valid_until ).toLocaleDateString() }}</TableCell>
                </TableRow>
            </TableBody>
        </Table>
        <div v-else>
            No pending invitations
        </div>
    </DataLoaderWrapper>
</template>