<?php

require_once __DIR__.'/../import/DBWriteClass.php';
require_once __DIR__.'/categoria.php';

class curso extends DBWriteClass{

	protected static $className = __CLASS__;
	public static $attrModel = [
		'id' => 'int',
		'nome' => 'string',
		'idcategoria' => 'int',
		'descricao' => 'string',
		'keywords' => []
	];

}

?>
