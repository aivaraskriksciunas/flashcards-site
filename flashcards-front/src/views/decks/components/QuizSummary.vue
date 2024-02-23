<script setup>
import Card from '../../../components/ui/Card.vue';
import DataLoaderWrapper from '../../../components/wrappers/DataLoaderWrapper.vue';
import { ref } from 'vue';
import { CheckCircle, XCircle } from 'lucide-vue-next';

const props = defineProps({
    quiz: {
        required: true,
    }
});

const summary = ref( null );
const onLoad = ( data ) => {
    summary.value = data
}

</script>

<template>
<DataLoaderWrapper :url="`/api/quiz/${props.quiz.id}`" @load="onLoad">
    <h2>Summary {{ summary.correct_count }}/{{ summary.total_cards }}</h2>
    <Card v-for="item in summary.items" :key="item.card_id" class="summary-card mb-3" :class="{ 'incorrect': !item.is_correct }">
        <div class="flex">
            <div class="flex-1 pr-2">
                <div class="card-question">
                    {{ item.question }}
                </div>
                <div class="card-answer">
                    {{ item.answer }}
                </div>
                <div class="card-comment">
                    {{ item.comment }}
                </div>
            </div>
            <div class="summary-icon">
                <CheckCircle v-if="item.is_correct" />
                <XCircle v-else />
            </div>
        </div>
        
    </Card>
</DataLoaderWrapper>
</template>

<style>
.summary-card .card-question {
    font-size: 1.2em;
    font-weight: 500;
    color: rgb( var( --primary ) );
}

.summary-card .card-comment {
    font-size: 0.9em;
    color: rgb( var( --muted-foreground ) );
}

.summary-card {
    border-bottom: 3px solid rgb( var( --primary ) );
}

.incorrect .card-question {
    color: rgb( var( --destructive ) );
}

.summary-card.incorrect {
    border-bottom: 3px solid rgb( var( --destructive ) );
}

.summary-icon {
    font-size: 2em;
    display: flex;
    align-items: center;
    color: rgb( var( --primary ) );
}

.incorrect .summary-icon {
    color: rgb( var( --destructive ) );
}
</style>