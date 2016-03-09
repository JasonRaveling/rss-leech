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

function rss_cacher( $feedURL, $feedName, $limit, $cacheTime = 2700 ) {

  $cacheDir = dirname( dirname(__FILE__) )  . '/cache';

  if ( ! $feedURL ) {
    return 'error: rss_cacher() expects at least two arguments. No RSS source URL provided or the URL does not exist.';
  }

  if ( ! $feedName ) {
    return 'error: rss_cacher() expects at least two arguments. No widget name provided.';
  } else {
    $feedName = str_replace(' ', '_', $feedName);
    $cacheFile =  $cacheDir . '/' . $feedName . '.txt';
  }

  if ( ! is_numeric($cacheTime) ) {
    return 'error: rss_cacher expects cacheTime to be integer.';
  }

  if ( ! file_exists($cacheFile) || filemtime($cacheFile) < (time() - $cacheTime) ) {

    if ( file_exists($cacheFile) ) {
      unlink( $cacheFile );
    }

    $feed = file_get_contents( $feedURL );

    // match any image URL
    preg_match_all('/([http|s]+:\/\/[^\/]+.\/[^\s]+\.(jpg|jpeg|png|gif|bmp))/', $feed, $imgMatches);

    for ($x = 0; $x <= $limit; $x++) {

      $src = $imgMatches[0][$x];

      $cacheImg = $feedName . md5($src) . '.' . pathinfo($src, PATHINFO_EXTENSION);
      $localImg = $cacheDir . '/' . $cacheImg;
      $httpImg = plugins_url('/rss-leech/cache/') . $cacheImg;

      file_put_contents($localImg, file_get_contents($src));

      $feed = str_replace($src, $httpImg, $feed);

    }

    $openFile = fopen( $cacheFile, 'w' );
    fwrite( $openFile, $feed );
    fclose( $openFile );

  }

  return file_get_contents($cacheFile);

}
