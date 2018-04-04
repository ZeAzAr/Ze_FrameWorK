<?php
ob_start();
session_start();
DEFINE('_PS_absolute_path',getcwd().'/include/');
require_once(_PS_absolute_path . 'loader.inc.php') ;
$LoadLibrairie = new loader("admin,security, util, cookie, MySQL, phpmailer","require_once","librairie");
$LoadAppli = new loader("configuration","require_once","application");
$lang = RecupVar( $_GET, 'lang' );

$lang = iif ( empty($lang) , 'FRA' , $lang ) ;

$LoadLlangue = new loader($lang.", sql","require_once","language");

$Entetecss = array('admin.css');
$Entetejs = array('diversajax.js','XHRConnection.js','jquery-1.4.2.min.js');
$ObjEntete = new Entete(FormatHTML(_LANG_Titre),FormatHTML(_LANG_MetaDesc),$lang,"",$Entetecss ,$Entetejs,1);

echo $ObjEntete->html;

$Tab_Param = RecupVarALL($_POST,$_GET,true);
echo '<body>' ;
$LoadHead = new loader("haut","require_once","modules");
echo '<div id="page"><div id="contenu">' ;
if ( empty($Tab_Param['page']) ) {
	$LoadPage = new loader('INDEX',"require_once","page");
}
else {
	$LoadPage = new loader($Tab_Param['page'],"require_once","page");
}

echo '</div></div>' ;

echo '</body>' ;
echo '</html>' ;
ob_end_flush();
