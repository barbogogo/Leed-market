<?php
/*
@name Social
@author Cobalt74 <cobalt74@gmail.com>
@link http://www.cobestran.com
@licence CC by nc sa http://creativecommons.org/licenses/by-nc-sa/2.0/fr/
@version 3.6.0
@description Le plugin Social permet de partager les news avec son réseau social préféré (Twitter, Google+, Facebook, Delicious, Shaarli, Pocket, Instapaper, Mail, LinkedIn, Wallabag)
*/

function social_plugin_AddButton(&$event){
  $eventId = "social_".$event->getId();
  //$link = $event->getLink();
  
  $requete = 'SELECT link, title FROM '.MYSQL_PREFIX.'event WHERE id = '.$event->getId();
  $query = mysql_query($requete);
  $result = mysql_fetch_row($query);
  $link = $result[0];
  $title = $result[1];

  $myUser = (isset($_SESSION['currentUser'])?unserialize($_SESSION['currentUser']):false);

  $configurationManager = new Configuration();
  $configurationManager->getAll();
  echo '<div class="social_group">
          <div class="social_divbut" id="maindiv'.$eventId.'" onclick="social_toggle_div(\'maindiv'.$eventId.'\',\''.$eventId.'\');">'._t('P_SOCIAL_SHARE_PLUS').'</div>
        </div>
        <div class="social_gdiv" id="'.$eventId.'" style="display:none">
            '.($configurationManager->get('plugin_social_twitter')?'<div onclick="openURL(\'https://twitter.com/share?url='.rawurlencode($link).'&text='.rawurlencode($title).'\');social_toggle_div(\'maindiv'.$eventId.'\',\''.$eventId.'\');" class="social_div">Twitter</div>':'').'
            '.($configurationManager->get('plugin_social_googleplus')?'<div onclick="openURL(\'https://plus.google.com/share?url='.rawurlencode($link).'&hl=fr\');social_toggle_div(\'maindiv'.$eventId.'\',\''.$eventId.'\');" class="social_div">Google+</div>':'').'
            '.($configurationManager->get('plugin_social_facebook')?'<div onclick="openURL(\'http://www.facebook.com/share.php?u='.rawurlencode($link).'\');social_toggle_div(\'maindiv'.$eventId.'\',\''.$eventId.'\');" class="social_div">Facebook</div>':'').'
            '.($configurationManager->get('plugin_social_delicious')?'<div onclick="openURL(\'http://del.icio.us/post?v=5&noui&jump=close&url='.rawurlencode($link).'&title='.rawurlencode($title).'\');social_toggle_div(\'maindiv'.$eventId.'\',\''.$eventId.'\');" class="social_div">Delicous</div>':'').'
            '.($myUser!=false?($configurationManager->get('plugin_social_shaarli')?'<div onclick="openURL(\''.$configurationManager->get('plugin_social_shaarli_link').'?post='.rawurlencode($link).'&title='.rawurlencode($title).'&amp;source=bookmarklet\');social_toggle_div(\'maindiv'.$eventId.'\',\''.$eventId.'\');" class="social_div">Shaare</div>':''):'').'
            '.($configurationManager->get('plugin_social_pocket')?'<div onclick="openURL(\'https://getpocket.com/edit?url='.rawurlencode($link).'&title='.rawurlencode($title).'\');social_toggle_div(\'maindiv'.$eventId.'\',\''.$eventId.'\');" class="social_div">Pocket</div>':'').'
            '.($configurationManager->get('plugin_social_instapaper')?'<div onclick="openURL(\'http://www.instapaper.com/text?u='.rawurlencode($link).'\');social_toggle_div(\'maindiv'.$eventId.'\',\''.$eventId.'\');" class="social_div">Instapaper</div>':'').'
            '.($configurationManager->get('plugin_social_mail')?'<div onclick="openURL(\'mailto:?subject='.rawurlencode($title).'&body='.rawurlencode($link).'\');social_toggle_div(\'maindiv'.$eventId.'\',\''.$eventId.'\');" class="social_div">E-mail</div>':'').'
            '.($configurationManager->get('plugin_social_linkedin')?'<div onclick="openURL(\'http://www.linkedin.com/shareArticle?url='.rawurlencode($link).'&title='.rawurlencode($title).'\');social_toggle_div(\'maindiv'.$eventId.'\',\''.$eventId.'\');" class="social_div">LinkedIn</div>':'').'
            '.($myUser!=false?($configurationManager->get('plugin_social_wallabag')?'<div onclick="openURL(\''.$configurationManager->get('plugin_social_wallabag_link').'?action=add&url='.base64_encode($link).'\');social_toggle_div(\'maindiv'.$eventId.'\',\''.$eventId.'\');" class="social_div">Wallabag</div>':''):'').'
        </div>';
}

