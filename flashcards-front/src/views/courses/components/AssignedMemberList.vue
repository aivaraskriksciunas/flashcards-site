<script setup>
import { useRoute, useRouter } from 'vue-router';
import { ref } from 'vue'
import { DataTable, DataTableHead, DataTableCell } from '@/components/ui/datatable';

const router = useRouter();
const route = useRoute();

const props = defineProps({
    course: {
        required: true,
        type: Object,
    }
})

</script>

<template>
    <DataTable :url="`/api/courses/${props.course.id}/assigned`" >
        <template #header>
            <DataTableHead sortable="name">Name</DataTableHead>
            <DataTableHead>Progress</DataTableHead>
        </template>

        <template #row="{ row }">
            <DataTableCell>{{ row.name }}</DataTableCell>
            <DataTableCell>--</DataTableCell>
        </template>
    </DataTable>
</template>

<style scoped>
#courseLayoutContainer {
    border-right: 1px solid rgb( var( --muted ) );
}
</style>