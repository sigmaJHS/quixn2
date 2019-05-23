<?php

require_once __DIR__.'/../import/DBWriteClass.php';

class respostas extends DBWriteClass{

	protected static $className = __CLASS__;
	
	public static $attrModel = [
		'id' => 'int',
		'idperguntas' => 'int',
		'resposta' => 'string',
		'certa' => 'int'
	];

}

?>
