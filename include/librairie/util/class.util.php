<?php

function parseInt($string) { 
// return intval($string); 
if(preg_match('/(\d+)/', $string, $array)) { 
return $array[1]; 
} else { 
return 0; 
} 
} 

function noHTML($str)
{
	$str=eregi_replace('<br />',' ',$str);
	$str=ereg_replace('<[^>]+>',' ',$str);
	$str=ereg_replace('\s{2,}',' ',$str);
	return $str;
}

function coupeMots($str, $nb)
{
$tabTexte=explode(' ',$str);
if (count($tabTexte)>$nb)
	{
	$str='';
	for ($i=0;$i<count($tabTexte) && $i<$nb;$i++)
		{
		if (strlen($tabTexte[$i])>0)	$str=$str.' '.$tabTexte[$i];
		}
	$str=$str.' [...]';
	}
return $str;
}

class Entete {
	public $html ="";
	
	public function __construct($Titre, $Desc, $Lang,$Sessionid,$FeuilleCSS,$Script,$finHead) {
	$this->html .= _LANG_DOCTYPE;
	$this->html .=  '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset='._LANG_CodePage.'" />' ;
	$this->html .=  '<title>'.$Titre.'</title>';
	$this->html .=  '<meta name="description" content="'.$Desc.'" />';
	$this->html .=  '<meta name="keywords" content="'._LANG_MetaKeys.'" />';
	$this->html .=  '<meta name="Author" content="'._LANG_MetaAuthor.'" />';
	$this->html .=  '<meta name="Copyright" content="'._LANG_MetaCopyright.'" />';
	$this->html .=  '<meta name="robots" content="all" />' ;
//	$this->html .=  '<meta name="revisit-after" content="15 days" />' ;
	$this->html .=  '<meta http-equiv="pragma" content="nocache" />';
	$this->html .=  '<link rel="icon" href="/images/logo-orange.png" type="image/ico" />' ;
	$this->html .=  '<meta name="google-site-verification" content="OpWipp383nCVMg47FryEkR-IhXCjB_LN7_ZR6Van60Q" />';
	$this->html .=  '<meta name="google-site-verification" content="OpWipp383nCVMg47FryEkR-IhXCjB_LN7_ZR6Van60Q" />';

		
	if (is_array($Script)) {
		foreach($Script as $valeur){
			if(isset($valeur)) {
				$this->html .=  '<script type="text/javascript" src="/js/'.$valeur.'"></script>';
			}	
		}
	}
	if ($FeuilleCSS!="") {
		foreach($FeuilleCSS as $valeur){
			$this->html .=  '<link rel="stylesheet" href="/css/'.$valeur.'" type="text/css" />';
		}
	}
	if ($finHead==1) {
		$this->html .=  '</head>';
	}
	}
	
	public function __destruct() {
		$this->html = "";
	}
}

class InjScript {
	public $script ="";
	
	public function __construct($Inject, $finHead) {
	$this->script .= '<script type="text/javascript">';
	$this->script .= $Inject ;
	$this->script .= '</script>';
			
	if ($finHead==1) {
		$this->script .=  '</head>';
	}
	}
	
	public function __destruct() {
		$this->script = "";
	}
}

class SWFObject {
	public $script ="";
	
	public function __construct($swf, $div, $width, $height, $flashvars, $params, $attributes, $version, $install,$finHead) {
		$this->script .= '<script type="text/javascript">';
		$this->script .= 'var flashvars = {};';
		$this->script .= 'var params = {};';
		$this->script .= 'var attributes = {};';
		
		if($flashvars!=""){
			foreach($flashvars as $key => $value){
				if(isset($key)){
					$this->script .= 'flashvars.'.$key.' = "'.$value.'";';
				}
			}
		}
		if($params!="") {
			foreach($params as $key => $value) {
				if(isset($key)) {
					$this->script .= 'params.'.$key.' = "'.$value.'";';
				}
			}
		}
		if($attributes!='') {
			foreach ($attributes as $key => $value) {
				if(isset($key)) {
					$this->script .= 'attributes.'.$key.' = "'.$value.'";';
				}
			}
		}
		if($version=="") {
			$version = '9.0.0' ;
		}
		$this->script .= 'swfobject.embedSWF("'.$swf.'", "'.$div.'", "'.$width.'", "'.$height.'", "'.$version.'", "'.$install.'", flashvars, params, attributes);' ;
		$this->script .= '</script>' ;
				
		if ($finHead==1) {
			$this->script .=  '</head>';
		}
	}
	
