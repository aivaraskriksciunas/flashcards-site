<script setup>
import ReactButtons from './ReactButtons.vue';


const props = defineProps({
    comment: {
        required: true
    }
})

</script>

<template>
<div class="forum-post-comment flex">
    <div class="flex-grow">
        <div class="comment-meta">
            <font-awesome-icon class='icon' icon="fa-regular fa-user"></font-awesome-icon>
            {{ props.comment.user.name }}
            <span class="comment-date">
                Posted 
                {{ props.comment.human_created_at }}
            </span>
        </div>
        <div class="comment-content">
            {{ props.comment.content }}
        </div>    
    </div>
    <ReactButtons :post-id="props.comment.id" :reactions="props.comment.reactions" comment></ReactButtons>
    
</div>
</template>

<style>

.forum-post-comment {
    padding: 12px;
    margin-top: 8px;
    border-bottom: 1px solid var( --color-shadow );
}

.forum-post-comment:last-child {
    border: none;
}

.forum-post-comment .comment-meta {
    font-size: 0.8em;
    color: var( --color-text-lighter );
    margin-bottom: 4px;
}

.forum-post-comment .comment-meta .comment-date {
    color: var( --color-text-lighter );
}

.forum-post-comment .comment-meta .icon {
    margin-right: 6px;
}

.forum-post-comment .comment-meta .icon path {
    fill: var( --color-text-lighter );
}

</style>