<?php
/* RSS Cacher
 *
 * Keep from angering the RSS host. Go unoticed as long as possible like any
 * good parasite would.
 *
 * We will grab the feed every 45 minutes. We hope that will sustain us without
 * angering our host.
 *
 */

function rss_cacher( $source, $feedName, $items = array(), $cacheTime = 2700 ) {

  if ( ! $source ) {
    return 'error: rss_cacher() expects at least two arguments. No RSS source URL provided or the URL does not exist.';
  }

  if ( ! $feedName ) {
    return 'error: rss_cacher() expects at least two arguments. No widget name provided.';
  } else {
    $cacheFile =  dirname(dirname(__FILE__)) . '/cache/' . str_replace(' ', '_', $feedName) . '-feed.txt';
  }

  if ( ! is_numeric($cacheTime) ) {
    return 'error: rss_cacher expects cacheTime to be integer.';
  }

  if ( ! file_exists($cacheFile) || filemtime($cacheFile) < (time() - $cacheTime) ) {

    echo "GRABBING CACHE";
    if (file_exists($cacheFile) ) {
      unlink( $cacheFile );
    }

    $feed = file_get_contents( $source );

    // any url to an image
    //preg_replace('/(src=")([^\s]+\/\/[^\/]+.\/[^\s]+\.(jpg|jpeg|png|gif|bmp))(")/gi', md5(//headline) $feed)

    $openFile = fopen( $cacheFile, 'w' );
    fwrite( $openFile, $feed );
    fclose( $openFile );

  }

  return file_get_contents($cacheFile);

}
