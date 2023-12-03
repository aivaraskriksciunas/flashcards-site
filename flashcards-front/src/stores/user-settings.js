import { defineStore } from 'pinia'
import { ref } from 'vue'

const AVAILABLE_THEMES = [ 'light', 'dark' ];

export const useUserSettingStore = defineStore( 'user-settings', () => {
    const colorTheme = ref( 'light' );
    const quizModePreferences = ref({});
    // Set color theme from local storage, if available
    if ( AVAILABLE_THEMES.includes( localStorage.getItem( 'theme' ) ) ) {
        colorTheme.value = localStorage.getItem( 'theme' );
    }

    try {
        quizModePreferences.value = JSON.parse( localStorage.getItem( 'quiz-modes' ) ) || {}
    }
    catch ( err ) {
        quizModePreferences.value = {}
    }

    const setColorTheme = ( theme ) => {
        if ( AVAILABLE_THEMES.includes( theme ) ) {
            colorTheme.value = theme 
        }
        else {
            colorTheme.value = 'light'
        }

        localStorage.setItem( 'theme', colorTheme.value )
    }

    const toggleColorTheme = () => {
        if ( colorTheme.value == 'dark' ) setColorTheme( 'light' );
        else setColorTheme( 'dark' );
    }

    const storeQuizPreferences = () => {
        localStorage.setItem( 'quiz-modes', JSON.stringify( quizModePreferences.value ) );
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

    return { colorTheme, setColorTheme, toggleColorTheme, getPreferredQuizMode, setPreferredQuizMode }
} )