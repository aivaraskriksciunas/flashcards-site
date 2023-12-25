import axios from "axios"
import { useStatusMessageService, MESSAGE_TYPE } from "../services/StatusMessageService"
import useAuthentication from "../views/auth/composables/useAuthentication"
import { useUserStore } from "../stores/user"
import { useRouter } from "vue-router"

export default function useAccountSwitcher( onSwitchCallback ) {

    const statusMessages = useStatusMessageService()
    const { setToken } = useAuthentication()
    const { refreshUserInfo } = useUserStore()
    const router = useRouter()

    const switchAccount = ( id ) => {
        axios.get( `api/account/switch/${id}` )
        .then( async ( res ) => {
            if ( res.data.token == null ) throw "Authentication error";

            setToken( res.data.token );
            await refreshUserInfo();

            if ( onSwitchCallback != null ) {
                onSwitchCallback();
            }
            else {
                router.go();
            }
        })
        .catch( () => {
            statusMessages.addStatusMessage( 
                "Authentication error", 
                "We could not switch to the chosen account. Please refresh the page and try again later.", 
                MESSAGE_TYPE.ERROR 
            )
        })
    }

    return {
        switchAccount,
    }
}