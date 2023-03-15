<script setup>
import Card from '../../components/ui/Card.vue';

const { deck } = defineProps({
    deck: {
        required: true
    }
})

</script>

<template>
    <Card hover>
        <router-link :to="{ name: 'view-deck', params: { id: deck.id } }">
            <div class="deck-name">{{ deck.name }}</div>
        </router-link>
        <div class="deck-details">
            <p>Cards: {{ deck.card_count }}</p>
        </div>
    </Card>
</template>

<style scoped>

.deck-name {
    font-size: 1.5em;
    line-height: 1.3;
    color: var( --color-primary-text );
    font-weight: 500;
    margin-bottom: 8px
}

.deck-details {
    font-size: 0.9em;
    font-weight: 300;
}

</style>