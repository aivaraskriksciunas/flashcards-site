<script setup>
import IconButton from '../../../components/ui/IconButton.vue';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    postId: {
        required: true,
        type: Number
    },
    reactions: {
        required: true
    },
    comment: {
        type: Boolean,
        default: false,
    }
})

const reactions = ref( props.reactions )
const isLoading = ref( false )

const onVote = async ( reaction ) => {
    if ( isLoading.value ) return;

    isLoading.value = true;
    
    try {
        if ( props.comment ) {
            const res = await axios.post( `/api/comments/react/${props.postId}`, { reaction } )
            reactions.value = res.data
        }
        else {
            const res = await axios.post( `/api/forum-posts/react/${props.postId}`, { reaction } )
            reactions.value = res.data
        }
    }
    catch ( err ) {

    }
    finally {
        isLoading.value = false;
    }
}

</script>

<template>
<div v-if="!isLoading" class="react-button-container">
    <div class="single-react-button mr-3">
        <IconButton @click="() => onVote( 'upvote' )" :filled="reactions.user_reaction == '1'" icon="fa-regular fa-thumbs-up"></IconButton>
        <div class='reaction-count upvotes'>{{ reactions.upvotes }}</div>
    </div>
    <div class="single-react-button mr-2">
        <IconButton @click="() => onVote( 'downvote' )" :filled="reactions.user_reaction == '-1'" icon="fa-regular fa-thumbs-down" type="danger"></IconButton>
        <div class='reaction-count downvotes'>{{ reactions.downvotes }}</div>
    </div>
</div>
<div v-else>
    Loading...
</div>
</template>

<style scoped>
.react-button-container {
    display: flex;
}

.single-react-button {
    text-align: center;
    display: flex;
    align-items: center;
}

.reaction-count {
    margin-left: 6px;
    font-weight: 300;
    color: var( --color-text-lighter );
}

@media ( min-width: 1024px ) {
    .single-react-button {
        display: block;
    }

    .reaction-count {
        margin-top: 6px;
    }
}
</style>