	public function __destruct() {
		$this->script = "" ;
	}
}

function format_nombre($Chiffre,$nbvirgule,$FormatSep,$FormatSepMillier=false) {
$Millier = '';
if($FormatSepMillier==true) {
	if ($FormatSep=="FRA") {
		$Millier = " ";
	}
	if ($FormatSep=="ENG") {
		$Millier = ",";
	}
} 
if ($FormatSep =="FRA") {
	return number_format(round($Chiffre,$nbvirgule),$nbvirgule,',',$Millier);
}

if ($FormatSep =="CSV") {
	return number_format(round($Chiffre,$nbvirgule),$nbvirgule,'.',$Millier);
}

if ($FormatSep =="XLS") {
	return number_format(round($Chiffre,$nbvirgule),$nbvirgule,'.',$Millier);
}

return number_format(round($Chiffre,$nbvirgule), $nbvirgule,'.',$Millier);
}


function FormatHTML($texte) {
	if (_LANG_CodeConvert=="UTF-8") {
		return trim($texte);
	}
return str_replace(" ","&nbsp;",htmlentities(trim($texte),ENT_QUOTES,_LANG_CodePage));
}

function FormatTXT($texte) {
	
return $texte;
}

function FormatXLS($texte) {
			
	if (_LANG_CodeConvert=="UTF-8") {
		//return chr(255).chr(254).mb_convert_encoding( html_to_utf8(FormatHTML($texte)), 'UTF-16LE', 'UTF-8'); 
		//return mb_convert_encoding( html_to_utf8(FormatHTML($texte)), _LANG_CodeXLS, 'UTF-8'); 
		return mb_convert_encoding( html_to_utf8(FormatHTML($texte)), _LANG_CodeXLS, 'UTF-8');
		//return iconv ( "UTF-8", _LANG_CodeXLS,encode($texte));
	}
	else {		
		return html_entity_decode($texte,ENT_QUOTES,_LANG_CodeXLS);	
	}
}

