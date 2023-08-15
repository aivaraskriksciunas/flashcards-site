import { ref, computed } from 'vue'
import { reportQuizItemProgress } from '../../../services/DeckProgressService'

export default function useCardRevision( quizItems, onFinish ) {

    const currentItemIndex = ref( 0 )
    const isRevealed = ref( false )
    const currentItem = computed( () => quizItems[currentItemIndex.value] )

    const nextItem = () => {
        if ( currentItemIndex.value >= quizItems.length - 1 ) {
            onFinish();
            return;
        }
    
        currentItemIndex.value += 1;
        isRevealed.value = false;
    }

    const revealItem = () => {
        isRevealed.value = true;
    }
    
    const onCorrect = () => {
        reportQuizItemProgress( quizItems[currentItemIndex.value].id, true )
        nextItem()
    }
    
    const onIncorrect = () => {
        reportQuizItemProgress( quizItems[currentItemIndex.value].id, false )
        nextItem()
    }

    return { 
        isRevealed,
        currentItem,
        nextItem,
        onCorrect,
        onIncorrect,
        revealItem,
    }
}