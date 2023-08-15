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
            <div class="forum-post-content">{{ props.item.content }}</div>
            <div class="forum-post-details">
                <span class="forum-post-author">{{ props.item.user.name }}</span> posted 
                <span class="forum-post-date">{{ props.item.human_created_at }}</span>
            </div>
        </div>
        <div class="forum-list-item-actions">
            <ReactButtons :postId="props.item.id" :reactions="props.item.reactions"></ReactButtons>
        </div>
    </div>
</template>

<style scoped>

.forum-list-item {
    padding: 1.2em 1.5em;
    border-bottom: 1px dashed var( --color-shadow );
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
    color: var( --color-text-light );
}

.forum-post-content {
    color: var( --color-text-light );
    font-weight: 300;
}

</style>