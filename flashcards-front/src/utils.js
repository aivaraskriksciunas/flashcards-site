import Cookies from 'js-cookie'

export const setApiCookie = ( api_key ) => {
    let domain = window.location.hostname;
    domain = domain.split( '.' ).slice( -2 ).join( '.' )

    Cookies.remove( 'api_key' )
    Cookies.set( 'api_key', api_key, { expires: 40, sameSite: 'strict', domain } );
}