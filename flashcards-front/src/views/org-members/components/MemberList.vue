<script setup>
import { DataTable, DataTableCell, DataTableHead } from '@/components/ui/datatable';

</script>

<template>
    <DataTable url="/api/organizations/members">
        <template #header>
            <DataTableHead>Name</DataTableHead>
            <DataTableHead>Email</DataTableHead>
            <DataTableHead>Actions</DataTableHead>
        </template>

        <template #row="{ row }">
            <DataTableCell>{{ row.name }}</DataTableCell>
            <DataTableCell>{{ row.email }}</DataTableCell>
            <DataTableCell>-</DataTableCell>
        </template>
    </DataTable>
</template>