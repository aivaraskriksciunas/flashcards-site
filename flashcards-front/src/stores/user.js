import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'

export const useUserStore = defineStore( 'user', () => {
    const user = ref( null )
    const isLoggedIn = computed( () => user.value != null )

    const setCurrentUser = ( u ) => {
        user.value = u
    }

    const logout = () => {
        user.value = null
    }

    const refreshUserInfo = async () => {
        let res = await axios.get( '/api/user' );
        setCurrentUser( res.data )
    }

    const resendVerificationCode = () => {
        if ( user.value.is_valid ) return null;

        return axios.get( '/api/resend-confirmation-email' )
    }

    return { user, isLoggedIn, setCurrentUser, refreshUserInfo, resendVerificationCode, logout }
} )