<?php
// Version 2.1 
// Janvier 2006
/**
 * @version $Id: globals.php 1145 2005-11-20 22:57:55Z Jinx $
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

/**
 * Use 1 to emulate register_globals = on
 * 
 * Use 0 to emulate regsiter_globals = off
 */
define( 'RG_EMULATION', 1 );
define( '_MOS_NOTRIM', 1 );
define( '_MOS_ALLOWHTML', 1 );
DEFINE('_LOG_ACCESS','/custom/website/avenue-mandarine/httpdocs/log/login.log');
DEFINE('_LOG_ERROR','/custom/website/avenue-mandarine/httpdocs/log/security.log');
/**
 * Adds an array to the GLOBALS array and checks that the GLOBALS variable is
 * not being attacked
 * @param array
 * @param boolean True if the array is to be added to the GLOBALS
 */
function checkInputArray( &$array, $globalise=false ) {
	//static $banned = array( '_files', '_env', '_get', '_post', '_cookie', '_server', '_session', 'globals' );
	static $banned = array( '_files', '_env', '_get', '_post', '_cookie', '_server', '_session', 'globals', '_LANG_','_IMG_','_CONFIG_');

	foreach ($array as $key => $value) {
		if (in_array( strtolower( $key ), $banned ) ) {
			//die( 'Illegal variable <b>' . implode( '</b> or <b>', $banned ) . '</b> passed to script.' );
			// CL redirection vers index
			die( 'Security Alert : Illegal variable <b>' . implode( '</b> or <b>', $banned ) . '</b> passed to script.' );
		}
		if ($globalise) {
			$GLOBALS[$key] = $value;
		}
	}
}

/**
 * Emulates register globals = off
 */
function unregisterGlobals () {
	checkInputArray( $_FILES );
	checkInputArray( $_ENV );
	checkInputArray( $_GET );
	checkInputArray( $_POST );
	checkInputArray( $_COOKIE );
	checkInputArray( $_SERVER );

	if (isset( $_SESSION )) {
		checkInputArray( $_SESSION );
	}

	$REQUEST = $_REQUEST;
	$GET = $_GET;
	$POST = $_POST;
	$COOKIE = $_COOKIE;
	if (isset ( $_SESSION )) {
		$SESSION = $_SESSION;
	}
	$FILES = $_FILES;
	$ENV = $_ENV;
	$SERVER = $_SERVER;
	foreach ($GLOBALS as $key => $value) {
		if ( $key != 'GLOBALS' ) {
			unset ( $GLOBALS [ $key ] );
		}
	}
	$_REQUEST = $REQUEST;
	$_GET = $GET;
	$_POST = $POST;
	$_COOKIE = $COOKIE;
	if (isset ( $SESSION )) {
		$_SESSION = $SESSION;
	}
	$_FILES = $FILES;
	$_ENV = $ENV;
	$_SERVER = $SERVER;
}

/**
 * Emulates register globals = on
 */
function registerGlobals() {
	checkInputArray( $_FILES, true );
	checkInputArray( $_ENV, true );
	checkInputArray( $_GET, true );
	checkInputArray( $_POST, true );
	checkInputArray( $_COOKIE, true );
	checkInputArray( $_SERVER, true );

	if (isset( $_SESSION )) {
		checkInputArray( $_SESSION, true );
	}

	foreach ($_FILES as $key => $value){
		$GLOBALS[$key] = $_FILES[$key]['tmp_name'];
		foreach ($value as $ext => $value2){
			$key2 = $key . '_' . $ext;
			$GLOBALS[$key2] = $value2;
		}
	}
}

if (RG_EMULATION == 0) {
	// force register_globals = off
	unregisterGlobals();	
} else if (ini_get('register_globals') == 0) {
	// php.ini has register_globals = off and emulate = on
	registerGlobals();
} else {
	// php.ini has register_globals = on and emulate = on
	// just check for spoofing
	checkInputArray( $_FILES );
	checkInputArray( $_ENV );
	checkInputArray( $_GET );
	checkInputArray( $_POST );
	checkInputArray( $_COOKIE );
	checkInputArray( $_SERVER );

	if (isset( $_SESSION )) {
		checkInputArray( $_SESSION );
	}
}

