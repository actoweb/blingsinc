<?php
include_once('config.all.php');


//$fx = simplexml_load_string($string);
$fx = simplexml_load_file('tnota.xml');
//$fx = simplexml_load_file('tnota-dev.xml');

$itensNota  = $data2 = $fx->NFe->infNFe->det;
$numNota    = $data2 = $fx->NFe->infNFe->ide->nNF;
$nfeRef     = '';
if(isSet($fx->NFe->infNFe->ide->NFref->refNFe)){
$nfeRef     = $data2 = $fx->NFe->infNFe->ide->NFref->refNFe;
}
$chvNota    = $data2 = $fx->protNFe->infProt->chNFe;

  for ($i = 0; $i < count($itensNota); $i++)
  {
    $ncm  = $itensNota[$i]->prod->NCM;
    $skuI = $itensNota[$i]->prod->cProd;
    $desc = $itensNota[$i]->prod->xProd;
    $vlr  = $itensNota[$i]->prod->vProd;
    $cfop = $itensNota[$i]->prod->CFOP;
    echo "ID.: <br />";
    echo "NumPedLoja: <br />";
    echo "PedidoRef: <br />";
    echo "NOTA: $numNota<br />";
    echo "NFe Ref.: $nfeRef<br />";
    echo "CHV: $chvNota<br />";
    echo "NCM: $ncm<br />";
    echo "SKU Item: $skuI<br />";
    echo "Desc Item: $desc<br />";
    echo "Valor Item: R$ $vlr<br />";
    echo "CFOP: $cfop<br />";
    echo "DEVOLVIDO?: <br />";
    echo "TROCADO?: <br />";
    echo "REEMBOLSADO?: <br />";
    echo "NOTA-DEV-NUM: <br />";
    echo "<hr />\n";
  }




?>
