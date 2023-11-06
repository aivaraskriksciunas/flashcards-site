import { defineStore } from "pinia";
import { computed, ref } from "vue";

export const AUTH_STATES = {
    LOGIN: 1,
    GOOGLE_LINK: 2,
}

export const useAuthenticationState = defineStore( 'authentication-state', () => {

    // Private
    const _authState = ref( AUTH_STATES.LOGIN );
    const _googleLinkPayload = ref({})
    
    const authState = computed( () => _authState )

    const setStateLogin = () => {
        _authState.value = AUTH_STATES.LOGIN
    }

    const setStateGoogleLink = ( email, google_credential ) => {
        _googleLinkPayload.value = {
            email, google_credential
        }
        _authState.value = AUTH_STATES.GOOGLE_LINK
    }

    const getGoogleLinkPayload = () => _googleLinkPayload.value

    return {
        authState,
        setStateLogin,
        setStateGoogleLink,
        getGoogleLinkPayload,
    }

})