function FormatXML($texte,$avecfiltre=true) {
		if (_LANG_CodeConvert=="UTF-8") {
			//return html_to_utf8(FormatHTML($texte));
			return html_to_utf8(FormatHTML($texte));
			 
		}
		return filtre_XML($texte,$avecfiltre);
				
	//return iconv("ISO-8859-1", "UTF-8",$texte);
	//return FormatHTML($texte);
	
}
function filtre_XML($XMLtxt,$Tout) {
			$TmpTXT = "";
			// CL on gère les caracters de séparation
			$search = array ('@[&]@i',"@[']@i",'@["]@i','@[‘]@i','@[<]@i','@[>]@i','@[˜]@i');
			$replace = array ('&#38;','&#146;','&#34;','&#145;','&#139;','&#155;','&#152;');
			$TmpTXT = preg_replace($search, $replace, $XMLtxt);
			if ($Tout) {
				$search = array ('@[éèêëÊË]@i','@[àâäÂÄ]@i','@[îïÎÏ]@i','@[ûùüÛÜ]@i','@[ôöÔÖ]@i','@[ç]@i');
				$replace = array ('e','a','i','u','o','c');
			// CL on gere les accents
			// Les E
			
			//$search = array ("@[é]@i",'@[è]@i','@[ê]@i','@[ë]@i','@[È]@i','@[É]@i','@[Ê]@i','@[Ë]@i');
			//$replace = array ('&#233;','&#232;','&#234;','&#235;','&#200;','&#201;','&#202;','&#203;');
			//$TmpTXT = preg_replace($search, $replace, $TmpTXT);
			// Les A
			//$search = array ("@[À]@i",'@[Á]@i','@[Â]@i','@[Ã]@i','@[Ä]@i','@[à]@i','@[á]@i','@[â]@i','@[ã]@i','@[ä]@i');
			//$replace = array ('&#192;','&#193;','&#194;','&#195;','&#196;','&#224;','&#225;','&#226;','&#227;','&#228;');
			//$TmpTXT = preg_replace($search, $replace, $TmpTXT);
			// Les O
			//$search = array ("@[Ò]@i",'@[Ó]@i','@[Ô]@i','@[Õ]@i','@[Ö]@i','@[ò]@i','@[ó]@i','@[ô]@i','@[õ]@i','@[ö]@i');
			//$replace = array ('&#210;','&#211;','&#212;','&#213;','&#214;','&#242;','&#243;','&#244;','&#245;','&#246;');
			//$TmpTXT = preg_replace($search, $replace, $TmpTXT);
			
			// Les i
			//$search = array ("@[Ì]@i",'@[Í]@i','@[Î]@i','@[Ï]@i','@[ì]@i','@[í]@i','@[î]@i','@[ï]@i');
			//$replace = array ('&#204;','&#205;','&#206;','&#207;','&#236;','&#237;','&#238;','&#239;');
			//$TmpTXT = preg_replace($search, $replace, $TmpTXT);
			
			// Les u
			//$search = array ("@[ù]@i",'@[ú]@i','@[ü]@i','@[û]@i','@[Ù]@i','@[Ú]@i','@[Û]@i','@[Ü]@i');
			//$replace = array ('&#249;','&#250;','&#252;','&#251;','&#217;','&#218;','&#219;','&#220;');
			//$TmpTXT = preg_replace($search, $replace, $TmpTXT);
			
			// Autres
			//$search = array ("@[Š]@i",'@[Œ]@i','@[Ž]@i','@[š]@i','@[œ]@i','@[ž]@i','@[Ç]@i','@[Ñ]@i','@[Ø]@i','@[ß]@i','@[Þ]@i','@[ç]@i','@[ñ]@i','@[þ]@i','@[ý]@i','@[ÿ]@i');
			//$replace = array ('&#138;','&#140;','&#142;','&#154;','&#156;','&#158;','&#199;','&#209;','&#216;','&#223;','&#222;','&#231;','&#241;','&#254;','&#253;','&#255;');
				$TmpTXT = preg_replace($search, $replace, $TmpTXT);
			}
			return $TmpTXT;
	}
	
//CL cette fonction permet d'évaluer une define en format texte pour la faire devenir variable 
// exemplr : Variable1=13, toto ="Variable1" -> Eval2(toto) = 13
function Eval2($Texte) {
	eval( "\$Txteval = $Texte;" );
return 	$Txteval;
}




//////////////////////////////////////////////////
// exemple d'utilisation
//$mysql = mysql_connect();
//$host = $_SERVER["SERVER_NAME"];
//
// call_user_func_array('debug', array("host", $host));
//call_user_func_array('debug', array("mysql", $mysql));
// call_user_func_array('debug', array("_POST", $_POST));
function debug($var, $val) {
echo "***Deboggage\nVariable : $var\nValeur :";
if (is_array($val) || is_object($val) || is_resource($val)) {
	print_r($val);
} else {
	echo "\n$val\n";
}
	echo "***\n";
	die("Fin deboggage");
}
////////////////////////////////////////
// FUNCTION POUR PHP 4 UTF8
///////////////////////////////////////

function html_to_utf8 ($data)
    {
    return preg_replace("/\\&\\#([0-9]{3,10})\\;/e", '_html_to_utf8("\\1")', $data);
    }

function _html_to_utf8 ($data)   {
    if ($data > 127)
        {
        $i = 5;
        while (($i--) > 0)
            {
            if ($data != ($a = $data % ($p = pow(64, $i))))
                {
                $ret = chr(base_convert(str_pad(str_repeat(1, $i + 1), 8, "0"), 2, 10) + (($data - $a) / $p));
                for ($i; $i > 0; $i--)
                    $ret .= chr(128 + ((($data % pow(64, $i)) - ($data % ($p = pow(64, $i - 1)))) / $p));
                break;
                }
            }
        }
        else
        $ret = "&#$data;";
    return $ret;
}



