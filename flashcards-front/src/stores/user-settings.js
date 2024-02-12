import { defineStore } from 'pinia'
import { ref } from 'vue'

const AVAILABLE_THEMES = [ 'light', 'dark' ];

export const useUserSettingStore = defineStore( 'user-settings', () => {
    const colorTheme = ref( 'light' );
    const quizModePreferences = ref({});
    const quizSizePreferences = ref({})

    try {
        quizModePreferences.value = JSON.parse( localStorage.getItem( 'quiz-modes' ) ) || {}
    }
    catch ( err ) {
        quizModePreferences.value = {}
    }

    try {
        quizSizePreferences.value = JSON.parse( localStorage.getItem( 'quiz-sizes' ) ) || {}
    }
    catch ( err ) {
        quizSizePreferences.value = {}
    }

    const setColorTheme = ( theme ) => {
        if ( AVAILABLE_THEMES.includes( theme ) ) {
            colorTheme.value = theme 
        }
        else {
            colorTheme.value = 'light'
        }

        document.body.className = 'theme-' + colorTheme.value;
        localStorage.setItem( 'theme', colorTheme.value )
    }

    // Set color theme from local storage
    setColorTheme( localStorage.getItem( 'theme' ) );

    const toggleColorTheme = () => {
        if ( colorTheme.value == 'dark' ) setColorTheme( 'light' );
        else setColorTheme( 'dark' );
    }

    const storeQuizPreferences = () => {
        localStorage.setItem( 'quiz-modes', JSON.stringify( quizModePreferences.value ) );
        localStorage.setItem( 'quiz-sizes', JSON.stringify( quizSizePreferences.value ) );
    }

    const setPreferredQuizMode = ( deckId, mode ) => {
        try {
            quizModePreferences.value[deckId] = mode
        }
        catch (err) {
            quizModePreferences.value = {}
        }

        storeQuizPreferences();
    } 
    
    const getPreferredQuizMode = ( deckId ) => {
        try {
            return quizModePreferences.value.hasOwnProperty( deckId ) 
                ? quizModePreferences.value[deckId] 
                : null
        }
        catch ( err ) {
            quizModePreferences.value = {}
            storeQuizPreferences();
        }

        return null;
    }

    const setPreferredQuizSize = ( deckId, size ) => {
        try {
            quizSizePreferences.value[deckId] = size
        }
        catch (err) {
            quizSizePreferences.value = {}
        }

        storeQuizPreferences();
    } 

    const getPreferredQuizSize = ( deckId ) => {
        try {
            return quizSizePreferences.value.hasOwnProperty( deckId ) 
                ? quizSizePreferences.value[deckId] 
                : null
        }
        catch ( err ) {
            quizSizePreferences.value = {}
            storeQuizPreferences();
        }

        return null;
    } 

    return { 
        colorTheme, 
        setColorTheme, 
        toggleColorTheme, 
        getPreferredQuizMode, 
        setPreferredQuizMode,
        setPreferredQuizSize,
        getPreferredQuizSize,
    }
} )