import axios from 'axios'

export const reportQuizItemProgress = ( quizItemId, isCorrect ) => {
    return axios.post( `/api/quiz/item/${quizItemId}/progress`, { 
        is_correct: isCorrect 
    } )
}

export const deleteDeck = ( deckId ) => {
    return axios.delete( `api/decks/${deckId}` );
}