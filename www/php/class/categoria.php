<?php

require_once __DIR__.'/../import/DBReadClass.php';

class categoria extends DBReadClass{

	protected static $className = 'categoria';
	protected static $attrModel = [
		'id' => 'int',
		'nome' => 'string',
		'nome_css' => 'string'
	];

}

?>
