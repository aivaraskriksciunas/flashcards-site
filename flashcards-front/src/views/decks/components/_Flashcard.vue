<script setup>

const props = defineProps({
    card
})



</script>

<template>
    <Card>
        <h1>{{ deck.cards[0].question }}</h1>
    </Card>
</template>