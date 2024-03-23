<script setup>

const props = defineProps({
    link: {
        required: true
    }
})

</script>

<template>

<span v-if="link.active" class="active-pagination-link">
    <slot></slot>
</span>
<!-- Inactive link -->
<span v-else-if="link.url == null" class="pagination-spacer">
    <slot></slot>
</span>
<!-- Specific page link -->
<span v-else class="pagination-link">
    <slot></slot>
</span>

</template>

<style>

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
    background-color: rgb( var( --shadow ) );
}

</style>