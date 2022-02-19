<?php

function sincNotasFiscais($args=array()){
    global $apikey_bling;

    $tipoNota = '';
    if($args['tipo']=='S'){$tipoNota = 'saida';}
    if($args['tipo']=='E'){$tipoNota = 'entrada';}

    $situacao = array();
    $situacao[1]  = 'Pendente';
    $situacao[2]  = 'Emitida';
    $situacao[3]  = 'Cancelada';
    $situacao[4]  = 'Enviada - Aguardando recibo';
    $situacao[5]  = 'Rejeitada';
    $situacao[6]  = 'Autorizada';
    $situacao[7]  = 'Emitida DANFE';
    $situacao[8]  = 'Registrada';
    $situacao[9]  = 'Enviada - Aguardando protocolo';
    $situacao[10] = 'Denegada';
    $situacao[11] = 'Consultar situação';
    $situacao[12] = 'Bloqueada';


}




?>