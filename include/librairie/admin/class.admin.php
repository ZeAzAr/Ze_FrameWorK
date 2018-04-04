<?php


function secureAdmin()
{
	if(!isset($_SESSION["login"]) && empty($_SESSION["login"]))
	{
		header("Location: index.php");
	}
}

function get_couleur_tr($i, $type = 0)
{
	if($i % 2 == 0) {
		$couleur	=	'#FFFFFF';
	}
	else
	{
		if($type == 0){
			$couleur	=	'#F0F0F0';
		}
		else {
			$couleur	=	'#ebeef3';
		}
	}
	return $couleur;
}

/* Mise en page */
function haut_table($titre)
{
	$titre	=	str_replace(" ", "&nbsp;", $titre);
	echo '<table cellpadding="0" cellspacing="0" border="0" class="table_arrondie" >' ;
	echo '<tr><td width="11"><img src="img/coin_hg.gif" alt="coin" /></td>' ;
	echo '<td style="" class="bleu_11"><span style="padding:3px;background-color:#F9F8F8">'.$titre.'</span></td>' ;
	echo '<td width="10"><img src="img/coin_hd.gif" alt="coin" /></td></tr>' ;
	echo '<tr><td style="background-image:url(img/fond_g.gif);background-repeat:repeat-y"></td>' ;
	echo '<td><div style="height:10px;"></div>' ;
}

function bas_table()
{
	echo '</td><td style="background-image:url(img/fond_d.gif);background-repeat:repeat-y"></td></tr>' ;
	echo '<tr><td width="11"><img src="img/coin_bg.gif" alt="coin" /></td>' ;
	echo '<td style="background-image:url(img/fond_b.gif);background-repeat:repeat-x"></td>' ;
	echo '<td width="10"><img src="img/coin_bd.gif" alt="coin" /></td></tr></table>' ;
}


?>