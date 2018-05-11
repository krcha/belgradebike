<?php
/**
 * Customizer sanitization functions
 *
 * @package coup
 */

/**
 * Sanitize checkbox
 */
function coup_sanitize_checkbox( $checkbox ) {
    if ( $checkbox ) {
        $checkbox = 1;
    } else {
        $checkbox = false;
    }
    return $checkbox;
}

/**
 * Sanitize selection.
 */
function coup_sanitize_select( $selection ) {
    return $selection;
}

/**
 * Sanitize the dropdown pages.
 *
 * @param interger $input.
 * @return interger.
 */
function coup_sanitize_dropdown_pages( $input ) {
    if ( is_numeric( $input ) ) {
        return intval( $input );
    }
}

/**
 * Sanitize text
 */
function coup_sanitize_text( $text ) {
    return esc_html( $text );
}

/**
 * Sanitize colors
 */
function coup_sanitize_color( $hex, $default = '' ) {
    if ( coup_of_validate_hex( $hex ) ) {
        return $hex;
    }
    return $default;
}
function coup_of_validate_hex( $hex ) {
    $hex = trim( $hex );
    /* Strip recognized prefixes. */
    if ( 0 === strpos( $hex, '#' ) ) {
        $hex = substr( $hex, 1 );
    }
    elseif ( 0 === strpos( $hex, '%23' ) ) {
        $hex = substr( $hex, 3 );
    }
    /* Regex match. */
    if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
        return false;
    }
    else {
        return true;
    }
}

/**
 * Sanitize blog layout radio inputs
 */
function coup_sanitize_blog_layout( $selection ) {
	if ( !in_array( $selection, array( 'standard-layout', 'shuffle-layout' ) ) ) {
		$selection = 'standard-layout';
	} else {
		return $selection;
	}
}

/**
 * Sanitize shop layout radio inputs
 */
function coup_sanitize_shop_layout( $selection ) {
    if ( !in_array( $selection, array( 'layout-5', 'layout-4', 'layout-3', 'layout-2' ) ) ) {
        $selection = 'layout-4';
    } else {
        return $selection;
    }
}
