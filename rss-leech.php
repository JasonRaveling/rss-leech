<?php
/*
Plugin Name:  RSS Leech
Description:  Grabs content from selected RSS feeds and places them in a widget for the sidebar.
Plugin URI:   https://github.com/webunraveling/wp-pbs-rss-widgets
Version:      0.1
Author:       Jason Raveling
Author URI:   http://webunraveling.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html

Not much happening here. Setting up this file structure to allow for future
functionality to be added without having to change file structure when/if things
become more complicated.
*/

// get the stylesheet
add_action( 'wp_enqueue_scripts', 'register_css' );

/**
 * Register style sheet.
 */
function register_css() {
	wp_register_style(
    'rss-leech',
    plugins_url( '/rss-leech/include/style/css/style.css' ),
    array(),
    date('Ymd', filemtime(plugins_url( '/rss-leech/include/style/css/style.css' )) )
  );
	wp_enqueue_style( 'rss-leech' );
}

// include rss parsing
include_once plugins_url( '/rss-leech/include/rss-parser.php' );

// Do the work
$widgetFiles = glob( plugins_url("/widgets/*.php") );

foreach ($widgetFiles as $file) {
  include $file;
}

?>
