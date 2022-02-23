<?php
function epoch2Date($str){
  $str = str_replace("/Date(",'',$str);
  $str = str_replace(")/",'',$str);
  $str = date("Y-m-d H:i:s", substr($str, 0, 10));
  return $str;
}

function consultaPedidoPlataforma($numPed=''){
  $dadosPedido='';
  if($numPed!=''){
    $url = 'https://laccord.layer.core.dcg.com.br/v1/Sales/API.svc/web/GetOrderByNumber';
    //$url = 'https://laccord.layer.core.dcg.com.br/v1/Sales/API.svc/web/UpdateOrder';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Accept: application/json',
      'Authorization: Basic aW50ZWdyYWNhby5taWxsZW5uaXVtOmludEBnckBjYW8xMjM=')
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$numPed");
    # Return response instead of printing.
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    # Send request.
    $result = curl_exec($ch);
    curl_close($ch);
    $dadosPedido = json_decode($result,true);
  }
  return $dadosPedido;
}

$res = consultaPedidoPlataforma('01641');
echo epoch2Date($res['AcquiredDate']);
echo '<br />';
echo $res['CustomerName'];


function getDataVendaFromRede($numPed=''){
  if($numPed!=''){
    $res        = dbf('SELECT * FROM bs_redecard WHERE numero_pedido = :numero_pedido',array(
                      ':numero_pedido'=>$numPed),'fetch');
    if(count($res)>0){
      $dataVenda =  $res[0]['dataVenda'];
      return $dataVenda;
    }else{
      return false;
    }
  }else{
    return false;
  }
}





?>
