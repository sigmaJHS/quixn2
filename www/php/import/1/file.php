<?php

if(!isset($_SESSION)){
	session_start();
}
require_once 'conexao.php';

class file extends ItsClass{

    protected static $className = 'file';

    protected $attr;

	function __construct($array = []){
		$this->construct();
        if(empty($array)){
            $this->fill(ItsClass::switchKeys(self::$className::getArray()),self::$className);
        }else{
            $this->fill($array,self::$className);
        }
	}

	static function getArray($type = ''){

		$a = [
			'temp' => [
                'name' => 'string',
                'type' => 'string',
                'tmp_name' => 'string',
                'error' => 'int',
                'size' => 'int'
            ],
			'format' => [],
			'path' => 'string'
		];

		switch($type){
			case "arrays":
				foreach($a as $k => $v){
					if(gettype($v) != "array"){
						unset($a[$k]);
					}
				}
			break;
		}

		return $a;
	}

    function getDir(){
        try{
            $p = $this->get('path');
            $p = str_replace('\\','/',$p);
            $p = explode('/',$p);
            unset($p[count($p)-1]);
            $p = implode("/",$p);
            print_r($p);

        }catch(Exception $e){
            die($e->getMessage());
        }
    }

	function upload($path, $fileName){

		try{

			if(empty($this->get('temp'))){
				throw new Exception("Não há uma imagem para subir");
			}

            if(!is_uploaded_file($this->get('temp'))){
                throw new Exception("Este arquivo não é válido");
            }

            if(!empty($this->get('format'))){

                $valid = false;
                $a = explode('.',$this->get('files'));
                $a = $a[count($a)-1];

                foreach($this->get('format') as $b){
                    if($a == $b){
                        $valid = true;
                    }
                }

                if($valid === false){
                    throw new Exception("Formato inválido arquivo para este arquivo");
                }

            }

			if(!file_exists($this->get('path'))){
				mkdir($this->get('path'));
			}

            if(move_uploaded_file($this->files['tmp_name'], $path."/".$fileName)){
                $this->path = $path;
                $this->fileName = $fileName;
                return true;
            }else{
                return false;
            }

		}catch(Exception $e){
			die($e->getMessage());
		}

	}

}

?>
