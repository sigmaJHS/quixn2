<?php

require_once __DIR__.'/IOClass.php';
require_once __DIR__.'/fileTemp.php';

class file extends IOClass{

    protected static $className = __CLASS__;
	public static $attrModel = [
		'name' => 'string',
		'path' => 'string',
		'formats' => 'array'
	];
	public $temp;

	function checkFormat(){
		if($this->get('formats') != null && $this->get('name') != null){
			return (in_array(getFormat($this->get('name')), $this->get('formats')));
		}
	}

	function upload(){
        try{
			if($this->checkFormat() !== true){
				throw new Exception("Formato inválido para o arquivo");
			}
			return $this->temp->upload($this->get('path'), $this->get('name'));
		}catch(Exception $e){
			die($e->getMessage());
		}
	}

	function getFile(){
		return $path."/".$name;
	}

	function setTemp($temp){
        try{
			if(isset($temp['tmp_name']) !== true || is_uploaded_file($temp['tmp_name']) !== true){
				throw new Exception("Valor inválido passado para file::setTemp");
			}
			$this->temp = new fileTemp($temp);
		}catch(Exception $e){
			die($e->getMessage());
		}
	}

	static function loadFile($path){
		try{
			if(file_exists($path) === false){
				return false;
			}
			$dir = str_replace("\\","/",$path);
			$dir = explode('/', $dir);
			$name = $dir[count($dir)-1];
			array_pop($dir);
			$dir = implode('/',$dir);

			$f = new file([
				'name' => $name,
				'path' => $dir
			]);
			$f->add('formats',getFormat($name));
			return $f;
		}catch(Exception $e){
			die($e->getMessage());
		}

	}

/*
    function getDir(){
        try{
            $p = $this->get('path');
            $p = str_replace('\\','/',$p);
            $p = explode('/',$p);
            unset($p[count($p)-1]);
            $p = implode("/",$p);

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

	}*/

}

?>
