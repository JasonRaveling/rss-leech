<?php
/*
 * Post result to https://www.reddit.com/r/PHPhelp/comments/4630th/parse_rss_feed_domdocumentload_cdata_errors/
 * Give credit in file product
 */

 function lptv_parsexpath($html, $query) {
     $doc = new DOMDocument();
     @$doc->loadHTML($html);
     @$xpath = new DOMXPath($doc);
     @$elements = $xpath->query($query);

     $ret = array();
     if( !$elements ) return(array());

     foreach( $elements as $element ) {
         $ret[] = $element->textContent;
     }

     return($ret);
 }
