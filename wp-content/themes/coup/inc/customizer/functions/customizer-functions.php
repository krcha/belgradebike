<?php
/**
 * Customizer specific functions
 *
 * @package coup
 */

// Update Google Fonts list
function coup_list_google_fonts() {

    $api_key = 'AIzaSyAbxRzjvB6WVfk0OLWAgOAKXcxp8sNF9A4';

    if ( false === get_transient( 'google_fonts_json' ) ) {
        $google_font_url  = 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . $api_key;
        $google_font_list = wp_remote_get( $google_font_url );
        $google_font_list = $google_font_list['body'];

        $expiration = 60 * 60 * 24; // 24 Hours

        set_transient( 'google_fonts_json', $google_font_list, $expiration );

        $webfonts_array = $google_font_list;
    } else {
        $webfonts_array = get_transient( 'google_fonts_json' );
    }

    $list_fonts            = array(); // 1
    $list_fonts_decoded    = json_decode( $webfonts_array, true );
    $list_fonts['default'] = esc_html__( 'Theme default', 'coup-shop' );

    foreach ( $list_fonts_decoded['items'] as $key => $value ) {
        $item_family              = $list_fonts_decoded['items'][$key]['family'];
        $list_fonts[$item_family] = $item_family;
    }

    return $list_fonts;

}

// Generate font weight for selected font family
function coup_generate_font_weight() {

    $font_familly                 = $_POST['selected_font'];
    $list_font_weights            = array(); // 2
    $webfonts                     = get_transient( 'google_fonts_json' );
    $list_fonts_decode            = json_decode( $webfonts, true );
    $list_font_weights['normal'] = esc_html__( 'Theme default', 'coup-shop' );

    foreach ( $list_fonts_decode['items'] as $key => $value ) {
        $item_family                     = $list_fonts_decode['items'][$key]['family'];
        $list_font_weights[$item_family] = $list_fonts_decode['items'][$key]['variants'];
    }

    if ( array_key_exists( $font_familly, $list_font_weights ) ) {
        echo json_encode( $list_font_weights[$font_familly] );
    }

    set_theme_mod( 'headings_font_weight', '' );
    set_theme_mod( 'paragraphs_font_weight', '' );
    set_theme_mod( 'navigation_font_weight', '' );

    die();

}
add_action( 'wp_ajax_nopriv_coup_generate_font_weight', 'coup_generate_font_weight' );
add_action( 'wp_ajax_coup_generate_font_weight', 'coup_generate_font_weight' );

/**
 * Generate divider to use in Customizer page
 */
if ( class_exists( 'WP_Customize_Control' ) ) :

    class WP_Customize_Divider_Control extends WP_Customize_Control {
        public $type = 'divider';

        public function render_content() {
            ?>
            <div class="customizer-divider"></div>
            <?php
        }
    }

endif;

// List 1 - 10
function coup_number_of_slides() {

    $results = array();

    for ( $i=1; $i <= 10; $i++ ) {
        $results[ $i ] = $i;
    }

    return $results;

}

// List all categories in dropdown
function coup_get_categories_select() {

    $teh_cats = get_categories();
    $results = array();

    $count = count( $teh_cats );
    $results['default'] = esc_html__( '-- Select --', 'coup-shop' );

    if ( !empty( $teh_cats ) ) {
        for ( $i=0; $i < $count; $i++ ) {
            if ( isset( $teh_cats[$i] ) )
                $results[$teh_cats[$i]->slug] = $teh_cats[$i]->name;
            else
                $count++;
        }
        return $results;
    }

}

// List all categories in dropdown
function coup_get_slider_categories_select() {

    if ( taxonomy_exists( 'ct_slider' ) ) {
        $teh_cats           = get_terms( 'ct_slider' );
        $results            = '';
        $count              = count( $teh_cats );
        $results['default'] = esc_html__( '-- Select --', 'coup-shop' );

        if ( !empty( $teh_cats ) ) {
            for ( $i=0; $i < $count; $i++ ) {
                if ( isset( $teh_cats[$i] ) )
                    $results[$teh_cats[$i]->slug] = $teh_cats[$i]->name;
                else
                    $count++;
            }
            return $results;
        }
    }

}
