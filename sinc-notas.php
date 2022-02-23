<?php
include_once('config.all.php');
$hr         = date('H');
$hora       = (int)$hr;
$diaSemana  = date('w');
if($hora>6 && $hora<=19 && $diaSemana <= 5){
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>REPORTS GEN</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>

<?php

logsys('iniciando sincronizacao das notas fiscais',true);

sincNotasFiscais(array('tipo'=>'S'));

ob_flush();
flush();
sleep(5);

sincNotasFiscais(array('tipo'=>'E'));

ob_flush();
flush();
sleep(5);

logsys('Processando itens da SAIDAS',true,'logsitens');
processaItensNotas('S');

ob_flush();
flush();
sleep(5);

logsys('Processando itens da ENTRADAS',false,'logsitens');
processaItensNotas('E');


?>

</body>
</html>

<?php
}
?>
