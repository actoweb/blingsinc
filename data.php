<?php
function calcVctosParcelas($dataVenda,$parcelas,$intervalo=30){
  $vencimentos=array();
  if(strstr($dataVenda,' ')){
  $a          = explode(' ',$dataVenda);
  $vendidoEm  = $a[0];
  }else{
  $vendidoEm  = $dataVenda;
  }

  for ($i = 1; $i <= $parcelas; $i++)
  {
    if($i==1){
    $vcto = date('Y-m-d', strtotime( $vendidoEm . '+30 days'));
    }else{
    $vcto = date('Y-m-d', strtotime( $vcto . '+30 days'));
    }
    $vencimentos[$i]  = $vcto;
  }
  return $vencimentos;
}

$res = calcVctos('2022-03-16',3,30);

for ($i = 1; $i <= count($res); $i++)
{
  $drow=$res[$i];
  echo "Vencimento: $drow<br />\n";
}



?>
