<?php
	require_once '../class/laboratorio.php';

	if(user::newByID($_SESSION['id'])->getPermission($_GET['idmenu']) < 2){
		die("Você não tem permissão para isso.");
	}
	
	$u = laboratorio::newByID($_GET['id']);
	if($u->delete()){
		echo 1;
	}
	
?>