<?php
require_once 'Smarty.class.php';

class Ze_smarty extends Smarty {
	//Crée une instance de Smarty et la configure
	
	public  function  __construct(){
		$this->smarty();
		
		$this->template_dir = getcwd().'/templates/' ;
		$this->compile_dir = getcwd().'/templates_c/' ;
		$this->config_dir = getcwd().'/configs/' ;
		$this->cache_dir = getcwd().'/cache/' ;
		$this->caching = true ;
	}
}

?>