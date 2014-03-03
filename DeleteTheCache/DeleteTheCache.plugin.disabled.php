<?php
/*
@name Delete the Cache
@author qwerty <qwerty@legtux.org>
@link http://etudiant-libre.fr.nf
@licence Tea Licence
@version 1.0.1
@description Vide le cache
*/

function clear_folder($folder, $skip_files=array()){
  $infos = array();
  $infos['return'] = true;
  $infos['nb_files'] = 0;
  
  $handle = @opendir($folder);
  if( !$handle ) return;
  while( $file = readdir($handle) )
  {
    if( $file == '.' || $file == '..' || in_array($file, $skip_files) ) continue;
    if( is_dir($folder . "/" . $file) ){
      $r = clear_folder($folder . "/" . $file);
      if( !$r )
      {
        $infos['return'] = false;
        return $infos;
      }
    }
    else
    {
      $r = @unlink($folder . "/" . $file);
      if($r)
      {
        $infos['nb_files']++;
      }
      else
      {
        $infos['return'] = false;
        return $infos;
      }
    }
  }
  closedir($handle);
  
  return $infos;
}


function delcache_plugin_setting_link(&$feed){
    echo '<a class="pointer" href="action.php?action=delcache" alt="Vider le cache de Leed" title="Vider le cache">Vider le cache</a>';
}

function delcache_plugin_action(&$_){
    if ($_['action']=='delcache'){
        $myUser = (isset($_SESSION['currentUser'])?unserialize($_SESSION['currentUser']):false);

        if($myUser==false) exit('Vous devez vous connecter pour cette action.');

        clear_folder("cache/");
        echo "<script type='text/javascript'>
                    if (window.confirm('Le cache a été vidé')) {
                        window.location.href='index.php';
                    }
              </script>";

    }
}

Plugin::addHook('setting_post_link', 'delcache_plugin_setting_link');
Plugin::addHook("action_post_case", "delcache_plugin_action"); 
?>
