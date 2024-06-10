import axios from 'axios'

export const createEmptyDeck = ( name ) => {
    return axios.post( '/api/decks', { name } )
}

export const reportQuizItemProgress = ( quizItemId, isCorrect ) => {
    return axios.post( `/api/quiz/item/${quizItemId}/progress`, { 
        is_correct: isCorrect 
    } )
}

export const deleteDeck = ( deckId ) => {
    return axios.delete( `api/decks/${deckId}` );
}

export const addDeckToLibrary = ( deckId ) => {
    return axios.post( `api/decks/${deckId}/add-to-library` )
}

export const copyDeck = ( deckId ) => {
    return axios.post( `api/decks/${deckId}/copy` )
}