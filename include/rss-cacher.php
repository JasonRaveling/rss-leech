<?php
/* RSS Cacher
 *
 * Keep from angering the RSS host. Go unoticed as long as possible like any
 * good parasite would.
 *
 * We will grab the feed every 30 minutes. We hope that will sustain us without
 * angering our host.
 *
 */

function rss_cacher( $source, $feedName, $cacheTime = 1800 ) {

  if ( ! $source ) {
    return 'error: rss_cacher() expects at least two arguments. No RSS source URL provided or the URL does not exist.';
  }

  if ( ! $feedName ) {
    return 'error: rss_cacher() expects at least two arguments. No widget name provided.';
  } else {
    $cacheFile =  dirname(dirname(__FILE__)) . '/cache/' . str_replace(' ', '_', $feedName) . '.txt';
  }

  if ( ! is_numeric($cacheTime) ) {
    return 'error: rss_cacher expects cacheTime to be integer.';
  }

  if ( file_exists($cacheFile) && filemtime($cacheFile) > (time() - $cacheTime) ) {

    echo '<h4>Using cache</h4>';

  } else {

    echo '<h4>NO CACHE FOUND</h4>';
    unlink( $cacheFile );
    $feed = file_get_contents( $source );
    $feed = serialize( $feed );

    $openFile = fopen( $cacheFile, 'w' );
    fwrite( $openFile, $feed );
    fclose( $openFile );

  }

  return file_get_contents($cacheFile);

}