function code2utf($num)
{
	if ($num < 128) return chr($num);
	if ($num < 2048) return chr(($num >> 6) + 192) . chr(($num & 63) +
128);
	if ($num < 65536) return chr(($num >> 12) + 224) . chr((($num >> 6) &
63) + 128) . chr(($num & 63) + 128);
	if ($num < 2097152) return chr(($num >> 18) + 240) . chr((($num >> 12)
& 63) + 128) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
	return '';
}

function encode($str)
{
	return preg_replace('/&#(\\d+);/e', 'code2utf($1)',utf8_encode($str));
}

function utf8entities($source)
{
   // array used to figure what number to decrement from character order value 
   // according to number of characters used to map unicode to ascii by utf-8
   $decrement[4] = 240;
   $decrement[3] = 224;
   $decrement[2] = 192;
   $decrement[1] = 0;
   
   // the number of bits to shift each charNum by
   $shift[1][0] = 0;
   $shift[2][0] = 6;
   $shift[2][1] = 0;
   $shift[3][0] = 12;
   $shift[3][1] = 6;
   $shift[3][2] = 0;
   $shift[4][0] = 18;
   $shift[4][1] = 12;
   $shift[4][2] = 6;
   $shift[4][3] = 0;
   
   $pos = 0;
   $len = strlen($source);
   $encodedString = '';
   while ($pos < $len)
   {
      $charPos = substr($source, $pos, 1);
      $asciiPos = ord($charPos);
      if ($asciiPos < 128)
      {
         $encodedString .= htmlentities($charPos);
         $pos++;
         continue;
      }
      
      $i=1;
      if (($asciiPos >= 240) && ($asciiPos <= 255)) // 4 chars representing one unicode character
         $i=4;
      else if (($asciiPos >= 224) && ($asciiPos <= 239)) // 3 chars representing one unicode character
         $i=3;
      else if (($asciiPos >= 192) && ($asciiPos <= 223)) // 2 chars representing one unicode character
         $i=2;
      else // 1 char (lower ascii)
         $i=1;
      $thisLetter = substr($source, $pos, $i);
      $pos += $i;
      
      // process the string representing the letter to a unicode entity
      $thisLen = strlen($thisLetter);
      $thisPos = 0;
      $decimalCode = 0;
      while ($thisPos < $thisLen)
      {
         $thisCharOrd = ord(substr($thisLetter, $thisPos, 1));
         if ($thisPos == 0)
         {
            $charNum = intval($thisCharOrd - $decrement[$thisLen]);
            $decimalCode += ($charNum << $shift[$thisLen][$thisPos]);
         }
         else
         {
            $charNum = intval($thisCharOrd - 128);
            $decimalCode += ($charNum << $shift[$thisLen][$thisPos]);
         }
         
         $thisPos++;
      }
      
      $encodedLetter = '&#'. str_pad($decimalCode, ($thisLen==1)?3:5, '0', STR_PAD_LEFT).';';
      $encodedString .= $encodedLetter;
   }
   
   return $encodedString;
}

function htmlutf8entities($str)
{
   if (!is_string($str)) die('<b>Warning:</b><br/>'
                     .'<tt>utf8entities(string $source)</tt> : $source should be a string.');
   
   //utf8 to unicode
   $unicode = array();       
   $values = array();
   $lookingFor = 1;
   $len = strlen($str);
   for ($i = 0; $i < $len; $i++ )
   {
      $thisValue = ord($str[$i]);
      if ($thisValue < 128)
         $unicode[] = $thisValue;
      else
      {
         if (count($values) == 0)
            $lookingFor = ($thisValue < 224)?2:3;
         $values[] = $thisValue;
         if (count($values) == $lookingFor)
         {
            $number = ($lookingFor == 3)
               ?(($values[0]%16)*4096) + (($values[1]%64)*64) + ($values[2]% 64)
               :(($values[0]%32)*64) + ($values[1]%64);
            $unicode[] = $number;
            $values = array();
            $lookingFor = 1;
         } // if
      } // if
   } // for
   
   $entities = '';
   foreach($unicode as $value)
      $entities .= $value<128 ? htmlentities(chr($value)) : ('&#'.$value.';');
   return $entities;
}


function isUTF8($string)
{
   if (is_array($string))
   {
      $enc = implode('', $string);
      return @!((ord($enc[0]) != 239) && (ord($enc[1]) != 187) && (ord($enc[2]) != 191));
   }
   else
   {
      return (utf8_encode(utf8_decode($string)) == $string);
  	}
}   


