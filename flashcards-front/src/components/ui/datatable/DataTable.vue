<script setup>
import { 
    Table,
    TableRow,
    TableCell,
    TableHeader,
    TableHead,
    TableBody,
    TableCaption,
} from '@/components/ui/table';
import { ref } from 'vue';
import axios from 'axios'
import NumberPaginator from '@/components/pagination/NumberPaginator.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { provide, watch } from 'vue';
import { debounce } from 'lodash'
import { Skeleton } from '@/components/ui/skeleton';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'

const props = defineProps({
    url: {
        type: String,
    },
    selectRow: {
        type: Boolean,
        default: false,
    },
    selectAll: {
        type: Boolean,
        default: false,
    },
    defaultPageSize: {
        type: Number,
        default: 10,
    }
})

const data = ref([])
const pagination = ref( null )
const perPage = ref( "" + props.defaultPageSize )
const query = ref({ per_page: perPage.value })
const isLoading = ref( false )
const error = ref( false )

const getData = async ( url ) => {
    error.value = false
    isLoading.value = true

    let urlParams = {}

    if ( typeof url !== "string" ) {
        url = props.url 
        urlParams = query.value
    }

    try {
        let res = await axios.request({
            url,
            method: 'get',
            params: urlParams,
        })

        if ( res.data.meta?.links ) {
            pagination.value = res.data.meta 
            data.value = res.data.data
        }
        else {
            pagination.value = null // No pagination
            data.value = res.data
        }
    }
    catch ( err ) {
        console.log( err )
        error.value = 'An error ocurred.'
    }
    finally {
        isLoading.value = false
    }
}

// Listen to changes to the query
const getDataDebounced = debounce( getData, 500 )
watch( query, getDataDebounced, { deep: true } )
getData()

const queryManager = {
    setSearch: ( searchStr ) => {
        queryManager.setParam( "search", searchStr )
    },
    setSort: ( col, descending ) => {
        if ( col == null ) {
            queryManager.clearParam( "sort" )
            return;
        }

        if ( descending ) {
            col = `-${col}`
        }

        queryManager.setParam( "sort", col )
    },
    setParam: ( name, value ) => {
        let q = query.value 
        q[name] = value

        query.value = q
    },
    clearParam: ( name ) => {
        let q = query.value 
        delete q[name]

        query.value = q
    }
}

// Listen to the changes to other query parameters
watch( perPage, ( p ) => queryManager.setParam( 'per_page', p ) )
provide( 'queryManager', queryManager )

</script>

<template>

<Table>
    <TableHeader>
        <TableRow>
            <TableHead v-if="props.selectAll" class="w-4">
                <Checkbox/>
            </TableHead>
            <slot name="header"></slot>
        </TableRow>
    </TableHeader>

    <TableBody>
        <TableRow v-if="!error && !isLoading" v-for="row of data" :key="row.id">
            <TableCell v-if="props.selectRow" class="w-4">
                <Checkbox/>
            </TableCell>

            <slot name="row" :row="row"></slot>
        </TableRow>
        <TableRow v-else-if="error">
            {{ error }}
        </TableRow>
    </TableBody>
</Table>

<div v-if="!isLoading && pagination && data.length > 0" class="px-4 flex items-center mt-2">
    <div class="text-sm italic text-muted-foreground">
        Total: {{ pagination.total }}
    </div>
    <div class="flex-grow"></div>
    <div>
        <Select v-model="perPage">
            <SelectTrigger class="w-24">
                <SelectValue placeholder="Page size"/>
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectLabel>Items per page</SelectLabel>
                    <SelectItem value="5">
                        5
                    </SelectItem>
                    <SelectItem value="10">
                        10
                    </SelectItem>
                    <SelectItem value="15">
                        15
                    </SelectItem>
                    <SelectItem value="30">
                        30
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
    </div>
</div>

<div v-if="isLoading" class="p-4">
    <Skeleton class="w-full h-4 mb-4"></Skeleton>
    <Skeleton class="w-full h-4 mb-4"></Skeleton>
    <Skeleton class="w-full h-4 mb-4"></Skeleton>
</div>

<div v-if="!isLoading && data.length == 0">
    <slot name="empty"></slot>
</div>

<NumberPaginator 
    v-if="pagination?.last_page > 1" 
    :pagination="pagination"
    @page-change="url => getData( url )"></NumberPaginator>


</template>