<?php

require_once 'DBReadClass.php';

abstract class DBWriteClass extends DBReadClass{

	protected static $className = 'DBWriteClass';

	function insert(){

		try{

			$t = $this->c->inTransaction();
			if(!$t) $this->c->beginTransaction();

			$this->getExceptions();

			$className = static::$className;

			$array = $className::keysArray('insert');
			$s = $this->c->prepare("INSERT INTO ".$className." (".implode(',',$array).") VALUES (:".implode(',:',$array).")");

			foreach($array as $a){
				$s->bindValue(":".$a, $this->get($a));
			}
			$s->execute();
			$this->set('id',$this->c->lastInsertId());

			foreach(static::keysArray("arrays") as $a){
				$this->insertArray($a);
			}

			foreach(static::keysArray("objects") as $a){
				foreach($this->get($a) as $b){
					$b->set('id'.$className, $this->get('id'));
					$b->insert();
				}
			}

			if(!$t) $this->c->commit();
			return true;

		}catch(Exception $e){
			die($e->getMessage());
		}

	}

	function update(){

		try{

			$t = $this->c->inTransaction();
			if(!$t) $this->c->beginTransaction();

			$className = static::$className;

			$array = $className::keysArray('table');

			$attrs = [];
			foreach($array as $a){
				$attrs[] = $a." = :".$a;
			}

			$s = $this->c->prepare("UPDATE ".$className." SET ".implode(',',$attrs)." WHERE id= :id");

			foreach($array as $a){
				$s->bindValue(":".$a, $this->get($a));
			}

			$s->execute();

			foreach(static::keysArray("arrays") as $a){
				$this->clearArray($a);
				$this->insertArray($a);
			}

			if(!$t) $this->c->commit();
			return true;

		}catch(Exception $e){
			die($e->getMessage());
		}

	}

	function delete(){

		try{

			$t = $this->c->inTransaction();
			if(!$t) $this->c->beginTransaction();

			$className = static::$className;

			$s = $this->c->prepare("DELETE FROM ".$className." WHERE id=:id");
			$values = [':id' => $this->get('id')];

			$s->execute($values);

			if($this->c->errorCode() == 23000){
				throw new Exception("Este registro não pode ser excluído porque está sendo usado por outro(s) registro(s)");
			}

			foreach(static::keysArray("arrays") as $a){
				$this->clearArray($a);
			}

			if(!$t) $this->c->commit();
			return true;

		}catch(Exception $e){
			die($e->getMessage());
		}

	}

	private function insertArray($attr, $parent = '', $a = []){
		
		$className = static::$className;
		
		if($parent === ''){
			$parent = $className;
		}
		
		if(empty($a)){
			$a = $className::getArray("arrays");
			$a = $a[$attr];
		}
		
		
		$a = switchKeys($a[$attr]);
		print_r($a);
		$s = $this->c->prepare("INSERT INTO ".$attr." (id".$className.",".implode(',',$a).") VALUES (:id".$className.",:".implode(',:',$a).")");
		print_r("INSERT INTO ".$attr." (id".$parent.",".implode(',',$a).") VALUES (:id".$parent.",:".implode(',:',$a).")");
		$s->bindValue(":id".$className, $this->attr['id']);
		foreach($this->get($attr) as $k => $v){
			foreach($a as $b){
				$s->bindValue(":".$b, $v[$b]);
			}
			$s->execute();
		}
/*
		$className = static::$className;
		
		$a = $className::getArray("arrays");
		$a = switchKeys($a[$attr]);
		$s = $this->c->prepare("INSERT INTO ".$attr." (id".$className.",".implode(',',$a).") VALUES (:id".$className.",:".implode(',:',$a).")");
		print_r("INSERT INTO ".$attr." (id".$className.",".implode(',',$a).") VALUES (:id".$className.",:".implode(',:',$a).")");
		$s->bindValue(":id".$className, $this->attr['id']);
		foreach($this->get($attr) as $k => $v){
			foreach($a as $b){
				$s->bindValue(":".$b, $v[$b]);
			}
			$s->execute();
		}*/
	}

	private function clearArray($attr){

		$className = static::$className;

		$s = $this->c->prepare("DELETE FROM ".$attr." WHERE id".$className." = :id".$className);
		$s->bindValue(":id".$className, $this->get('id'));
		$s->execute();

	}

	protected static function trimArray($array, $type){
		$array = parent::trimArray($array, $type);
		switch($type){
			case "insert":
				$array = static::trimArray($array,"table");
				unset($array['id']);
			break;
			case "required":
				unset($array['id']);
				foreach($array as $k => $v){
					if($v === 'object'){
						unset($array[$k]);
					}
				}
			break;
		}
		return $array;
	}

	function getExceptions(){
		try{
			self::checkArray($this->attr,"required");
		}catch(Exception $e){
			die($e->getMessage());
		}
	}

}

?>
