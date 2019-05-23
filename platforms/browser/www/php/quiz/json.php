<?php

require_once '../class/quiz.php';
$u = quiz::newByID($_POST['id']);
echo $u->getJSON();

?>
