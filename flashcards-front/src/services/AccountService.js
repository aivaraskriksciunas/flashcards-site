import axios from "axios"

export const setAccountType = ( userType ) => {
    return axios.patch( `/api/accounts/set-type`, { type: userType } )
}