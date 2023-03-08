<script setup>

const { deck } = defineProps({
    deck: {
        required: true
    }
})

</script>

<template>
    <div class="deck-list-item">
        <router-link :to="{ name: 'edit-deck', params: { id: deck.id } }">
            <div class="deck-name">{{ deck.name }}</div>
        </router-link>
        <div class="deck-details">
            <p>Cards: {{ deck.card_count }}</p>
        </div>
    </div>
</template>

<style scoped>

.deck-list-item {
    padding: 20px;
    border-radius: 10px;
    background-color: var( --color-white );
    box-shadow: 3px 3px 0 var( --color-shadow );
}

.deck-list-item:hover {
    box-shadow: none;
    background-color: var( --color-shadow );
}

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