<?php

require_once __DIR__.'/../include/c.php';
require_once __DIR__.'/IOClass.php';

abstract class DBReadClass extends IOClass{

	protected static $className = 'DBReadClass';
    protected $c;

    function __construct($array = []){
        parent::__construct($array);
        $this->c = $GLOBALS['c'];
    }

	//	construtor por ID
	static function newByID($id, $raw = false){

		$c = $GLOBALS['c'];

		try{

			if(empty($id) || $id == ''){
				throw new Exception("O ID está vazio.\n");
			}

			$className = static::$className;
			
			if($id == 0){
				return new $className();
			}
			
			$array = $className::keysArray('table');

			$s = $c->prepare("SELECT * FROM ".$className." WHERE id = :id");
			$s->bindValue(":id",$id);
			$s->execute();

			if($s->rowCount() <= 0){
				throw new Exception("Este registro (".$className.") não está cadastrado.\n");
			}

			$a = $s->fetch(PDO::FETCH_ASSOC);

			foreach($className::$attrModel as $k => $v){
				if(gettype($v) == 'array'){
					$s = $c->prepare("SELECT * FROM ".$k." WHERE id".$className." = :id");
					$s->bindValue(":id",$a['id']);
					$s->execute();
					$a[$k] = $s->fetchAll(PDO::FETCH_ASSOC);
				}else
				if($v === 'object'){
					$a[$k] = $k::getByAttr(["id".$className => $a['id']]);
				}
			}

			return ($raw === true) ? $a : new $className($a);

		}catch(Exception $e){
			die($e->getMessage());
		}

	}

    static function getAll($raw = false){

		return static::getByAttr([],$raw);

  	}
  	
  	static function getByAttr($attrs = [], $raw = false){

		$c = $GLOBALS['c'];

		try{

			$className = static::$className;
			
			$where = '';
			if(empty($attrs) === false){
				$where.= ' WHERE';
				foreach($attrs as $k => $v){
					if($where != ' WHERE'){
						$where.= ' AND';
					}
					$where.= ' '.$k.' = '.$v;
				}
			}

			$a = [];
			$s = $c->prepare("SELECT id FROM ".$className.$where);
			$s->execute();

			if($s->rowCount() > 0){

				foreach($s->fetchAll() as $v){
					$a[] = $className::newByID($v['id'],$raw);
				}

			}

			return $a;

		}catch(Exception $e){
			die($e->getMessage());
		}
		
	}

}

?>
