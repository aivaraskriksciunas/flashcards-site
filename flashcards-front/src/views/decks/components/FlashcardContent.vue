<script setup>

const props = defineProps({
    card: { required: true },
    questionOnly: {
        type: Boolean,
        default: false,
    }
})

</script>

<template>
    <div v-if="!props.questionOnly">
        <div class="card-question">{{ card.question }}</div>
        <div class="divider"></div>
        <slot name="card-answer">
            <div class="card-answer">{{ card.answer }}</div>  
        </slot>
    </div>
    <div v-else>
        <div class="card-question">{{ card.question }}</div>
    </div>
</template>

<style scoped>
.card-content {
    text-align: center;
    padding-top: 1em;
}

.card-question {
    font-size: 3em;
    font-weight: 500;
}

.card-answer {
    font-size: 2em;
}

.divider {
    display: inline-block;
    background-color: var(--color-shadow);
    height: 1px;
    width: 60%;
}
</style>