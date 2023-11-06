import { useRoute, useRouter } from "vue-router";
import jsCookie from 'js-cookie';

export default function useAuthentication() {

    const router = useRouter()
    const route = useRoute()

    const onLoginSuccessCallback = ( data ) => {
        jsCookie.set( 'api_key', data.token, { expires: 40, sameSite: 'strict' } )

        if ( route.query.r ) {
            router.push( route.query.r )
        }
        else {
            router.push({ name: 'home' })
        }
    }

    return {
        onLoginSuccessCallback
    }
}