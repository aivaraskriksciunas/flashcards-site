import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useUserStore = defineStore( 'user', () => {
    const user = ref( null )
    const isLoggedIn = computed( () => user.value != null )

    const setCurrentUser = ( u ) => {
        user.value = u
    }

    const logout = () => {
        user.value = null
    }

    return { user, isLoggedIn, setCurrentUser, logout }
} )