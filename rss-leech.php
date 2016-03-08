<?php
/*
Plugin Name:  RSS Leech
Description:  Grabs content from selected RSS feeds and places them in a widget for the sidebar.
Plugin URI:   https://github.com/webunraveling/wp-pbs-rss-widgets
Version:      1.0
Author:       Jason Raveling
Author URI:   http://webunraveling.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html

/*
RSS Leech
Copyright (C) 2016 Jason Raveling

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

// get the stylesheet
add_action( 'wp_enqueue_scripts', 'register_rss_leech_css' );

function register_rss_leech_css() {
	wp_register_style(
    'rss-leech',
    plugins_url( '/rss-leech/include/style/css/rss-leech-style.css' ),
    array(),
    date( 'Ymd', filemtime(dirname(__FILE__) . '/include/style/css/rss-leech-style.css') )
  );
	wp_enqueue_style( 'rss-leech' );
}

// include our functions
include_once dirname(__FILE__) . '/include/rss-parser.php';
include_once dirname(__FILE__) . '/include/rss-cacher.php';

// get the widgets
$widgetFiles = glob( dirname(__FILE__) . "/widgets/*.php" );

foreach ($widgetFiles as $file) {
  include $file;
}

?>
