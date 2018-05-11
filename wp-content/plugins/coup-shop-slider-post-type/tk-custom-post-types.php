<?php
/*
Plugin Name: Coup Shop Slider Post Type
Plugin URI: http://www.themeskingdom.com/
Description: Simple plugin for adding custom post type
Version: 1.0
Author: Themes Kingdom
Author URI: http://www.themeskingdom.com
License: Copyright (c) 2016 Themes Kingdom. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
*/


// Define paths
if ( ! defined( 'TK_POSTTYPES_DIR' ) ) define( 'TK_POSTTYPES_DIR', plugin_dir_path( __FILE__ ) );

require( TK_POSTTYPES_DIR . '/slider-post-types.php' );  // Register Custom Post Types

