<?php
/*
@name z_cssLeedMaker
@author Cobalt74 <cobalt74@gmail.com>
@link http://www.cobestran.com
@licence CC by nc sa http://creativecommons.org/licenses/by-nc-sa/2.0/fr/
@version 1.1.0
@description Ce plugin permet de contruire son propre thème en ajoutant du css. 
*/

function zcssleedmaker_plugin_setting_link(&$myUser){
	echo '<li><a class="toggle" href="#CSSLeedMaker">'._t('P_CSSLEEDMAKER_TITLE').'</a></li>';
}

function zcssleedmaker_plugin_setting_bloc(&$myUser){
	$configurationManager = new Configuration();
	$configurationManager->getAll();
	$filescss=getCSSMaker();
	$filecss=$configurationManager->get('plugin_cssLeedMaker_css');
	echo '
	<section id="CSSLeedMaker" class="CSSLeedMaker" style="display:none;">
		<form action="action.php?action=zcssleedmaker_update" method="POST">
		<h2>'._t('P_CSSLEEDMAKER_TITLE').'</h2>

		<section class="preferenceBloc">
			<h3>'._t('P_CSSLEEDMAKER_DEFAULT_FILE').'</h3>
			<select name="plugin_cssLeedMaker_css" id="plugin_cssLeedMaker_css">
				<option value="none">'._t('P_CSSLEEDMAKER_NONE').'</option>';
				foreach($filescss as $file){
                    if ($file!=".htaccess"&&$file!="@eadir"&&$file!=".DS_Store") {
                        if ($file==$filecss) {
                            echo '<option selected="selected" value="'.$file.'">'.$file.'</option>';
                        } else {
                            echo '<option value="'.$file.'">'.$file.'</option>';
                        }
                    }
				}
	echo '	</select>
			<h3>'._t('P_CSSLEEDMAKER_ADD_CSS_PERSO').'</h3>
			<textarea name="plugin_cssLeedMaker_addcss" rows=20 cols=40 wrap=physical>'.rawurldecode($configurationManager->get('plugin_cssLeedMaker_addcss')).'</textarea>
		</section>
		<input type="submit" class="button" value="'._t('P_CSSLEEDMAKER_BTN_SAVE').'">
		</form>
		
		<p>'._t('P_CSSLEEDMAKER_COMMENT_AUTHOR').'</p>
	</section>
	';
}

function getCSSMaker(){
	$cssDir = './' . Plugin::path() . 'cssmaker/';
	$cssFiles = scandir($cssDir);
	foreach($cssFiles as $cssFile){
	    if(is_file($cssDir.$cssFile) && !in_array($cssFile,array(".","..","@")) ){
	        $cssTab[]=$cssFile;
	    }
	}
	isset($cssTab)?sort($cssTab):$cssTab = false;
	return $cssTab;
}

function zcssleedmaker_plugin_update($_){
	$configurationManager = new Configuration();
	$configurationManager->getAll();

	if($_['action']=='zcssleedmaker_update'){
		$configurationManager->put('plugin_cssLeedMaker_css',$_['plugin_cssLeedMaker_css']);
		$configurationManager->put('plugin_cssLeedMaker_addcss',rawurlencode($_['plugin_cssLeedMaker_addcss']));
		$_SESSION['configuration'] = null;

		header('location: settings.php#CSSLeedMaker');
	}
}

// Ajout de la fonction au Hook situé avant l'affichage des évenements
$configurationManager = new Configuration();
$configurationManager->getAll();
$cssfile = $configurationManager->get('plugin_cssLeedMaker_css');
if ($cssfile!='none') { Plugin::addCss('/cssmaker/'.$cssfile); }
Plugin::addCss('/style.php');
$myUser = (isset($_SESSION['currentUser'])?unserialize($_SESSION['currentUser']):false);
if($myUser!=false) {
    Plugin::addHook('setting_post_link', 'zcssleedmaker_plugin_setting_link');
    Plugin::addHook('setting_post_section', 'zcssleedmaker_plugin_setting_bloc');
    Plugin::addHook("action_post_case", "zcssleedmaker_plugin_update");
}
?>
