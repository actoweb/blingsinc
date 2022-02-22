<?php
include_once('config.all.php');

function itensDoPedido($args=array()){

  $notaID               = $args['nota_id'];
  $nfe_numeroPedidoLoja = $args['nfe_numeroPedidoLoja'];
  $xml_nota             = $args['nfe_xml_nota'];

  $fx = simplexml_load_string($xml_nota);

  //obtem os itens da nota
  $itensNota  = $data2 = $fx->NFe->infNFe->det;
  $numNota    = (string) $data2 = $fx->NFe->infNFe->ide->nNF;
  $nfeRef     = '';
  if(isSet($fx->NFe->infNFe->ide->NFref->refNFe)){
  $nfeRef     = (string) $data2 = $fx->NFe->infNFe->ide->NFref->refNFe;
  }
  $chvNota    = (string) $data2 = $fx->protNFe->infProt->chNFe;


    //insere os itens da nota na tabela de dados
    $_itensNOTA=array();
    $item_nota = array();
    for ($i = 0; $i < count($itensNota); $i++)
    {
      $ncm  = (string) $itensNota[$i]->prod->NCM;
      $skuI = (string) $itensNota[$i]->prod->cProd;
      $desc = (string) $itensNota[$i]->prod->xProd;
      $vlr  = (string) $itensNota[$i]->prod->vProd;
      $cfop = (string) $itensNota[$i]->prod->CFOP;

      $item_nota['item_pedidoLoja'] = $nfe_numeroPedidoLoja;
      $item_nota['bs_notas_id']     = $notaID;
      $item_nota['item_numeroNota'] = $numNota;
      $item_nota['item_chave_nota'] = $chvNota;
      $item_nota['item_nfeRef']     = $nfeRef;
      $item_nota['item_sku']        = $skuI;
      $item_nota['item_ncm']        = $ncm;
      $item_nota['item_descricao']  = $desc;
      $item_nota['item_valor']      = $vlr;
      $item_nota['item_cfop']       = $cfop;
      $item_nota['item_devolvido']  = 0;
      $item_nota['item_trocado']    = 0;
      $item_nota['item_reembolsado']= 0;
      $item_nota['item_cancelado']  = 0;
      $item_nota['item_conferir']   = 0;
      $item_nota['item_numNotaDev'] = '';
      $item_nota['item_observacoes']= '';

      $_itensNOTA[] = $item_nota;


    }
    //print_r($_itensNOTA);

    return $_itensNOTA;


}

