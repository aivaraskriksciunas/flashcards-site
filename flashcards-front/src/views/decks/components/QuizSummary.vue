<script setup>
import Card from '../../../components/ui/Card.vue';
import DataLoaderWrapper from '../../../components/wrappers/DataLoaderWrapper.vue';
import { ref } from 'vue';

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
            <div class="flex-1">
                <div class="card-question">
                    {{ item.question }}
                </div>
                <div class="card-answer">
                    {{ item.answer }}
                </div>
            </div>
            <div class="summary-icon">
                <font-awesome-icon class='icon' v-if="item.is_correct" icon="fa-regular fa-circle-check"></font-awesome-icon>
                <font-awesome-icon class='icon' v-else icon="fa-regular fa-circle-xmark"></font-awesome-icon>
            </div>
        </div>
        
    </Card>
</DataLoaderWrapper>
</template>

<style>
.summary-card .card-question {
    font-size: 1.2em;
    font-weight: 500;
    color: var( --color-success );
}

.summary-card {
    border-bottom: 3px solid var( --color-success );
}

.incorrect .card-question {
    color: var( --color-danger );
}

.summary-card.incorrect {
    border-bottom: 3px solid var( --color-danger );
}

.summary-icon {
    font-size: 2em;
    display: flex;
    align-items: center;
}

.summary-icon .icon path {
    fill: var( --color-success );
}

.incorrect .summary-icon .icon path {
    fill: var( --color-danger );
}
</style>