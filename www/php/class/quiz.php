<?php

require_once __DIR__.'/../import/DBWriteClass.php';
require_once __DIR__.'/categoria.php';
require_once __DIR__.'/perguntas.php';

class quiz extends DBWriteClass{

	protected static $className = __CLASS__;
	
	public static $attrModel = [
		'id' => 'int',
		'idcategoria' => 'int',
		'titulo' => 'string',
		'descricao' => 'string',
		'perguntas' => 'object'
	];

	protected static function trimArray($array, $type){
		$array = parent::trimArray($array, $type);
		switch($type){
			case "required":
				unset($array['descricao']);
			break;
		}
		return $array;
	}
	
	function getAttrs(){
		$a = parent::getAttrs();
		$a['categoria'] = categoria::newByID($a['idcategoria'])->getAttrs();
		return $a;
	}

}

?>
