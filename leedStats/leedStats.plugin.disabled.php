<?php
/*
@name leedStats
@author Cobalt74 <cobalt74@gmail.com>
@link http://www.cobestran.com
@licence CC by nc sa http://creativecommons.org/licenses/by-nc-sa/2.0/fr/
@version 1.0.0
@description Permet d'avoir des petites statistiques sur les flux de votre environnement Leed.
*/

function leedStats_plugin_setting_link(&$myUser){
	echo '<li><a class="toggle" href="#leedStatslBloc">Plugin Leed Stats</a></li>';
}

function leedStats_plugin_setting_bloc(&$myUser){
	$configurationManager = new Configuration();
	$configurationManager->getAll();

    echo '
	<section id="leedStatslBloc" class="leedStatslBloc" style="display:none;">
		<h2>Plugin Leed Stats</h2>

		<section class="preferenceBloc">
		<h3>Résumé</h3>
	';

    //Nombre global d'article lus / non lus / total / favoris
    $requete = 'SELECT
                (SELECT count(1) FROM '.MYSQL_PREFIX.'feed)as nbFeed,
                (SELECT count(1) FROM '.MYSQL_PREFIX.'event WHERE unread = 1)as nbUnread,
                (SELECT count(1) FROM '.MYSQL_PREFIX.'event WHERE unread = 0)as nbRead,
                (SELECT count(1) FROM '.MYSQL_PREFIX.'event) as nbTotal,
                (SELECT count(1) FROM '.MYSQL_PREFIX.'event WHERE favorite = 1)as nbFavorite
                ';
    $query = mysql_query($requete);
    if($query!=null){
        echo '<div id="result_leedStats1" class="result_leedStats1">
                 <table>
                        <th class="leedStats_border leedStats_th">Nb. feed</th>
                        <th class="leedStats_border leedStats_th">Nb. articles</th>
                        <th class="leedStats_border leedStats_th">Nb. art. non lus</th>
                        <th class="leedStats_border leedStats_th">Nb. art. lus</th>
                        <th class="leedStats_border leedStats_th">Nb. favoris</th>
        ';
        while($data = mysql_fetch_array($query)){
            echo '
                <tr>
                    <td class="leedStats_border leedStats_textright">'.$data['nbFeed'].'</td>
                    <td class="leedStats_border leedStats_textright">'.$data['nbTotal'].'</td>
                    <td class="leedStats_border leedStats_textright">'.$data['nbUnread'].'</td>
                    <td class="leedStats_border leedStats_textright">'.$data['nbRead'].'</td>
                    <td class="leedStats_border leedStats_textright">'.$data['nbFavorite'].'</td>
                </tr>
            ';
        }
        echo '</table>
            </div>';
    }
	echo '
            <h3>Nb d\'article par flux RSS</h3>

    ';
    //Nombre global d'article lus / non lus / total / favoris
    $requete = 'SELECT name, count(1) as nbTotal,
                (SELECT count(1) FROM '.MYSQL_PREFIX.'event le2 WHERE le2.unread=1 and le1.feed = le2.feed) as nbUnread,
                (SELECT count(1) FROM '.MYSQL_PREFIX.'event le2 WHERE le2.unread=0 and le1.feed = le2.feed) as nbRead,
                (SELECT count(1) FROM '.MYSQL_PREFIX.'event le2 WHERE le2.favorite=1 and le1.feed = le2.feed) as nbFavorite
                FROM '.MYSQL_PREFIX.'feed lf1
                INNER JOIN '.MYSQL_PREFIX.'event le1 on le1.feed = lf1.id
                GROUP BY name
                ORDER BY name
                ';
    $query = mysql_query($requete);
    if($query!=null){
        echo '<div id="result_leedStats1" class="result_leedStats1">
                 <table>
                        <th class="leedStats_border leedStats_th">Feed</th>
                        <th class="leedStats_border leedStats_th">Nb. articles</th>
                        <th class="leedStats_border leedStats_th">Nb. art. non lus</th>
                        <th class="leedStats_border leedStats_th">Nb. art. lus</th>
                        <th class="leedStats_border leedStats_th">Nb. favoris</th>
        ';
        while($data = mysql_fetch_array($query)){
            echo '
                <tr>
                    <td class="leedStats_border leedStats_textright">'.short_name($data['name'],32).'</td>
                    <td class="leedStats_border leedStats_textright">'.$data['nbTotal'].'</td>
                    <td class="leedStats_border leedStats_textright">'.$data['nbUnread'].'</td>
                    <td class="leedStats_border leedStats_textright">'.$data['nbRead'].'</td>
                    <td class="leedStats_border leedStats_textright">'.$data['nbFavorite'].'</td>
                </tr>
            ';
        }
        echo '</table>
            </div>';
    }
    echo '
        </section>
	</section>
	';
}

function short_name($str, $limit)
{
    // Make sure a small or negative limit doesn't cause a negative length for substr().
    if ($limit < 3)
    {
        $limit = 3;
    }

    // Now truncate the string if it is over the limit.
    if (strlen($str) > $limit)
    {
        return substr($str, 0, $limit - 3) . '...';
    }
    else
    {
        return $str;
    }
}


        // Ajout de la fonction au Hook situé avant l'affichage des évenements
    Plugin::addHook('setting_post_link', 'leedStats_plugin_setting_link');
    Plugin::addHook('setting_post_section', 'leedStats_plugin_setting_bloc');
?>
