<script setup>
import PlainButton from '../ui/PlainButton.vue';


const props = defineProps({
    pagination: {
        required: true
    }
})

const emit = defineEmits([ 'pageChange' ])

</script>

<template>
    <div class="flex my-8 items-center flex-wrap">
        <div v-for="link of props.pagination.links" 
            class="pagination-link-container">
            <span v-if="link.active" class="active-pagination-link" v-html="link.label"></span>
            <span v-else-if="link.url == null" class="pagination-spacer" v-html="link.label"></span>
            <span v-else @click="() => emit( 'pageChange', link.url )" class="pagination-link" v-html="link.label"></span>
        </div>
    </div>
</template>

<style scoped>

.pagination-link, 
.active-pagination-link, 
.pagination-spacer {
    padding: 12px;
    border-radius: 3px;

}

.pagination-spacer {
    color: rgb( var( --muted-foreground ) );
}

.active-pagination-link {
    color: rgb( var( --primary ) );
    font-weight: 500;
}

.pagination-link {
    cursor: pointer;
}

.pagination-link:hover {
    background-color: rgb( var( --shadow ) ));
}

</style>