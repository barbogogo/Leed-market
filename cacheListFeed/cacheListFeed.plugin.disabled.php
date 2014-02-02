<?php
/*
@name cacheListFeed
@author Cobalt74 <cobalt74@gmail.com>
@link http://www.cobestran.com
@licence CC by nc sa http://creativecommons.org/licenses/by-nc-sa/2.0/fr/
@version 2.1.0
@description Le plugin cacheListFeed permet de cacher la liste des feed afin de lire les news en plein écran.
*/

function cacheListFeed_plugin_AddButton(&$event){
	echo '<div class="cacheListFeed_divbut" title="'._t('P_CACHELISTFEED_TITLE').'" onclick="cacheListFeed_toggle_div();">></div>';
}

function cacheListFeed_plugin_action (&$_) {
	if ($_['action']=='cacheListFeed'){
		$myUser = (isset($_SESSION['currentUser'])?unserialize($_SESSION['currentUser']):false);
		if($myUser==false) exit();
		$configurationManager = new Configuration();
  		$configurationManager->getAll();
		$configurationManager->put('cacheListFeed', $_['cacher']);
	}
	if ($_['action']=='getCacheListFeed'){
		$myUser = (isset($_SESSION['currentUser'])?unserialize($_SESSION['currentUser']):false);
		if($myUser==false) exit();
		$configurationManager = new Configuration();
  		$configurationManager->getAll();
		echo $configurationManager->get('cacheListFeed');
	}
}

// Ajout de la fonction au Hook situé avant l'affichage des évenements
Plugin::addJs("/js/main.js");
Plugin::addHook("menu_pre_folder_menu", "cacheListFeed_plugin_AddButton");
Plugin::addHook("action_post_case", "cacheListFeed_plugin_action");
?>
