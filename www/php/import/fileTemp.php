<?php

require_once __DIR__.'/IOClass.php';

class fileTemp extends IOClass{

    protected static $className = __CLASS__;
	public static $attrModel = [
		'name' => 'string',
		'type' => 'string',
		'tmp_name' => 'string',
		'error' => 'integer',
		'size' => 'integer'
	];

	function upload($path, $fileName){

		try{
            
			if($this->get('tmp_name') == null){
				throw new Exception("Não há uma imagem para subir");
			}

            if(file_exists($path) !== true){
                mkdir($path);
            }

            return (move_uploaded_file($this->get('tmp_name'), $path."/".$fileName));

		}catch(Exception $e){
			die($e->getMessage());
		}

	}

}

?>
