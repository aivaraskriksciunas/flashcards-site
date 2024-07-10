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

    const userAccounts = computed(() => {
        if ( user.value == null || user.value.accounts == null ) return [];
        
        return user.value.accounts;
    })

    // User levels
    const isStudent = () => {
        if ( !isLoggedIn.value ) return false;
        return user.value.account_type === 'student';
    }

    const isOrgAdmin = () => {
        if ( !isLoggedIn.value ) return false;
        return user.value.account_type === 'orgadmin';
    }

    const isOrgManager = () => {
        if ( !isLoggedIn.value ) return false;
        return user.value.account_type === 'orgmanager' || user.value.account_type === 'orgadmin';
    }

    const isAdmin = () => {
        if ( !isLoggedIn.value ) return false;
        return user.value.account_type === 'admin';
    }

    const isUndefinedAccountType = () => {
        if ( !isLoggedIn.value ) return false;
        return user.value.account_type === 'undefined';
    }

    return { 
        user, 
        isLoggedIn, 
        setCurrentUser, 
        refreshUserInfo, 
        resendVerificationCode, 
        logout, 
        userAccounts,
        isStudent,
        isOrgAdmin,
        isAdmin,
        isOrgManager,
        isUndefinedAccountType,
    }
} )