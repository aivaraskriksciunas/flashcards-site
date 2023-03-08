import { defineStore } from 'pinia'
import { ref } from 'vue'

const AVAILABLE_THEMES = [ 'light', 'dark' ];

export const useUserSettingStore = defineStore( 'user-settings', () => {
    const colorTheme = ref( 'light' )

    const setColorTheme = ( theme ) => {
        if ( theme in AVAILABLE_THEMES ) {
            colorTheme.value = theme 
        }
        else {
            colorTheme.value = 'light'
        }
    }

    const toggleColorTheme = () => {
        if ( colorTheme.value == 'dark' ) colorTheme.value = 'light';
        else colorTheme.value = 'dark';
    }

    return { colorTheme, setColorTheme, toggleColorTheme }
} )