<?php

// CL classe abstraite Modulable permet de récuperer des variable a la volée
// Elle surchage le set et le get
// voir exemple : 
/*
class testAbs extends Entity {
 
     // Mes propriétés. 
     protected $name;
     protected $description;
 
 }
 // Création d'un objet
 $Id= new testAbs();
 
 // Affectation de propriétés
 $Id->setName('Lainé');
 $Id->setdescription('ceci est un exemple');
 
 // Lecture de propriétés
 echo 'Nom : '.$Id->getName()."<br />\n";
 echo 'Vitesse d'obt. : .$Id->getdescription();
*/

class ajaxplugin {
	
	public function __construct($TabParm,$oSmarty) {
			$this->TPSmarty = $oSmarty;
			$this->TPTabParm = $TabParm;
			$this->logfic = "";
			
	}
    public function PlugValue($name, $defaut="") {
			
			if (isset( $this->TPTabParm[$name] )) {
				$defaut = $this->TPTabParm[$name];
			}
		return $defaut;
	}
	public function logfile($Logtexte="",$logfilename="", $defaut=1) {
			if ($this->logfic=="") {
				$logfilename =iif($logfilename=="",get_class($this),$logfilename);
				//$this->logfic=& new oolog("./logs/".$logfilename.".log",$defaut);
				$this->logfic= new oolog("./logs/".$logfilename.".log",$defaut);

			}				
			$this->logfic->log($Logtexte);
	}
	
	 // Interception des accesseurs. 
     public function __call($method, $attrs) {
         $prefix  = substr($method, 0, 3);
         $suffix  = chr(ord(substr($method, 3, 1)) + 32);
         $suffix .= substr($method, 4);
         $cattrs  = count($attrs);
         if (property_exists($this, $suffix)) {
             if ($prefix == 'set' && $cattrs == 1) {
                 return $this->set($suffix, $attrs[0]);
             }
             if ($prefix == 'get' && $cattrs == 0) {
                 return $this->get($suffix);
             }
         }
         trigger_error("La méthode ".$method." n'existe pas.");
     }
	 
	public function debug($what='') {
			$what =iif($what=="",$this,$what);
			ob_start();
			var_export($what);
			$tab_debug = ob_get_contents();
			ob_end_clean();
			$this->logfile($tab_debug,"",129);
			$this->TPSmarty->assign('debug',true);
			//$this->logfile($tab_debug,"",129);$this->TPSmarty->fetch();
	}
}


?>