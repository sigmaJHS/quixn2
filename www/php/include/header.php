<?php

if(!isset($_SESSION)){
	session_start();
}
else
if(!isset($_SESSION['IP']) || empty($_SESSION['IP'])){
	$_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
}

?>
