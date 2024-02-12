<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { Button } from '@/components/ui/button';
import { ThumbsUp, ThumbsDown } from 'lucide-vue-next';

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
        <Button 
            :variant="reactions.user_reaction == '1' ? 'default' : 'ghost'"
            size="sm"
            @click="() => onVote( 'upvote' )">
            <ThumbsUp class="h-4 w-4 mr-2"/>
            {{ reactions.upvotes }}
        </Button>
    </div>
    <div class="single-react-button mr-2">
        <Button 
            :variant="reactions.user_reaction == '-1' ? 'destructive' : 'ghost'"
            size="sm"
            @click="() => onVote( 'downvote' )" >
            <ThumbsDown class="h-4 w-4 mr-2"/>
            {{ reactions.downvotes }}
        </Button>
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

.reaction-count {
    font-weight: 300;
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