function RecupVar( &$arr, $name, $def=null, $mask=0 ) {
/* exemple
RecupVar( $_REQUEST, 'option' ) 
$sectcat = RecupVar( $_POST, 'sectcat', '' );
 RecupVar( $_COOKIE, 'sessioncookie', null );
*/
	$return = null;
	if (isset( $arr[$name] )) {
	    if (is_string( $arr[$name] )) {
			if (!($mask&_MOS_NOTRIM)) {
				$arr[$name] = trim( $arr[$name] );
			}
			if (!($mask&_MOS_ALLOWHTML)) {
				$arr[$name] = strip_tags( $arr[$name] );
			}
			if (!get_magic_quotes_gpc()) {
				$arr[$name] = addslashes( $arr[$name] );
			}
			if (!validecvar($name,$arr[$name])) {
				$logvariable = new LogSercurite($name,-1,$arr[$name]);
				//die('Security Alert : Illegal variable : '.$name.' / '.$arr[$name]);
				header("Location: /404.html");
				if (!empty($def)) {
						return $def;
				} else {
					die('Security Alert is logged : Illegal variable <a href="http://www.avenue-mandarine.com/">www.avenue-mandarine.com</a>');	
				}
			}
		}
		return $arr[$name];
	} else {
		return $def;
	}
}

function RecupVarALL($ZE_POST,$ZE_GET,$avecnom=true) {
	$Tabvalue = array();
	$i=0;
	foreach ($ZE_POST as $cle => $value) {
			if ($avecnom) {
				$Tabvalue[$cle] = RecupVar( $ZE_POST,$cle);
			} else {
				$Tabvalue[$i]= RecupVar( $ZE_POST,$cle);
				$i++;
			}
	}
	foreach ($ZE_GET as $cle => $value) {
			if ($avecnom) {
				$Tabvalue[$cle] = RecupVar($ZE_GET,$cle);
			} else {
				$Tabvalue[$i]= RecupVar($ZE_GET,$cle);
				$i++;
			}
	}
	return $Tabvalue;
}

function recuptabexist($arr,$name,$defaut) {
print_r($arr);
echo "-1:".isset($arr);
echo "-2:".is_array($arr);
echo "-3:".print_r(array_key_exists($name, $arr)==true);
echo "-4:".empty($arr[$name]);

return iif(isset($arr),iif(is_array($arr),iif(array_key_exists($name, $arr),iif(!empty($arr[$name]),$arr[$name],$defaut),$defaut),$defaut),$defaut);

}

function validecvar($name, $value) {
	
	$retourne = false;
	$Arraylangh = array('FRA'=>1, 'ENG'=>2);
	//static $bannedSQLInjection = array('UNION ','SELECT ');
	static $bannedSQLInjection = array();
	static $bannedchars = array('\*', '\?', '&','"', '#', '~', '{', '\(', '\[', '\|', '`', '_', '\^', ')', '\]', '=', '\+', '}', '%', ';', '/', '!', '�', '<', '>');
		
	switch ($name) {
		case "lang" : {
			if (array_key_exists($value, $Arraylangh)) {
				$retourne = true;		
			}
			break;
		}
				
		default : {
			//$chars = array('\*', '\?', '&','"', '#', '~', '{', '\(', '\[', '\|', '`', '_', '\^', ')', '\]', '=', '\+', '}', '\$', '%', ',', ';', '/', '!', '�', '<', '>');
			$chars = array('\*', '\?', '&','"', '#', '~', '{', '\(', '\[', '\|', '`', '_', '\^', ')', '\]', '=', '\+', '}', '%', ';', '/', '!', '�', '<', '>');
  			$retourne = true;	
  			for ($i=0;$i<count($chars);$i++) {
  				if (@ereg($chars[$i],$value)) {
  					$retourne = false;
   					
   				}
   			}
			if (array_key_exists($value, $bannedSQLInjection)) {
				$retourne = false;		
			}
   			
  			break;
		}
	}
	return $retourne;
}

class LogSercurite {
	var $Log_Name="";
	var $Log_Pass="";
	var $Log_IP = "";
	var $Log_File = "";
	var $Log_Date = "";
	var $Log_Ligne = "";
	var $Log_Ref = "";
	var $Log_page ="";
	
	function LogSercurite($LogName,$LogAcces,$LogPass) {
		$this->Log_IP = $_SERVER['REMOTE_ADDR'];
		$this->Log_Date	= date("d-m-Y H:i:s");
		$this->Log_Name	= $LogName;
		$this->Log_Pass	= $LogPass;
		$this->Log_page = $_SERVER["REQUEST_URI"];	
		
		if ($LogAcces==-1) {
			$this->Log_File = _LOG_ERROR;
		} else {
			$this->Log_File = _LOG_ACCESS;
		}
		if ($LogSec = fopen($this->Log_File, 'a')) {
			$this->Log_Ligne = '"'.$this->Log_Date.'" - "'.$this->Log_IP.'" - "'.$this->Log_page.'" : "'.$this->Log_Name.'" -> "'.$this->Log_Pass.'"';
			fwrite($LogSec, $this->Log_Ligne);
			fwrite($LogSec,"\n");
			fclose($LogSec);
		}	
	}
}
