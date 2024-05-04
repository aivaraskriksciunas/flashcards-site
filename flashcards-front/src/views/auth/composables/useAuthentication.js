import { useRoute, useRouter } from "vue-router";
import axios from 'axios';
import { setApiCookie } from "@/utils";

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
        setApiCookie( token )
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }

    return {
        setToken,
        onLoginSuccessCallback
    }
}