function social_plugin_setting_link(&$myUser){
	echo '<li><a class="toggle" href="#socialBloc">'._t('P_SOCIAL_TITLE').'</a></li>';
}

function social_plugin_setting_bloc(&$myUser){
	$configurationManager = new Configuration();
	$configurationManager->getAll();
	echo '
	<section id="socialBloc" class="socialBloc" style="display:none;">
		<form action="action.php?action=social_update" method="POST">
		<h2>'._t('P_SOCIAL_TITLE').'</h2>

		<section class="preferenceBloc">
		<h3>'._t('P_SOCIAL_PREF_TITLE').'</h3>
		<p>
		<label for="social_twitter_link">'._t('P_SOCIAL_SHARE_ON').' Twitter :</label>
		<input type="radio" '.($configurationManager->get('plugin_social_twitter')?'checked="checked"':'').' value=1 id="socialTwitterYes" name="socialTwitter"><label>'._t('P_SOCIAL_YES').'</label>
		<input type="radio" '.($configurationManager->get('plugin_social_twitter')?'':'checked="checked"').' value=0 id="socialTwitterNo" name="socialTwitter"><label>'._t('P_SOCIAL_NO').'</label>
		</p>
		<p>
		<label for="social_googleplus_link">'._t('P_SOCIAL_SHARE_ON').' Google+ :</label>
		<input type="radio" '.($configurationManager->get('plugin_social_googleplus')?'checked="checked"':'').' value=1 id="socialGooglePlusYes" name="socialGooglePlus"><label>'._t('P_SOCIAL_YES').'</label>
		<input type="radio" '.($configurationManager->get('plugin_social_googleplus')?'':'checked="checked"').' value=0 id="socialGooglePlusNo" name="socialGooglePlus"><label>'._t('P_SOCIAL_NO').'</label>
		</p>
		<p>
		<label for="social_facebook_link">'._t('P_SOCIAL_SHARE_ON').' Facebook :</label>
		<input type="radio" '.($configurationManager->get('plugin_social_facebook')?'checked="checked"':'').' value=1 id="socialFacebookYes" name="socialFacebook"><label>'._t('P_SOCIAL_YES').'</label>
		<input type="radio" '.($configurationManager->get('plugin_social_facebook')?'':'checked="checked"').' value=0 id="socialFacebookNo" name="socialFacebook"><label>'._t('P_SOCIAL_NO').'</label>
		</p>
		<p>
		<label for="social_facebook_link">'._t('P_SOCIAL_SHARE_ON').' Delicious :</label>
		<input type="radio" '.($configurationManager->get('plugin_social_delicious')?'checked="checked"':'').' value=1 id="socialDeliciousYes" name="socialDelicious"><label>'._t('P_SOCIAL_YES').'</label>
		<input type="radio" '.($configurationManager->get('plugin_social_delicious')?'':'checked="checked"').' value=0 id="socialDeliciouskNo" name="socialDelicious"><label>'._t('P_SOCIAL_NO').'</label>
		</p>
		<p>
		<label for="social_pocket">'._t('P_SOCIAL_SHARE_ON').' Pocket :</label>
		<input type="radio" '.($configurationManager->get('plugin_social_pocket')?'checked="checked"':'').' value=1 id="socialPocketYes" name="socialPocket"><label>'._t('P_SOCIAL_YES').'</label>
		<input type="radio" '.($configurationManager->get('plugin_social_pocket')?'':'checked="checked"').' value=0 id="socialPocketNo" name="socialPocket"><label>'._t('P_SOCIAL_NO').'</label>
		</p>
		<p>
		<label for="social_instapaper">'._t('P_SOCIAL_SHARE_ON').' Instapaper :</label>
		<input type="radio" '.($configurationManager->get('plugin_social_instapaper')?'checked="checked"':'').' value=1 id="socialInstapaperYes" name="socialInstapaper"><label>'._t('P_SOCIAL_YES').'</label>
		<input type="radio" '.($configurationManager->get('plugin_social_instapaper')?'':'checked="checked"').' value=0 id="socialInstapaperNo" name="socialInstapaper"><label>'._t('P_SOCIAL_NO').'</label>
		</p>
		<p>
		<label for="social_mail">'._t('P_SOCIAL_SHARE_BY').' E-mail :</label>
		<input type="radio" '.($configurationManager->get('plugin_social_mail')?'checked="checked"':'').' value=1 id="socialMailYes" name="socialMail"><label>'._t('P_SOCIAL_YES').'</label>
		<input type="radio" '.($configurationManager->get('plugin_social_mail')?'':'checked="checked"').' value=0 id="socialMailNo" name="socialMail"><label>'._t('P_SOCIAL_NO').'</label>
		</p>
		<p>
		<label for="social_linkedin">'._t('P_SOCIAL_SHARE_ON').' LinkedIn :</label>
		<input type="radio" '.($configurationManager->get('plugin_social_linkedin')?'checked="checked"':'').' value=1 id="socialLinkedInYes" name="socialLinkedIn"><label>'._t('P_SOCIAL_YES').'</label>
		<input type="radio" '.($configurationManager->get('plugin_social_linkedin')?'':'checked="checked"').' value=0 id="socialLinkedInNo" name="socialLinkedIn"><label>'._t('P_SOCIAL_NO').'</label>
		</p>
		</section>

		<section class="preferenceBloc">
		<h3>'._t('P_SOCIAL_SHARE_APPLI').' Shaarli</h3>
		<h4>'._t('P_SOCIAL_SHAARLI_DESC').'</h4>
		<p>
		<label for="social_shaarli">'._t('P_SOCIAL_SHARE_ON').' Shaarli :</label>
		<input type="radio" '.($configurationManager->get('plugin_social_shaarli')?'checked="checked"':'').' value=1 id="socialShaarliYes" name="socialShaarli"><label>'._t('P_SOCIAL_YES').'</label>
		<input type="radio" '.($configurationManager->get('plugin_social_shaarli')?'':'checked="checked"').' value=0 id="socialShaarliNo" name="socialShaarli"><label>'._t('P_SOCIAL_NO').'</label>
		</p>
		<p>
		<label for="social_shaarli_link">'._t('P_SOCIAL_LINK').' shaarli :</label>
		<input style="width:50%;" type="text" placeholder="http://mon.domaine.com/shaarli/" value="'.$configurationManager->get('plugin_social_shaarli_link').'" id="plugin_social_shaarli_link" name="plugin_social_shaarli_link" />
		</p>
		</section>

		<section class="preferenceBloc">
		<h3>'._t('P_SOCIAL_SHARE_APPLI').' wallabag</h3>
		<h4>'._t('P_SOCIAL_WALLABAG_DESC').'</h4>
		<p>
		<label for="social_wallabag">'._t('P_SOCIAL_SHARE_ON').' Wallabag :</label>
		<input type="radio" '.($configurationManager->get('plugin_social_wallabag')?'checked="checked"':'').' value=1 id="socialwallabagYes" name="socialwallabag"><label>'._t('P_SOCIAL_YES').'</label>
		<input type="radio" '.($configurationManager->get('plugin_social_wallabag')?'':'checked="checked"').' value=0 id="socialwallabagNo" name="socialwallabag"><label>'._t('P_SOCIAL_NO').'</label>
		</p>
		<p>
		<label for="social_wallabag_link">'._t('P_SOCIAL_LINK').' Wallabag :</label>
		<input style="width:50%;" type="text" placeholder="http://mon.domaine.com/wallabag/" value="'.$configurationManager->get('plugin_social_wallabag_link').'" id="plugin_social_wallabag_link" name="plugin_social_wallabag_link" />
		</p>
		</section>

		<input type="submit" class="button" value="'._t('P_SOCIAL_BTN_SAVE').'"><br/>
		<p>
		'._t('P_SOCIAL_COMMENT_AUTHOR').'
		</p>
		</form>
	</section>
	';
}

