<script setup>
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import PaginationLink from './links/PaginationLink.vue';

const props = defineProps({
    pagination: {
        required: true
    }
})

const emit = defineEmits([ 'pageChange' ])

const linkSelected = ( link ) => {
    if ( link.url != null && !link.active ) {
        emit( 'pageChange', link.url )
    }
}

</script>

<template>
    <div class="flex my-4 items-center flex-wrap" v-if="props.pagination.total > 1">
        <div v-for="link of props.pagination.links" 
            class="pagination-link-container">
            <PaginationLink :link="link" @click="() => linkSelected( link )">
                <!-- Previous page link -->
                <span v-if="link.label.includes( 'P' )" >
                    <ChevronLeft size="16" class="inline"/>
                </span>
                <!-- Next page link -->
                <span v-else-if="link.label.includes( 'N' )" >
                    <ChevronRight size="16" class="inline"/>
                </span>
                <!-- Other type of link -->
                <span v-else v-html="link.label"></span>
            </PaginationLink>
        </div>
    </div>
</template>

