<?php

require_once __DIR__."/php/class/quiz.php";

$request = $_GET;
$dataValidRequests = ['quiz', 'perguntas', 'respostas'];
$data;
$r;

if(isset($request['data']) !== true){
	die("No data requested");
}
if(in_array($request['data'], $dataValidRequests) !== true){
	die("Invalid data requested");
}
$data = $request['data'];

if(isset($request['id']) === true){
	$r = $data::newByID($request['id'])->getAttrs();
}else{
	$r = [];
	foreach($data::getAll() as $a){
		$r[] = $a->getAttrs();
	}
}

echo json_encode($r);

?>