function decode_url_utf8($strdecode) {
  	$strdecode = unescape($strdecode);
	$strdecode = utf8entities ($strdecode);
  return $strdecode;
}


function unescape($strIn, $iconv_to = 'UTF-8') {
  $strOut = '';
  $iPos = 0;
  $len = strlen ($strIn);
  while ($iPos < $len) {
    $charAt = substr ($strIn, $iPos, 1);
    if ($charAt == '%') {
      $iPos++;
      $charAt = substr ($strIn, $iPos, 1);
      if ($charAt == 'u') {
        // Unicode character
        $iPos++;
        $unicodeHexVal = substr ($strIn, $iPos, 4);
        $unicode = hexdec ($unicodeHexVal);
        $strOut .= code2utf($unicode);
        $iPos += 4;
      }
      else {
        // Escaped ascii character
        $hexVal = substr ($strIn, $iPos, 2);
        if (hexdec($hexVal) > 127) {
          // Convert to Unicode 
          $strOut .= code2utf(hexdec ($hexVal));
        }
        else {
          $strOut .= chr (hexdec ($hexVal));
        }
        $iPos += 2;
      }
    }
    else {
      $strOut .= $charAt;
      $iPos++;
    }
  }
  if ($iconv_to != "UTF-8") {
    $strOut = iconv("UTF-8", $iconv_to, $strOut);
  }   
  return $strOut;
} 



function iif($expression, $returntrue, $returnfalse = '') { 
    return ($expression ? $returntrue : $returnfalse); 
} 


function in_array_multi_key($needle, $haystack)
{
   // mutidimentional search for in_array function
   // only matches the key, values don't count.
   if ( array_key_exists($needle, $haystack) )
   {
       return TRUE;
   }

   foreach ( $haystack as $key => $value )
   {
       if ( is_array($value) )
       {
           $work = in_array_multi_key($needle, $value);
           if ( $work )
           {
               return TRUE;
           }
       }
   }
   return FALSE;
}


function VerifMail($Mail) {
  if (!Empty($Mail)) {
  	$Retour = eregi("^[[:alpha:]]{1}[[:alnum:]]*((\.|_|-)[[:alnum:]]+)*@".
        	          "[[:alnum:]]{1}[[:alnum:]]*((\.|-)[[:alnum:]]+)*".
                	  "(\.[[:alpha:]]{2,})$",
                	  $Mail);
  } else {
  	$Retour = FALSE;
  }
  Return $Retour;
}


function Reverse_Array($array)
{    $index = 0;
   foreach ($array as $subarray)
   {    if (is_array($subarray))
       {    $subarray = array_reverse($subarray);
           $arr = Reverse_Array($subarray);
           $array[$index] = $arr;
       }
       else {$array[$index] = $subarray;}
       $index++;
   }
   return $array;
} 

function tableauvirgule($tabvirgule) {
	$resultat = "";
	if (is_array($tabvirgule)) {
		for ($i=0;$i<count($tabvirgule);$i++) {
			$resultat .= $tabvirgule[$i];
			if (isset($tabvirgule[$i+1])) {
				//$resultat .= ',';
			}	
		}
	} else {
		
		$resultat =$tabvirgule;
	}
		return $resultat;
}

function chaineToTab($tabchaine,$Taille) {
	$resultat = array();
	$indiceT =0;
	$indice  =0;
	$resultat[0] = $tabchaine; 
	while (($indice<strlen($tabchaine)) && (strlen(substr($tabchaine,$indiceT,$Taille))==$Taille) ) {
		$resultat[$indice] =substr($tabchaine,$indiceT,$Taille);
		$indiceT = $indiceT + $Taille;
		$indice++;
	}
	return $resultat;
}

function random($car) {
	$string = "";
	$chaine = "ABCDEFGHIJQLMNOPQRSTUVWXYZabcdefghijqlmnopqrstuvwxyz0123456789";
	srand((double)microtime()*1000000);
	for($i=0; $i<$car; $i++) {
		$string .= $chaine[rand()%strlen($chaine)];
	}
	return $string;
}

?>