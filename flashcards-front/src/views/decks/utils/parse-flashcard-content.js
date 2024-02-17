
export const parseFlashcardListContent = ( json ) => {
    let items = [];

    try {
        items = JSON.parse( json )
    } catch( e ) {}

    if ( !Array.isArray( items ) ) items = [];
    
    items.splice( 5 );
    return items.map( i => i.trim() ).filter( i => i != '' )
}