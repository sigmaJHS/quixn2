<?php

session_start();
require_once '../class/laboratorio.php';

if(user::newByID($_SESSION['id'])->getPermission($_GET['idmenu']) < 2){
	die("Você não tem permissão para isso.");
}

$u = laboratorio::newByID($_POST['id']);

$u->setNomeLaboratorio($_POST['nome_laboratorio']);
$u->setNomeFantasia($_POST['nome_fantasia']);
$u->setCNPJ($_POST['cnpj']);
$u->setCidade(cidade::newByID($_POST['cidade']));
$u->setEndereco($_POST['endereco']);
$u->setCEP($_POST['cep']);
$u->setSite($_POST['site']);
$u->setCodigoInterno($_POST['codigo_interno']);
$u->setCodigoIBGE($_POST['codigo_ibge']);
$u->setSituacao($_POST['situacao']);

if(isset($_FILES['logo'])){
	$u->setLogo($_FILES['logo']['name'], true);
}

$u->clearTelefones();

if(isset($_POST['numero']) && !empty($_POST['numero'])){
	foreach($_POST['numero'] as $k => $v){
		$u->addTelefone($_POST['tipo'][$k],$_POST['ddd'][$k],$_POST['numero'][$k],$_POST['setor'][$k],$_POST['ramal'][$k],$_POST['contato'][$k],$_POST['situacao-telefones'][$k]);
	}
}

if($u->update()){
	echo 1;
}
	
?>
