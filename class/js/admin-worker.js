/**
 * Worker creates a modern and stylish WPBakery addon to display business hours.
 * Exclusively on Envato Market: https://1.envato.market/worker-wpbakery
 *
 * @encoding        UTF-8
 * @version         1.0.3
 * @copyright       Copyright (C) 2018 - 2021 Merkulove ( https://merkulov.design/ ). All rights reserved.
 * @license         Envato License https://1.envato.market/KYbje
 * @contributors    {{contributors}}
 * @support         dmitry@merkulov.design
 **/

!function( $ ) {

    "use strict";

    $( document ).ready( function() {

        $( '.mdp_date_time_field' ).each( function( index ) {

            let config = $( this ).attr( 'config' );
            config = jQuery.parseJSON( config );

            $( this ).flatpickr( config );

        } );

        /** Dynamic initialisation. */
        $( document ).on( 'mouseenter', '.mdp_date_time_field', function ( e ) {

            /** This input already initialise. */
            if ( $( this ).hasClass( 'flatpickr-input' ) ) {
                return;
            }

            let config = $( this ).attr( 'config' );
            config = jQuery.parseJSON( config );

            $( this ).flatpickr( config );

        } );

    } );

}( window.jQuery );

