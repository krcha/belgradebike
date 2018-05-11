(function($) { 'use strict';

	function retreive_font_weight( font_type ) {

		var font_selector = $( '#customize-control-' + font_type + '_font_family select' ),
			font_selected = font_selector.val(),
			weight_select;

		if ( 'default' != font_selected ) {

    		$.ajax({
	            type: 'POST',
	            url: js_vars.admin_url,
	            dataType: 'json',
	            data: {
	            	action: 'coup_generate_font_weight',
	            	selected_font: font_selected
	            },
	            success: function( response ) {

	            	var result = eval( response ),
	            	    select_options = '';

					for ( var index in result ) {

						var selected_variant            = '',
							headings_selected_variant   = js_vars.headings_font_variant,
							paragraphs_selected_variant       = js_vars.paragraphs_font_variant,
							navigation_selected_variant       = js_vars.navigation_font_variant;

						switch ( font_type ) {
							case 'headings' :
								if ( result[index] == headings_selected_variant ) {
									selected_variant = 'selected="selected"';
								}
								break;
							case 'paragraphs' :
								if ( result[index] == paragraphs_selected_variant ) {
									selected_variant = 'selected="selected"';
								}
								break;
							case 'navigation' :
								if ( result[index] == navigation_selected_variant ) {
									selected_variant = 'selected="selected"';
								}
								break;
							default :
								selected_variant = '';
						}

						select_options += '<option value="' + result[index] + '" ' + selected_variant + '>' + result[index] + '</option>';
					}

					weight_select = $( '#customize-control-' + font_type + '_font_weight select' );
					weight_select.empty();
					weight_select.append( select_options );

	            }

	       	} );

       	} else {
       		weight_select = $( '#customize-control-' + font_type + '_font_weight select' );
       		weight_select.empty();
       		weight_select.append( '<option value="default">' + js_vars.default_text + '</option>' );
       	}

	}

    $(window).load(function(){

    	/**
    	 * On load set selected font family and weight
    	 */
    	retreive_font_weight( 'headings' );
		retreive_font_weight( 'paragraphs' );
		retreive_font_weight( 'navigation' );

    	/**
    	 * Select font and generate weight for it
    	 */
    	var headings_font_select   = $( '#customize-control-headings_font_family select' ),
	    	paragraphs_font_select = $( '#customize-control-paragraphs_font_family select' ),
	    	navigation_font_select = $( '#customize-control-navigation_font_family select' );

    	headings_font_select.on( 'change', function(){
    		retreive_font_weight( 'headings' );
    	} );

    	paragraphs_font_select.on( 'change', function(){
    		retreive_font_weight( 'paragraphs' );
    	} );

    	navigation_font_select.on( 'change', function(){
    		retreive_font_weight( 'navigation' );
    	} );

    } ); // End Document Ready

} )(jQuery);
