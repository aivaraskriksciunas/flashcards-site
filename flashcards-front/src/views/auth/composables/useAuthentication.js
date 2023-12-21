import { useRoute, useRouter } from "vue-router";
import axios from 'axios';
import jsCookie from 'js-cookie';

export default function useAuthentication() {

    const router = useRouter()
    const route = useRoute()

    const onLoginSuccessCallback = ( data ) => {
        setToken( data.token )

        if ( route.query.r ) {
            router.push({ name: 'account-picker', query: { r: route.query.r }})
        }
        else {
            router.push({ name: 'account-picker' })
        }
    }

    const setToken = ( token ) => {
        jsCookie.set( 'api_key', token, { expires: 40, sameSite: 'strict' } )
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }

    return {
        setToken,
        onLoginSuccessCallback
    }
}