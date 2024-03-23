<script setup>
import { 
    TableHead,
} from '@/components/ui/table';
import { ArrowDownUp, ArrowDownWideNarrow, ArrowUpNarrowWide } from 'lucide-vue-next';
import { inject, ref } from 'vue'

const props = defineProps({
    sortable: {
        type: String,
        default: '',
    }
})

const queryManager = inject( 'queryManager' )

const sort = ref( null )
const toggleSort = () => {
    if ( !props.sortable ) return;

    if ( sort.value == null ) {
        sort.value = "asc"
        queryManager.setSort( props.sortable, false )
    }
    else if ( sort.value == "asc" ) {
        sort.value = "desc"
        queryManager.setSort( props.sortable, true )
    }
    else {
        sort.value = null 
        queryManager.setSort( null )
    }
}

</script>

<template>
    <TableHead :class="{ 'cursor-pointer': props.sortable }" class="select-none" @click="toggleSort">
        <slot></slot>
        <ArrowDownUp v-if="props.sortable && sort == null" size='16' class="inline ml-2"/>
        <ArrowDownWideNarrow v-else-if="props.sortable && sort == 'desc'" size="16" class="inline ml-2"/>
        <ArrowUpNarrowWide v-else-if="props.sortable && sort == 'asc'" size='16' class="inline ml-2"/>
    </TableHead>

</template>