function processaItensNotas($tipoNota='S'){

  $tipoNota = strtoupper($tipoNota);

  if($tipoNota=='S' || $tipoNota=='E'){

  //listo todas as notas fiscais de saida
  $saidas = dbf('SELECT * FROM bs_notas WHERE nfe_tipo = :nfe_tipo',array(':nfe_tipo'=>$tipoNota),'fetch');

  logsys("total de notas de saida: ".count($saidas)."",false,'logsitens');
  //echo "total de notas de saída: ".count($saidas)."";

  $itemCadOK    = 0;
  $itemCadERROR = 0;
  $itemUpdOK    = 0;
  $itemUpdERROR = 0;

  for ($i = 0; $i < count($saidas); $i++)
  {
    $drow       = $saidas[$i];
    $bsNotasId  = $drow['id'];
    $xmlNota    = $drow['nfe_xml_nota'];
    $cfopNota   = $drow['nfe_cfops'];
    if($drow['nfe_numeroPedidoLoja']==null||$drow['nfe_numeroPedidoLoja']==''||!isSet($drow['nfe_numeroPedidoLoja'])){
    $numPedLoja = '0000';
    }else{
    $numPedLoja = $drow['nfe_numeroPedidoLoja'];
    }

    //verifico se este registro (ns_notas_id) ja tem itens cadastrados na tabela de itens
    //caso nao tenha entao gera registros caso contrario ignora

    $chk_itens    = dbf('SELECT * FROM bs_notas_itens WHERE bs_notas_id = :bs_notas_id',array(':bs_notas_id'=>$bsNotasId),'fetch');



    //caso nao tenha registros de itens da nota entao gera eles e cadastra
    if(count($chk_itens)==0){

      //gera itens da nota
      $args['nota_id']              = $bsNotasId;
      $args['nfe_xml_nota']         = $xmlNota;
      $args['nfe_numeroPedidoLoja'] = $numPedLoja;

      $itensNOTA = itensDoPedido($args);

      //se existirem itens na nota fiscal procede
      if(count($itensNOTA)>0){

        for ($n = 0; $n < count($itensNOTA); $n++)
        {
          $drowNFE              = $itensNOTA[$n];

          $_item_pedidoLoja     = $drowNFE['item_pedidoLoja'];
          $_bs_notas_id         = $drowNFE['bs_notas_id'];
          $_item_numeroNota     = $drowNFE['item_numeroNota'];
          $_item_chave_nota     = $drowNFE['item_chave_nota'];
          $_item_nfeRef         = $drowNFE['item_nfeRef'];
          $_item_sku            = $drowNFE['item_sku'];
          $_item_ncm            = $drowNFE['item_ncm'];
          $_item_descricao      = $drowNFE['item_descricao'];
          $_item_valor          = $drowNFE['item_valor'];
          $_item_cfop           = $drowNFE['item_cfop'];
          $_item_devolvido      = $drowNFE['item_devolvido'];
          $_item_trocado        = $drowNFE['item_trocado'];
          $_item_reembolsado    = $drowNFE['item_reembolsado'];
          $_item_cancelado      = $drowNFE['item_cancelado'];
          $_item_conferir       = $drowNFE['item_conferir'];
          $_item_numNotaDev     = $drowNFE['item_numNotaDev'];
          $_item_observacoes    = $drowNFE['item_observacoes'];


          //insere este item desta nota na tabela (bs_notas_itens)
          $insItem = dbf('INSERT bs_notas_itens SET
                          item_pedidoLoja     = :item_pedidoLoja,
                          bs_notas_id         = :bs_notas_id,
                          item_numeroNota     = :item_numeroNota,
                          item_chave_nota     = :item_chave_nota,
                          item_nfeRef         = :item_nfeRef,
                          item_sku            = :item_sku,
                          item_ncm            = :item_ncm,
                          item_descricao      = :item_descricao,
                          item_valor          = :item_valor,
                          item_cfop           = :item_cfop,
                          item_devolvido      = :item_devolvido,
                          item_trocado        = :item_trocado,
                          item_reembolsado    = :item_reembolsado,
                          item_cancelado      = :item_cancelado,
                          item_conferir       = :item_conferir,
                          item_numNotaDev     = :item_numNotaDev,
                          item_observacoes    = :item_observacoes',array(
                          ':item_pedidoLoja'  => $_item_pedidoLoja,
                          ':bs_notas_id'      => $_bs_notas_id,
                          ':item_numeroNota'  => $_item_numeroNota,
                          ':item_chave_nota'  => $_item_chave_nota,
                          ':item_nfeRef'      => $_item_nfeRef,
                          ':item_sku'         => $_item_sku,
                          ':item_ncm'         => $_item_ncm,
                          ':item_descricao'   => $_item_descricao,
                          ':item_valor'       => $_item_valor,
                          ':item_cfop'        => $_item_cfop,
                          ':item_devolvido'   => $_item_devolvido,
                          ':item_trocado'     => $_item_trocado,
                          ':item_reembolsado' => $_item_reembolsado,
                          ':item_cancelado'   => $_item_cancelado,
                          ':item_conferir'    => $_item_conferir,
                          ':item_numNotaDev'  => $_item_numNotaDev,
                          ':item_observacoes' => $_item_observacoes));
                          if($insItem>0){
                            $itemCadOK++;
                          }else{
                            $itemCadERROR++;
                          }
                          logsys("Item Cadastrado: $insItem",false,'logsitens');
                          //echo "Item Cadastrado: $insItem";

          //caso seja nota de entrada verifica se é uma nota de devolucao
          //caso seja entao usa a chave referenciada nela para assinalar o item que
          //deve ser destacado como devolvido na nota de saida que ja foi processada
          if($tipoNota=='E'){

            //echo "NOTA DE ENTRADA OK<br />Verificando CHAVE E CFOP<br />";
            logsys("NOTA DE ENTRADA OK - Verificando CHAVEs E CFOPs",false,'logsitens');

            logsys("Chave (_item_nfeRef) = $_item_nfeRef => CFOP: $cfopNota",false,'logsitens');
            //echo "<h4>Chave (_item_nfeRef) = $_item_nfeRef<br />CFOP: $cfopNota</h4>";

            //deve ter uma chave referenciada e ter cfop 1201 ou 2201
            if($_item_nfeRef!='' && ($cfopNota=='1201' || $cfopNota=='2201')){

              //neste caso deveremos localizar o item de uma nota que tenha essa chave da nfe e o mesmo SKU
              //entao se localizado vamos fazer um UPDATE no item encontrado assinalando ele como DEVOLVIDO = 1

              $setDev = dbf('UPDATE bs_notas_itens SET
                            item_devolvido    = :itemDev
                            WHERE
                            item_chave_nota   = :item_chave_nota AND
                            item_sku          = :item_sku AND
                            item_devolvido    = :zeroDev',array(
                            ':itemDev'        =>'1',
                            ':zeroDev'        =>'0',
                            ':item_chave_nota'=>$_item_nfeRef,
                            ':item_sku'       =>$_item_sku));
                          if($setDev>0){
                            $itemUpdOK++;
                          }else{
                            $itemUpdERROR++;
                          }


            }

          }

        }

      }

    }

  }

    if($tipoNota=='S'){
      logsys("TOTAL DE ITENS CADASTRADOS: $itemCadOK",false,'logsitens');
      logsys("TOTAL ERROS NO PROCESSAMENTO: $itemCadERROR",false,'logsitens');
    //echo "<h3>TOTAL DE ITENS CADASTRADOS: $itemCadOK</h3>\n";
    //echo "<h3>TOTAL ERROS NO PROCESSAMENTO: $itemCadERROR</h3>\n";
    }

    if($tipoNota=='E'){
      logsys("TOTAL DE ITENS ATUALIZADOS: $itemUpdOK",false,'logsitens');
      logsys("TOTAL ERROS NA ATUALIZACAO: $itemUpdERROR",false,'logsitens');
    //echo "<h3>TOTAL DE ITENS ATUALIZADOS: $itemUpdOK</h3>\n";
    //echo "<h3>TOTAL ERROS NA ATUALIZACAO: $itemUpdERROR</h3>\n";
    }

  }
}


logsys('Processando itens da SAIDAS',true,'logsitens');
processaItensNotas('S');

logsys('Processando itens da ENTRADAS',false,'logsitens');
processaItensNotas('E');



?>
