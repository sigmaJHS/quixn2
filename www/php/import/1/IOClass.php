<?php
 
require_once __DIR__.'/../include/header.php';
require_once __DIR__.'/../include/functions.php';

abstract class IOClass{

	protected $attr;
	protected static $attrModel = [];
	protected static $className = 'IOClass';
	
	function __construct($array = []){
		$this->fill($array);
	}

	function fill($array = []){

		$className = static::$className;
		$a = static::$attrModel;

		foreach($a as $k => $v){

			$isArray = false;
			$type = gettype($v);
			if($type == 'array'){
				$isArray = true;
			}

			if(!isset($this->attr[$k])){
				if($isArray === true || $v === 'object'){
					$this->set($k, []);
				}else{
					$this->set($k, null);
				}
			}

			if(isset($array[$k])){
				if($isArray === true){
					foreach($array[$k] as $a){
						$this->add($k, $a);
					}
				}else
				if($v === 'object'){
					foreach($array[$k] as $a){
						$type = gettype($a);
						if($type === 'integer'){
							$this->add($k, $k::newByID($a));
						}else
						if($type === 'array'){
							$this->add($k, new $k($a));
						}else
						if($type === 'object'){
							$this->add($k, $a);
						}else{
							$this->add($k, new $k());
						}
					}
				}else{
					$this->set($k, $array[$k]);
				}
			}

		}

	}
	
	//	getters e setters

	function get($attr){
		return $this->attr[$attr];
	}

	function set($attr,$v){
		$this->attr[$attr] = $v;
	}

	function add($attr,$v,$index = null){
	
		if($index == null){
			$index = array_keys($this->get($attr));
			$index = end($index);
			$index = ($index === false) ? 0 : $index+1;
		}
			
		$this->attr[$attr][$index] = $v;
		
	}

	//	Retorna o objeto em formato JSON
	function getJSON(){
		$a = [];
		foreach($this as $k => $v){
			$a[$k] = $v;
		}
		return json_encode($a);

	}

	protected static function getArray($type = ''){
		$a = static::$attrModel;
		if($type != ''){
			$a = static::trimArray($a, $type);
		}
		return $a;
	}

	protected static function trimArray($array, $type){

		switch($type){
			case "arrays":
				foreach($array as $k => $v){
					if(gettype($v) != "array"){
						unset($array[$k]);
					}
				}
			break;
			case "objects":
				foreach($array as $k => $v){
					if($v !== "object"){
						unset($array[$k]);
					}
				}
			break;
			case "table":
				foreach($array as $k => $v){
					if(gettype($v) == 'array' || $v === 'object'){
						unset($array[$k]);
					}
				}
			break;
		}

		return $array;
	}

	protected static function keysArray($type = ''){

		$className = static::$className;
		return switchKeys($className::trimArray($className::$attrModel, $type));

	}

	static function checkArray($array, $type = ''){

		try{

			$className = static::$className;

			foreach($className::getArray($type) as $k => $v){
				if(array_key_exists($k,$array) !== true){
					throw new Exception("Valor faltando: ".$k);
				}
				if(empty($array[$k]) === true && $array[$k] != 0){
					throw new Exception("Valor vazio: ".$k);
				}
			}

		}catch(Exception $e){
			die($e->getMessage());
		}

	}

}

?>
