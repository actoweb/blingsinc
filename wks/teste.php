<?php
include_once('../functions/functions.blingsinc.php');

$args = array('tipo'=>'S');
$res = sincNotasFiscais($args);

echo $res;
?>