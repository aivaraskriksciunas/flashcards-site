<script setup>
import DataLoaderWrapper from '@/components/wrappers/DataLoaderWrapper.vue';
import { ref } from 'vue';
import { useMemberStore } from '../stores/member-store.js';
import { storeToRefs } from 'pinia';
import { 
    Table,
    TableRow,
    TableCell,
    TableHeader,
    TableHead,
    TableBody,
} from '@/components/ui/table';

const store = useMemberStore()
const { members } = storeToRefs( store )
const onLoad = ( data ) => {
    store.setMembers( data.data )
}

</script>

<template>
    <DataLoaderWrapper url="/api/organizations/members" @load="onLoad">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Name</TableHead>
                    <TableHead>Email</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="member of members">
                    <TableCell>{{ member.name }}</TableCell>
                    <TableCell>{{ member.email }}</TableCell>
                    <TableCell></TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </DataLoaderWrapper>
</template>