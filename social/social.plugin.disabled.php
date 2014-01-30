<?php
/*
@name Social
@author Cobalt74 <cobalt74@gmail.com>
@link http://www.cobestran.com
@licence CC by nc sa http://creativecommons.org/licenses/by-nc-sa/2.0/fr/
@version 3.5.3
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
          <div class="social_divbut" id="maindiv'.$eventId.'" onclick="social_toggle_div(\'maindiv'.$eventId.'\',\''.$eventId.'\');">+ Partager</div>
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
	echo '<li><a class="toggle" href="#socialBloc">Plugin Social</a></li>';
}

function social_plugin_setting_bloc(&$myUser){
	$configurationManager = new Configuration();
	$configurationManager->getAll();
	echo '
	<section id="socialBloc" class="socialBloc" style="display:none;">
		<form action="action.php?action=social_update" method="POST">
		<h2>Plugin Social</h2>

		<section class="preferenceBloc">
		<h3>Activation des boutons de partage</h3>
		<p>
		<label for="social_twitter_link">Partage sur Twitter :</label> 
		<input type="radio" '.($configurationManager->get('plugin_social_twitter')?'checked="checked"':'').' value=1 id="socialTwitterYes" name="socialTwitter"><label>Oui</label>
		<input type="radio" '.($configurationManager->get('plugin_social_twitter')?'':'checked="checked"').' value=0 id="socialTwitterNo" name="socialTwitter"><label>Non</label>
		</p>
		<p>
		<label for="social_googleplus_link">Partage sur Google+ :</label>
		<input type="radio" '.($configurationManager->get('plugin_social_googleplus')?'checked="checked"':'').' value=1 id="socialGooglePlusYes" name="socialGooglePlus"><label>Oui</label>
		<input type="radio" '.($configurationManager->get('plugin_social_googleplus')?'':'checked="checked"').' value=0 id="socialGooglePlusNo" name="socialGooglePlus"><label>Non</label>
		</p>
		<p>
		<label for="social_facebook_link">Partage sur Facebook :</label> 
		<input type="radio" '.($configurationManager->get('plugin_social_facebook')?'checked="checked"':'').' value=1 id="socialFacebookYes" name="socialFacebook"><label>Oui</label>
		<input type="radio" '.($configurationManager->get('plugin_social_facebook')?'':'checked="checked"').' value=0 id="socialFacebookNo" name="socialFacebook"><label>Non</label>
		</p>
		<p>
		<label for="social_facebook_link">Partage sur Delicious :</label> 
		<input type="radio" '.($configurationManager->get('plugin_social_delicious')?'checked="checked"':'').' value=1 id="socialDeliciousYes" name="socialDelicious"><label>Oui</label>
		<input type="radio" '.($configurationManager->get('plugin_social_delicious')?'':'checked="checked"').' value=0 id="socialDeliciouskNo" name="socialDelicious"><label>Non</label>
		</p>
		<p>
		<label for="social_pocket">Partage sur Pocket :</label> 
		<input type="radio" '.($configurationManager->get('plugin_social_pocket')?'checked="checked"':'').' value=1 id="socialPocketYes" name="socialPocket"><label>Oui</label>
		<input type="radio" '.($configurationManager->get('plugin_social_pocket')?'':'checked="checked"').' value=0 id="socialPocketNo" name="socialPocket"><label>Non</label>
		</p>
		<p>
		<label for="social_instapaper">Partage sur Instapaper :</label> 
		<input type="radio" '.($configurationManager->get('plugin_social_instapaper')?'checked="checked"':'').' value=1 id="socialInstapaperYes" name="socialInstapaper"><label>Oui</label>
		<input type="radio" '.($configurationManager->get('plugin_social_instapaper')?'':'checked="checked"').' value=0 id="socialInstapaperNo" name="socialInstapaper"><label>Non</label>
		</p>
		<p>
		<label for="social_mail">Partage par E-mail :</label> 
		<input type="radio" '.($configurationManager->get('plugin_social_mail')?'checked="checked"':'').' value=1 id="socialMailYes" name="socialMail"><label>Oui</label>
		<input type="radio" '.($configurationManager->get('plugin_social_mail')?'':'checked="checked"').' value=0 id="socialMailNo" name="socialMail"><label>Non</label>
		</p>
		<p>
		<label for="social_linkedin">Partage sur LinkedIn :</label>
		<input type="radio" '.($configurationManager->get('plugin_social_linkedin')?'checked="checked"':'').' value=1 id="socialLinkedInYes" name="socialLinkedIn"><label>Oui</label>
		<input type="radio" '.($configurationManager->get('plugin_social_linkedin')?'':'checked="checked"').' value=0 id="socialLinkedInNo" name="socialLinkedIn"><label>Non</label>
		</p>
		</section>

		<section class="preferenceBloc">
		<h3>Paramétrages Application Shaarli</h3>
		<h4>Application PHP de partage de liens (<a href="http://sebsauvage.net/wiki/doku.php?id=php:shaarli">site web</a>)</h4>
		<p>
		<label for="social_shaarli">Partage sur Shaarli :</label> 
		<input type="radio" '.($configurationManager->get('plugin_social_shaarli')?'checked="checked"':'').' value=1 id="socialShaarliYes" name="socialShaarli"><label>Oui</label>
		<input type="radio" '.($configurationManager->get('plugin_social_shaarli')?'':'checked="checked"').' value=0 id="socialShaarliNo" name="socialShaarli"><label>Non</label>
		</p>
		<p>
		<label for="social_shaarli_link">Lien vers votre shaarli :</label> 
		<input style="width:50%;" type="text" placeholder="http://mon.domaine.com/shaarli/" value="'.$configurationManager->get('plugin_social_shaarli_link').'" id="plugin_social_shaarli_link" name="plugin_social_shaarli_link" />
		</p>
		</section>

		<section class="preferenceBloc">
		<h3>Paramétrages Application wallabag</h3>
		<h4>Application auto-hébergeable pour lire les articles plus tard (<a href="http://www.wallabag.org/">site web</a>)</h4>
		<p>
		<label for="social_wallabag">Partage sur Wallabag :</label>
		<input type="radio" '.($configurationManager->get('plugin_social_wallabag')?'checked="checked"':'').' value=1 id="socialwallabagYes" name="socialwallabag"><label>Oui</label>
		<input type="radio" '.($configurationManager->get('plugin_social_wallabag')?'':'checked="checked"').' value=0 id="socialwallabagNo" name="socialwallabag"><label>Non</label>
		</p>
		<p>
		<label for="social_wallabag_link">Lien vers votre Wallabag :</label>
		<input style="width:50%;" type="text" placeholder="http://mon.domaine.com/wallabag/" value="'.$configurationManager->get('plugin_social_wallabag_link').'" id="plugin_social_wallabag_link" name="plugin_social_wallabag_link" />
		</p>
		</section>

		<input type="submit" class="button" value="Enregistrer"><br/>
		<p>
		@Cobalt74 : Si vous souhaitez que j\'intègre de nouveau lien de partage vers des applications, je reste disponible sur le <a href=https://github.com/ldleman/Leed-market/>GitHub du projet</a>
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