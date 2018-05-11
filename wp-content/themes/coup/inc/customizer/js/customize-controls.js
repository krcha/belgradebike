( function( $ ) { 'use strict';

    $( document ).ready( function() {

        // Checkbox Multiple Control
        $( document ).on( 'change', '.multiple-checkbox-input', function() {

                var checkbox_values = $( this ).closest( '.customize-control' ).find( ':checked' ).map(
                    function() {
                        return this.value;
                    }
                ).get().join( ',' );

                $( this ).closest( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );

            }
        );

    } ); // $( document ).ready

    $( window ).load( function(){
        $( '#my-checklist' ).sortable({
            handle: '.my-handle',
            axis: 'y',
            update: function( e, ui ){
                $('.hello-check').trigger( 'change' );
            }
        });

        // Sorting Front Page sections order
        $( document ).on( 'change', '.hello-check', function() {
            var checkbox_values = $( this ).parents( '#my-checklist' ).find( 'input.hello-check' ).map( function() {
                var newvalue = '0';
                if( $(this).prop("checked") ){
                    var newvalue = '1';
                }
                return this.name + ';' + newvalue;
            }).get().join( ',' );

            $( '.fillme' ).val( checkbox_values ).trigger( 'change' );
        });

    } );

} ) ( jQuery );

