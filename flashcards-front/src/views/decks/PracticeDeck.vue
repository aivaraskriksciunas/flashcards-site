<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import SlimContainer from '../../components/ui/SlimContainer.vue';
import DataLoaderWrapper from '../../components/wrappers/DataLoaderWrapper.vue';
import WritingCardRevision from './components/WritingCardRevision.vue';
import SimpleCardRevision from './components/SimpleCardRevision.vue';
import Button from '../../components/ui/Button.vue';
import PlainButton from '../../components/ui/PlainButton.vue';
import QuizSummary from './components/QuizSummary.vue';

const route = useRoute()
const router = useRouter()
const deckId = route.params.id

const quiz = ref([])
const quizItems = ref([])
const revisionState = ref('revision')
const dataRefreshKey = ref( 0 )

const onLoad = ( data ) => {
    quiz.value = data
    quizItems.value = data.items.filter( i => i.date_answered === null )
}

const onReviseAgain = () => {
    // Force page refresh
    dataRefreshKey.value++;
    revisionState.value = 'revision';
}


const onBack = () => {
    router.push({ name: 'view-deck', params: { id: deckId } })
}

</script>

<template>
<DataLoaderWrapper :url="`/api/decks/${deckId}/quiz`" @load="onLoad" :key="dataRefreshKey">
    <SlimContainer v-if="quiz.items">
        <h1>{{ quiz.deck.name  }}</h1>
        <div v-if="revisionState == 'revision'">
            <div v-if="route.name == 'practice-deck'">
                <WritingCardRevision :items="quizItems" @finish="() => revisionState = 'finished'"></WritingCardRevision>
            </div>
            <div v-else>
                <SimpleCardRevision :items="quizItems" @finish="() => revisionState = 'finished'"></SimpleCardRevision>
            </div>
        </div>
        <div v-else>
            <h2>You have completed the revision.</h2>
            <div class="flex">
                <Button @click="onReviseAgain" class="mr-3">Revise again</Button>
                <PlainButton @click="onBack">Back</PlainButton>
            </div>
            <QuizSummary :quiz="quiz"></QuizSummary>
        </div>
    </SlimContainer>

</DataLoaderWrapper>
</template>