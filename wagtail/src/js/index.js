import '../css/index.css';

const $ = require( 'jquery' );

$( () => {

    const mobileMenu = $( '#mobileMenu' );
    const mobileMenuCover = $( '#mobileMenuCover' )
    const mobileMenuButton = $( '#mobileMenuButton' )
    const mobileCloseButton = $( '#mobileMenuCloseButton' );

    // Hide menu on load
    mobileMenu.hide();
    mobileMenuCover.hide();
    mobileCloseButton.hide();

    const closeNavbar = () => {
        $( mobileMenu ).slideUp( 100 );
        $( mobileMenuCover ).fadeOut( 100 );
        $( mobileMenuButton ).show();
        $( mobileCloseButton ).hide();
    }

    const openNavbar = () => {
        $( mobileMenu ).slideDown( 250 );
        $( mobileMenuCover ).fadeIn( 250 );
        $( mobileMenuButton ).hide();
        $( mobileCloseButton ).show();
    }

    $( mobileMenuButton ).on( 'click', openNavbar );

    $( mobileCloseButton ).on( 'click', closeNavbar );

    $( mobileMenuCover ).on( 'click', closeNavbar );
})