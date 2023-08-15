<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import SlimContainer from '../../components/ui/SlimContainer.vue';
import Card from '../../components/ui/Card.vue';
import DataLoaderWrapper from '../../components/wrappers/DataLoaderWrapper.vue';
import SimpleCardRevision from './components/SimpleCardRevision.vue';
import Button from '../../components/ui/Button.vue';
import PlainButton from '../../components/ui/PlainButton.vue';

const route = useRoute()
const router = useRouter()
const deckId = route.params.id

const data = ref({})
const dataRefreshKey = ref( 0 )
const quizItems = ref([])
const revisionState = ref('revision')

const onLoad = ( d ) => {
    data.value = d
    quizItems.value = d.items.filter( i => i.date_answered === null )
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
    <SlimContainer v-if="data.items">
        <h1>{{ data.deck.name  }}</h1>
        <div v-if="revisionState == 'revision'">
            <SimpleCardRevision :items="quizItems" @finish="() => revisionState = 'finished'"></SimpleCardRevision>
        </div>
        <div v-else>
            <h2>You have completed the revision.</h2>
            <p>Would you like to revise again?</p>
            <div class="flex flex-col">
                <Button @click="onReviseAgain">Revise again</Button>
                <PlainButton @click="onBack">Back</PlainButton>
            </div>
        </div>
    </SlimContainer>

</DataLoaderWrapper>
</template>