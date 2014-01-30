<?php
/*
@name leedHomeLink
@author Cobalt74 <cobalt74@gmail.com>
@link http://www.cobestran.com
@licence CC by nc sa http://creativecommons.org/licenses/by-nc-sa/2.0/fr/
@version 1.0.1
@description Ce plugin permet d'ajouter un menu surgissant afin d'accéder directement à des liens externes (blog, applications)
*/

function leedHomeLink_plugin_setting_link(&$myUser){
	echo '<li><a class="toggle" href="#leedHomeLink">Plugin Leed Home Link</a></li>';
}

function leedHomeLink_plugin_homelink() {
	$configurationManager = new Configuration();
	$configurationManager->getAll();
	
	echo '<a class="popmenu_title" href="#">menu</a>
		  <div class="popmenu">';
	
	if ($configurationManager->get('plugin_leedHomeLink_1_name')!='') echo'<a class="more" href="'.$configurationManager->get('plugin_leedHomeLink_1_link').'">'.$configurationManager->get('plugin_leedHomeLink_1_name').'</a><br />';
	if ($configurationManager->get('plugin_leedHomeLink_2_name')!='') echo'<a class="more" href="'.$configurationManager->get('plugin_leedHomeLink_2_link').'">'.$configurationManager->get('plugin_leedHomeLink_2_name').'</a><br />';
	if ($configurationManager->get('plugin_leedHomeLink_3_name')!='') echo'<a class="more" href="'.$configurationManager->get('plugin_leedHomeLink_3_link').'">'.$configurationManager->get('plugin_leedHomeLink_3_name').'</a><br />';
	if ($configurationManager->get('plugin_leedHomeLink_4_name')!='') echo'<a class="more" href="'.$configurationManager->get('plugin_leedHomeLink_4_link').'">'.$configurationManager->get('plugin_leedHomeLink_4_name').'</a><br />';
	if ($configurationManager->get('plugin_leedHomeLink_5_name')!='') echo'<a class="more" href="'.$configurationManager->get('plugin_leedHomeLink_5_link').'">'.$configurationManager->get('plugin_leedHomeLink_5_name').'</a><br />';
	
	echo  '</div>';
}

function leedHomeLink_plugin_setting_bloc(&$myUser){
	$configurationManager = new Configuration();
	$configurationManager->getAll();
	
	echo '
	<section id="leedHomeLink" class="leedHomeLink" style="display:none;">
		<form action="action.php?action=leedHomeLink_update" method="POST">
		<h2>Plugin Leed Home Link</h2>

		<section class="preferenceBloc">
			<h3>Ajouter des liens à votre menu</h3>
			<p>
				<label for="leedHomeLink_1">Lien 1 :</label> 
				<input style="width:50%;" type="text" placeholder="Blog" value="'.$configurationManager->get('plugin_leedHomeLink_1_name').'" id="plugin_leedHomeLink_1_name" name="plugin_leedHomeLink_1_name" />
				<input style="width:60%;" type="text" placeholder="http://mon.domaine.com/blog/" value="'.$configurationManager->get('plugin_leedHomeLink_1_link').'" id="plugin_leedHomeLink_1_link" name="plugin_leedHomeLink_1_link" />
			</p>
			<p>
				<label for="leedHomeLink_1">Lien 2 :</label> 
				<input style="width:50%;" type="text" placeholder="Shaarli" value="'.$configurationManager->get('plugin_leedHomeLink_2_name').'" id="plugin_leedHomeLink_2_name" name="plugin_leedHomeLink_2_name" />
				<input style="width:60%;" type="text" placeholder="http://mon.domaine.com/shaarli/" value="'.$configurationManager->get('plugin_leedHomeLink_2_link').'" id="plugin_leedHomeLink_2_link" name="plugin_leedHomeLink_2_link" />
			</p>
			<p>
				<label for="leedHomeLink_1">Lien 3 :</label> 
				<input style="width:50%;" type="text" value="'.$configurationManager->get('plugin_leedHomeLink_3_name').'" id="plugin_leedHomeLink_3_name" name="plugin_leedHomeLink_3_name" />
				<input style="width:60%;" type="text" value="'.$configurationManager->get('plugin_leedHomeLink_3_link').'" id="plugin_leedHomeLink_3_link" name="plugin_leedHomeLink_3_link" />
			</p>
			<p>
				<label for="leedHomeLink_1">Lien 4 :</label> 
				<input style="width:50%;" type="text" value="'.$configurationManager->get('plugin_leedHomeLink_4_name').'" id="plugin_leedHomeLink_4_name" name="plugin_leedHomeLink_4_name" />
				<input style="width:60%;" type="text" value="'.$configurationManager->get('plugin_leedHomeLink_4_link').'" id="plugin_leedHomeLink_4_link" name="plugin_leedHomeLink_4_link" />
			</p>
			<p>
				<label for="leedHomeLink_1">Lien 5 :</label> 
				<input style="width:50%;" type="text" value="'.$configurationManager->get('plugin_leedHomeLink_5_name').'" id="plugin_leedHomeLink_5_name" name="plugin_leedHomeLink_5_name" />
				<input style="width:60%;" type="text" value="'.$configurationManager->get('plugin_leedHomeLink_5_link').'" id="plugin_leedHomeLink_5_link" name="plugin_leedHomeLink_5_link" />
			</p>
		</section>
		<input type="submit" class="button" value="Enregistrer">
		</form>
	</section>
	';
}

function leedHomeLink_plugin_update($_){
	$configurationManager = new Configuration();
	$configurationManager->getAll();

	if($_['action']=='leedHomeLink_update'){
		$configurationManager->put('plugin_leedHomeLink_1_name',$_['plugin_leedHomeLink_1_name']);
		$configurationManager->put('plugin_leedHomeLink_1_link',$_['plugin_leedHomeLink_1_link']);
		$configurationManager->put('plugin_leedHomeLink_2_name',$_['plugin_leedHomeLink_2_name']);
		$configurationManager->put('plugin_leedHomeLink_2_link',$_['plugin_leedHomeLink_2_link']);
		$configurationManager->put('plugin_leedHomeLink_3_name',$_['plugin_leedHomeLink_3_name']);
		$configurationManager->put('plugin_leedHomeLink_3_link',$_['plugin_leedHomeLink_3_link']);
		$configurationManager->put('plugin_leedHomeLink_4_name',$_['plugin_leedHomeLink_4_name']);
		$configurationManager->put('plugin_leedHomeLink_4_link',$_['plugin_leedHomeLink_4_link']);
		$configurationManager->put('plugin_leedHomeLink_5_name',$_['plugin_leedHomeLink_5_name']);
		$configurationManager->put('plugin_leedHomeLink_5_link',$_['plugin_leedHomeLink_5_link']);
		$_SESSION['configuration'] = null;

		header('location: settings.php#leedHomeLink');
	}
}

Plugin::addJs('/js/leedHomeLink.js');

$myUser = (isset($_SESSION['currentUser'])?unserialize($_SESSION['currentUser']):false);
if($myUser!=false) {
    Plugin::addHook('setting_post_link', 'leedHomeLink_plugin_setting_link');
    Plugin::addHook('setting_post_section', 'leedHomeLink_plugin_setting_bloc');
    Plugin::addHook('action_post_case', 'leedHomeLink_plugin_update');
}
Plugin::addHook('footer_post_copyright', 'leedHomeLink_plugin_homelink');
?>
