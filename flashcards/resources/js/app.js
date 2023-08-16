import styles from '../css/app.scss';
import $ from 'jquery';

$( () => {
    $( '._delete_object_button' ).on( 'click', () => {
        let conf = confirm( "Are you sure you want to delete the current item?" )
    
        if ( conf ) {
            $( '._delete_object_form' ).trigger( 'submit' );
        }
    })
})