function social_plugin_update($_){
	$configurationManager = new Configuration();
	$configurationManager->getAll();

	if($_['action']=='social_update'){
		$configurationManager->put('plugin_social_twitter',$_['socialTwitter']);
		$configurationManager->put('plugin_social_googleplus',$_['socialGooglePlus']);
		$configurationManager->put('plugin_social_facebook',$_['socialFacebook']);
		$configurationManager->put('plugin_social_delicious',$_['socialDelicious']);
		$configurationManager->put('plugin_social_shaarli',$_['socialShaarli']);
		$configurationManager->put('plugin_social_shaarli_link',$_['plugin_social_shaarli_link']);
		$configurationManager->put('plugin_social_pocket',$_['socialPocket']);
		$configurationManager->put('plugin_social_instapaper',$_['socialInstapaper']);
		$configurationManager->put('plugin_social_mail',$_['socialMail']);
        $configurationManager->put('plugin_social_linkedin',$_['socialLinkedIn']);
		$configurationManager->put('plugin_social_wallabag',$_['socialwallabag']);
		$configurationManager->put('plugin_social_wallabag_link', $_['plugin_social_wallabag_link']);
		$_SESSION['configuration'] = null;

		header('location: settings.php#socialBloc');
	}
}

// Ajout de la fonction au Hook situé avant l'affichage des évenements
Plugin::addJs("/js/main.js");
Plugin::addHook("event_post_top_options", "social_plugin_AddButton");

$myUser = (isset($_SESSION['currentUser'])?unserialize($_SESSION['currentUser']):false);
if($myUser!=false) {
    Plugin::addHook('setting_post_link', 'social_plugin_setting_link');
    Plugin::addHook('setting_post_section', 'social_plugin_setting_bloc');
    Plugin::addHook("action_post_case", "social_plugin_update");
}

?>