<?php
/*
@name Url Clean
@author Olivier <http://j.cybride.net/olb>
@link http://j.cybride.net/olb
@licence CC by nc sa http://creativecommons.org/licenses/by-nc-sa/2.0/fr/
@version 2.2.2
@description Used to cleanup url from some crap (xtor, utm_) and use url id of RSS feed for clean Feedbrner(feedproxy), feedsportal url
*/

// validate url format to prevent entries like this "249742 at http://rue89.nouvelobs.com"
function validurl($link) {
    if(filter_var($link, FILTER_VALIDATE_URL)) return $link;
    else return null;
}

// cleanup links
function urlclean_plugin_link(&$events){
    foreach($events as $event){
        $link = $event->getLink(); // Get article link

        // handle these sites: feedproxy, feedsportal, 20minutes
        if (    preg_match('#feedproxy#',$link)
            or  preg_match('#feedsportal#',$link)
            or  preg_match('#20minutes#',$link))
        {
            $events_load = new Event();
            $event_load = $events_load->load(array('id'=>$event->getid())); // load full event
            //001 - feedproxy - recherche d'une url à reconstituer du type tag:site.org,2013-05-18:finurl
            $link_search = $event_load->getGuid();
            preg_match("#tag:([a-zA-Z.]+),[0-9a-zA-Z-]+:([0-9a-zA-Z&?=./-]+)#", $link_search, $matches);
            if (isset($matches[0])) {
                $link_search = 'http://'.$matches[1].'/'.$matches[2];
            }
            //001 - fin
            $link = validurl( $link_search ); // get article guid as link (and check its format validity)
        }

        // fallback to crawl to real url (slowest method and unsecure to privacy)
        if ($link == null && function_exists('curl_init') && !ini_get('safe_mode')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $event->getLink());
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // >>> anonimization
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch, CURLOPT_REFERER, '');
            // <<< anonimization
            $a = curl_exec($ch);
            $link = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        }

        $link = preg_replace("/[&#?]xtor=(.)+/", "", $link); // remove: xtor
        $link = preg_replace("/utm_([^&#]|(&amp;))+&*/", "", $link); // remove: utm_

        // cleanup end of url
        $link = preg_replace("/\?&/", "", $link);
        if (isset($link[strlen($link) -1])){
            if ($link[strlen($link) -1] == '?')
                $link = substr($link, 0, strlen($link) -1);
        }

        $event->setLink($link); // Set new cleaned link
    }
}

Plugin::addHook("index_post_treatment", "urlclean_plugin_link");
?>
