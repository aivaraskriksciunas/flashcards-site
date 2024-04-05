<script setup>
import HiddenField from '@/components/forms/HiddenField.vue'
import FlashcardTextControl from './FlashcardTextControl.vue';
import FlashcardContentTypeSelect from './FlashcardContentTypeSelect.vue';
import { ref, computed } from 'vue';
import FlashcardListControl from './FlashcardListControl.vue';
import FlashcardFormulaControl from './FlashcardFormulaControl.vue';

const props = defineProps({
    card: {
        required: true,
        type: Object,
    },
    index: {
        required: true,
        type: Number,
    },
    answerType: {
        required: false,
        type: String,
        default: 'text',
    }
})

// const questionType = ref( 'text' )
// const answerType = ref( props.card.answerType ?? 'text' )

const questionFieldValues = ref({
    text: props.card.question ?? '',
    // list: props.card.question ?? '',
    // formula: props.card.question ?? '',
})

const answerFieldValues = ref({
    text: props.card.answer ?? '',
    list: props.card.answer ?? '',
    math: props.card.answer ?? '',
})

const question = computed(() => {
    return questionFieldValues.value['text']
})
const answer = computed(() => {
    return answerFieldValues.value[props.answerType]
})

</script>

<template>

<div class="md:flex gap-2 mb-2">
    <HiddenField v-if="props.card.id" :value="props.card.id" :name="`cards.${props.index}.id`"></HiddenField>
    
    <div class="flex-1">
        <!-- <FlashcardContentTypeSelect v-model="questionType"/> -->

        <HiddenField value="text" :name="`cards.${props.index}.question_type`"></HiddenField>
        <HiddenField v-model="question" :name="`cards.${props.index}.question`"></HiddenField>

        <FlashcardTextControl 
            v-model="questionFieldValues.text"
            placeholder="Question"/>
    </div>

    <div class="flex-1">
        
        <HiddenField v-model="props.answerType" :name="`cards.${props.index}.answer_type`"></HiddenField>
        <HiddenField v-model="answer" :name="`cards.${props.index}.answer`"></HiddenField>

        <FlashcardTextControl
            v-if="props.answerType == 'text'" 
            v-model="answerFieldValues.text"
            placeholder="Answer"/>

        <FlashcardListControl
            v-else-if="props.answerType == 'list'"
            v-model="answerFieldValues.list"
            />

        <FlashcardFormulaControl
            v-else-if="props.answerType == 'math'"
            v-model="answerFieldValues.math"
            />
    </div>
</div>

</template>