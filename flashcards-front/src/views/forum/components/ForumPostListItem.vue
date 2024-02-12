<script setup>
import ReactButtons from './ReactButtons.vue';

const props = defineProps({
    item: {
        required: true
    }
});


</script>

<template>
    <div class="forum-list-item flex">
        <div class="forum-list-item-content flex-grow">
            <div class='forum-post-title'>
                <router-link :to="{ name: 'view-forum-post', params: { id: props.item.id } }">
                    {{ props.item.title }}
                </router-link>
            </div>
            <div class="forum-list-item-actions lg:hidden mb-3">
                <ReactButtons :postId="props.item.id" :reactions="props.item.reactions"></ReactButtons>
            </div>
            <div class="forum-post-content">{{ props.item.content }}</div>
            <div class="forum-post-details">
                <span class="forum-post-author">{{ props.item.user.name }}</span> posted 
                <span class="forum-post-date">{{ props.item.human_created_at }}</span>
            </div>
        </div>
        <div class="forum-list-item-actions hidden lg:block">
            <ReactButtons :postId="props.item.id" :reactions="props.item.reactions"></ReactButtons>
        </div>
    </div>
</template>

<style scoped>

.forum-list-item {
    padding: 1.2em 1.5em;
    border-bottom: 1px dashed rgb( var( --shadow ) );
    margin-bottom: 8px;
}

.forum-list-item:last-child {
    border-bottom: none;
}

.forum-post-title {
    font-size: 1.4em;
    font-weight: 500;
    margin-bottom: 8px;
}

.forum-post-details {
    font-size: 0.8em;
}

.forum-post-author {

}

.forum-post-date {
    font-style: italic;
    color: rgb( var( --muted-foreground ) );
}

.forum-post-content {
    color: rgb( var( --muted-foreground ) );
    font-weight: 300;
}

</style>