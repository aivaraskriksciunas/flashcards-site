<script setup>
import AjaxForm from '../forms/AjaxForm.vue';
import DataLoaderWrapper from '@/components/wrappers/DataLoaderWrapper.vue';
import { useUserStore } from '@/stores/user'
import { storeToRefs } from 'pinia'
import { ref } from 'vue';
import { 
    Table,
    TableRow,
    TableCell,
    TableHeader,
    TableHead,
    TableBody,
} from '@/components/ui/table';

const { user } = storeToRefs( useUserStore() )
const members = ref([])

</script>

<template>
    <DataLoaderWrapper :url="`/api/organizations/members`" @load="m => members = m.data">
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
                    <TableCell>
                        <slot :member="member">-</slot>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </DataLoaderWrapper>
</template>