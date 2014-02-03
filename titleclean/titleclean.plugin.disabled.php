<?php
/*
@name Title Clean
@author Olivier <http://olivierlebris.me>
@link http://cybride.net/olivier
@licence CC by nc sa http://creativecommons.org/licenses/by-nc-sa/2.0/fr/
@version 1.0.0
@description Used to cleanup title text just to keep textual information
*/

function filtercleanup($text='') {
    $text = preg_replace("/http(s)?:\/\/(.*?)\s/i", '',$text);
    $text = strip_tags($text);
    $text = ltrim($text);
    $text = rtrim($text);
    return $text;
}
function titleclean_plugin_link(&$events){
    foreach($events as $event){
        $title = $event->getTitle();
        $title = filtercleanup($title);
        $event->setTitle($title);
    }
}

Plugin::addHook("index_post_treatment", "titleclean_plugin_link");
?>