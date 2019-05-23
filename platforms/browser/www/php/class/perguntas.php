<?php

require_once __DIR__.'/../import/DBWriteClass.php';
require_once __DIR__.'/respostas.php';

class perguntas extends DBWriteClass{

	protected static $className = __CLASS__;
	
	public static $attrModel = [
		'id' => 'int',
		'idquiz' => 'int',
		'enunciado' => 'string',
		'respostas' => 'object'
	];

}

?>
