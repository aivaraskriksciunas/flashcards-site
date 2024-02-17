<script setup>
import { ref } from 'vue';
import FlashcardDisplay from '../FlashcardDisplay.vue';
import FlashcardContent from '../FlashcardContent.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
    isRevealed: {
        type: Boolean,
        required: true,
    }
})

const emit = defineEmits([ 'correct', 'incorrect', 'reveal' ])

</script>

<template>

    <div v-if="!props.isRevealed">    
        <FlashcardDisplay @click="emit( 'reveal' )">
            <FlashcardContent :text="card.question" :type="card.question_type"/>
        </FlashcardDisplay>

        <div class="flex mt-3">
            <Button variant='secondary' class="w-full" @click="emit( 'reveal' )">Flip card</Button>
        </div>
    </div>

    <div v-else>
        <FlashcardDisplay>
            <FlashcardContent :text="card.question" :type="card.question_type"/>
            <Separator class="my-2"/>
            <FlashcardContent :text="card.answer" :type="card.answer_type"/>
            
            <div class="card-comment mt-8 text-muted-foreground">{{ card.comment }}</div>
        </FlashcardDisplay>
        <div class="flex mt-3">
            <Button variant='pill' size="lg" class="flex-1 mr-1 bg-destructive hover:bg-destructive/80" @click="emit( 'incorrect' )">Need more practice</Button>
            <Button variant="pill" size="lg" class="flex-1 ml-1" @click="emit( 'correct' )">I know this</Button>
        </div>
    </div>
</template>