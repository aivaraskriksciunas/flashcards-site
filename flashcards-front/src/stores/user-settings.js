import { defineStore } from 'pinia'
import { ref } from 'vue'

const AVAILABLE_THEMES = [ 'light', 'dark' ];

export const useUserSettingStore = defineStore( 'user-settings', () => {
    const colorTheme = ref( 'light' )
    // Set color theme from local storage, if available
    if ( AVAILABLE_THEMES.includes( localStorage.getItem( 'theme' ) ) ) {
        colorTheme.value = localStorage.getItem( 'theme' );
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

    return { colorTheme, setColorTheme, toggleColorTheme }
} )