import { useRouter } from "vue-router"
import useAuthentication from "./useAuthentication"
import axios from 'axios'
import { MESSAGE_TYPE, useStatusMessageService } from "../../../services/StatusMessageService"
import { useAuthenticationState } from '../stores/authenticationState.js'

export default function useGoogleLogin() {

    const { onLoginSuccessCallback } = useAuthentication()
    const router = useRouter()
    const statusMessages = useStatusMessageService()
    const authState = useAuthenticationState()

    const GoogleLoginCallback = ( data ) => {
        axios.post( '/api/google-login', {
            credential: data.credential
        } )
        .then( onLoginSuccessCallback )
        .catch( 
            ( error ) => {
                if ( error.response.data?.required_action == 'link_google_account' ) {
                    authState.setStateGoogleLink( error.response.data?.email, data.credential )
                }
                else {
                    statusMessages.addStatusMessage( 
                        "Authentication error", 
                        "There was an error while authenticating you through Google. Please refresh the page and try again", 
                        MESSAGE_TYPE.ERROR 
                    )
                }
            }
        )
    }

    
    const initializeGoogleLogin = () => {
        google.accounts.id.initialize({
            client_id: import.meta.env.VITE_GOOGLE_PUBLIC_KEY,
            callback: GoogleLoginCallback
        });
    }

    return {
        initializeGoogleLogin
    }
}