<?php
//funcao para cadastrar ou atualizar notas fiscais na tabela de notas
function insertNfe($args=array()){

  logsys('Iniciando cadastro na tabela de dados (insertNfe)');

  if(count($args)>0){

  logsys('...recebido dados para cadastro ($args)');

  //consulta se nota ja esta cadastrada
  $numero_nota    = $args[':nfe_numero'];//numero da NFE
  $nfe_tipo       = $args[':nfe_tipo'];//tipo nfe E entrada ou S saida
  $documento_nota = $args[':nfe_doc'];//numero do documento CPF ou CNPJ (sem pontuacao)
  $nfe_xml_link   = $args[':nfe_linkXml'];//endereco do xml da nota fiscal

  logsys('...consultando se nota ja esta cadastrada...');
  $checkNota = dbf('SELECT * FROM bs_notas
                  WHERE
                  nfe_numero  = :nfe_numero AND
                  nfe_tipo    = :nfe_tipo AND
                  nfe_doc     = :nfe_doc',array(
                  ':nfe_numero' =>$numero_nota,
                  ':nfe_tipo'   =>$nfe_tipo,
                  ':nfe_doc'    =>$documento_nota),'fetch');

    if(count($checkNota)==0){//caso nota nao esteja cadastrada entao cadastra
      logsys('...nota nova! Deve ser cadastrada, cadastrando');

      logsys('...baixando XML da nota para cadastro...');
      //retorna o xml da nota fiscal
      $xmlnota='';
      if($nfe_xml_link!=''){$xmlnota = file_get_contents($nfe_xml_link);}
      if($xmlnota!=''){
      $args[':nfe_xml_nota'] = $xmlnota;
      }else{
      $args[':nfe_xml_nota'] = '0';
      }

      logsys('...iniciando INSERT...');
      //cadastra nota na tabela de dados
      $insert = dbf('INSERT bs_notas SET
      nfe_serie              = :nfe_serie,
      nfe_numero             = :nfe_numero,
      nfe_numeroPedidoLoja   = :nfe_numeroPedidoLoja,
      nfe_tipo               = :nfe_tipo,
      nfe_loja               = :nfe_loja,
      nfe_situacao           = :nfe_situacao,
      nfe_nome               = :nfe_nome,
      nfe_doc                = :nfe_doc,
      nfe_cep                = :nfe_cep,
      nfe_uf                 = :nfe_uf,
      nfe_email              = :nfe_email,
      nfe_fone               = :nfe_fone,
      nfe_dataEmissao        = :nfe_dataEmissao,
      nfe_valorNota          = :nfe_valorNota,
      nfe_chaveAcesso        = :nfe_chaveAcesso,
      nfe_linkXml            = :nfe_linkXml,
      nfe_xml_nota           = :nfe_xml_nota,
      nfe_json_nota          = :nfe_json_nota,
      nfe_linkDanfe          = :nfe_linkDanfe,
      nfe_linkPDF            = :nfe_linkPDF,
      nfe_tipoIntegracao     = :nfe_tipoIntegracao,
      nfe_cfops              = :nfe_cfops,
      nfe_transportadora     = :nfe_transportadora,
      nfe_hash_md5           = :nfe_hash_md5',$args);

      logsys('...RESULTADO DO INSERT: ( '.$insert.' )');
      //var_dump($insert);

    }//end if checkNota == 0 (ROTINA DE CADASTRO)
    else
    {//caso nota ja exista entao atualiza ela na tabela de dados

      logsys('...NOTA JA CADASTRADA! Conferindo HASH nota: '.$checkNota[0]['nfe_numero']);

      //obtem o hash do registro cadastrado
      $hash_cadastrado = $checkNota[0]['nfe_hash_md5'];

      //caso o hash armazenado seja diferente do hash recebido em $args['nfe_hash_md5'] entao atualiza
      if($hash_cadastrado!=$args[':nfe_hash_md5']){
      logsys('...HASH diferente atualizacao necessaria');

      logsys('...baixando XML da nota para ATUALIZACAO...');
      //retorna o xml da nota fiscal
      $xmlnota='';
      if($nfe_xml_link!=''){$xmlnota = file_get_contents($nfe_xml_link);}
      if($xmlnota!=''){
      $args[':nfe_xml_nota'] = $xmlnota;
      }else{
      $args[':nfe_xml_nota'] = '0';
      }

      logsys('...iniciando UPDATE...');
      //ATUALIZA nota na tabela de dados
      $update = dbf('UPDATE bs_notas SET
      nfe_serie              = :nfe_serie,
      nfe_numero             = :nfe_numero,
      nfe_numeroPedidoLoja   = :nfe_numeroPedidoLoja,
      nfe_tipo               = :nfe_tipo,
      nfe_loja               = :nfe_loja,
      nfe_situacao           = :nfe_situacao,
      nfe_nome               = :nfe_nome,
      nfe_doc                = :nfe_doc,
      nfe_cep                = :nfe_cep,
      nfe_uf                 = :nfe_uf,
      nfe_email              = :nfe_email,
      nfe_fone               = :nfe_fone,
      nfe_dataEmissao        = :nfe_dataEmissao,
      nfe_valorNota          = :nfe_valorNota,
      nfe_chaveAcesso        = :nfe_chaveAcesso,
      nfe_linkXml            = :nfe_linkXml,
      nfe_xml_nota           = :nfe_xml_nota,
      nfe_json_nota          = :nfe_json_nota,
      nfe_linkDanfe          = :nfe_linkDanfe,
      nfe_linkPDF            = :nfe_linkPDF,
      nfe_tipoIntegracao     = :nfe_tipoIntegracao,
      nfe_cfops              = :nfe_cfops,
      nfe_transportadora     = :nfe_transportadora,
      nfe_hash_md5           = :nfe_hash_md5
      WHERE
      nfe_numero  = :nfe_numero AND
      nfe_tipo    = :nfe_tipo   AND
      nfe_doc     = :nfe_doc',$args);

      //var_dump($update);
      logsys('...RESULTADO DO UPDATE: ( '.$update.' )');
      }

    }//end if checkNota != 0 (ROTINA DE ATUALIZACAO)
  }
  logsys('*#*#*#*#*#*#*# END INSERT / UPDATE #*#*#');
}

function executeGetFiscalDocuments($url, $apikey){
    logsys('...recuperando dados da nota via CURL...');
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $url . '&apikey=' . $apikey);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($curl_handle);
    curl_close($curl_handle);
    ////// echo '<h3>* '.$url . '&apikey=' . $apikey.'</h3>';
    return $response;
}

function str2int($str=''){
  if($str!=''){
  $str  = str_replace('"','',$str);
  $int  = (int) $str;
  }else{$int = '';}
  return $int;
}

//converte monetario BR para float
function real2float($string=''){
$string = str_replace('.','',$string);
$string = str_replace(',','.',$string);
$string = str_replace('%','',$string);
$string = str_ireplace('r$','',$string);
return $string;
}

function sincNotasFiscais($args=array()){

    $pendentes    = 0;
    $emitidas     = 0;
    $canceladas   = 0;
    $agrecibo     = 0;
    $rejeitadas   = 0;
    $autorizadas  = 0;
    $emidanfe     = 0;
    $registrada   = 0;
    $agprotocolo  = 0;
    $denegada     = 0;
    $conssitu     = 0;
    $bloqueada    = 0;



    $apikey_bling = APIKEYBLING;

/*
 * antiga doca da funcao executeGetFiscalDocuments()
 * */

    $tipoNota     = '';
    $filtroTipo   = '';
    if($args['tipo']=='S'){$tipoNota = 'saida'; $filtroTipo = ';tipo[S]';}
    if($args['tipo']=='E'){$tipoNota = 'entrada'; $filtroTipo = ';tipo[E]';}

    logsys('*********************************************************');
    logsys('*********************************************************');
    logsys('PROCESSANDO NOTAS DE: '.strtoupper($tipoNota));
    logsys('*********************************************************');
    logsys('*********************************************************');

    //SE PROCESSAMENTO FOR PARA NOTAS DE SAIDA
    //if($tipoNota=='entrada'){
     $situacao      = array();
     $situacao[1]   = 'Pendente';
     $situacao[2]   = 'Emitida';
     $situacao[3]   = 'Cancelada';
     $situacao[4]   = 'Enviada - Aguardando recibo';
     $situacao[5]   = 'Rejeitada';
     $situacao[6]   = 'Autorizada';
     $situacao[7]   = 'Emitida DANFE';
     $situacao[8]   = 'Registrada';
     $situacao[9]   = 'Enviada - Aguardando protocolo';
     $situacao[10]  = 'Denegada';
     $situacao[11]  = 'Consultar situação';
     $situacao[12]  = 'Bloqueada';


     $notaProcessada=0;
     $nc=1;//counter notas processadas

            /* iremos localizar todas as notas fiscais de acordo com os 12 tipo de
            situacao possiveis */

            foreach($situacao as $key => $val){

                logsys('Esperando 1 segundo...'.date('H:i:s'));
                ob_flush();
                flush();
                sleep(1);
                logsys('Ok vamos continuar...'.date('H:i:s'));


                //situacao da NFe
                $situacao_nfe   = $key;
                //echo '<h1> status:: '.$situacao[$key].'('.$situacao_nfe.')</h1>';

                //chave de controle para o loop while
                $processa       = true;

                //pagina inicial a ser processada
                $page           = 1;

                //enquanto processa for true repete o loop
                while($processa==true){

                    //abrimos uma conexao CURL com o bling para requisitar as notas com essa situacao
                    $outputType = "json";

                    $url            = 'https://bling.com.br/Api/v2/notasfiscais/page='.$page.'/'. $outputType .'/?filters=situacao['.$situacao_nfe.']'.$filtroTipo;

                    $retorno        = executeGetFiscalDocuments($url, $apikey_bling);

                    //convertemos o retorno JSON em um array
                    $array_notas    =  (array) json_decode($retorno,true);


                    //conferimos se nao existe mensagem de erro avisando a ausencia de dados
                    if(isSet($array_notas['retorno']['erros'][0]['erro']['cod'])){
                        $retorno_ERRO = $array_notas['retorno']['erros'][0]['erro']['cod'];
                    }else{$retorno_ERRO = 0;}

                    if($retorno_ERRO==0){ //se nao for erro 14 processa

                        //selecionamos o elemento do array com a lista de notas fiscais
                        //$notas_fiscais  =  $array_notas['retorno']['notasfiscais'];
                        $notas_fiscais  =  $array_notas['retorno']['notasfiscais'];


                        //listamos o array para o seu processamento
                        for ($i=0; $i < count($notas_fiscais); $i++) {

                            //linha de dados do array
                            $drow           = $notas_fiscais[$i]['notafiscal'];
                            $nfe_json_nota  = json_encode($drow);

                            ////// echo '<h1>Nota num: '.$drow['numero'].'</h1>';

                            $insertNFe = array();

                            //obtemos os dados basicos para o cadastro na tabela de dados
                            $insertNFe[$i][':nfe_serie']              = str2int($drow['serie']);
                            $insertNFe[$i][':nfe_numero']             = str2int($drow['numero']);
                            $insertNFe[$i][':nfe_numeroPedidoLoja']   = $drow['numeroPedidoLoja'];
                            $insertNFe[$i][':nfe_tipo']               = $drow['tipo'];
                            $insertNFe[$i][':nfe_loja']               = $drow['loja'];
                            $insertNFe[$i][':nfe_situacao']           = $drow['situacao'];
                            $insertNFe[$i][':nfe_nome']               = $drow['cliente']['nome'];
                            $insertNFe[$i][':nfe_doc']                = $drow['cliente']['cnpj'];
                            $insertNFe[$i][':nfe_cep']                = $drow['cliente']['cep'];
                            $insertNFe[$i][':nfe_uf']                 = $drow['cliente']['uf'];
                            $insertNFe[$i][':nfe_email']              = $drow['cliente']['email'];
                            $insertNFe[$i][':nfe_fone']               = $drow['cliente']['fone'];
                            $insertNFe[$i][':nfe_dataEmissao']        = $drow['dataEmissao'];
                            $insertNFe[$i][':nfe_valorNota']          = $drow['valorNota'];
                            if(!isSet($drow['chaveAcesso'])||strlen($drow['chaveAcesso'])<40){
                            $insertNFe[$i][':nfe_chaveAcesso']        = '0';
                            }else{
                            $insertNFe[$i][':nfe_chaveAcesso']        = $drow['chaveAcesso'];
                            }
                            $insertNFe[$i][':nfe_linkXml']            = $drow['xml'];
                            $insertNFe[$i][':nfe_json_nota']          = $nfe_json_nota;
                            $insertNFe[$i][':nfe_linkDanfe']          = $drow['linkDanfe'];
                            $insertNFe[$i][':nfe_linkPDF']            = $drow['linkPDF'];
                            if(isSet($drow['tipoIntegracao'])){
                            $insertNFe[$i][':nfe_tipoIntegracao']     = arrayVar($drow,'tipoIntegracao',0);
                            }else{
                            $insertNFe[$i][':nfe_tipoIntegracao']     = 0;
                            }
                            $insertNFe[$i][':nfe_cfops']              = str2int($drow['cfops'][0]);
                            if(isSet($drow['transporte']['transportadora'])){
                            $insertNFe[$i][':nfe_transportadora']     = $drow['transporte']['transportadora'];
                            }else{
                            $insertNFe[$i][':nfe_transportadora']     = 0;
                            }
                            $insertNFe[$i][':nfe_hash_md5']           = md5($nfe_json_nota);
                            $notaProcessada++;


                            if($key==1){$pendentes++;}
                            if($key==2){$emitidas++;}
                            if($key==3){$canceladas++;}
                            if($key==4){$agrecibo++;}
                            if($key==5){$rejeitadas++;}
                            if($key==6){$autorizadas++;}
                            if($key==7){$emidanfe++;}
                            if($key==8){$registrada++;}
                            if($key==9){$agprotocolo++;}
                            if($key==10){$denegada++;}
                            if($key==11){$conssitu++;}
                            if($key==12){$bloqueada++;}

                            $dbNota = $insertNFe[$i];
                            insertNfe($dbNota);

                            //print_r para conferencia dos dados
                            ////// print_r($dbNota);

                        $nc++;
                        }

                        logsys('Encontradas '.$i.' notas ('.$situacao[$key].'(s)) prosseguindo para o cadastro no mysql');

                        //funcao a ser implentada, vai receber o array $insert e inserir as notas na tabela de dados
                        //insertNfe($insert,$situacao_nfe);


                    }else{  //caso seja erro 14 interrompe o processamento

                        $processa = false;

                    }
                    $page++;


            }//end loop while processa==true


            ////// echo '<h1> status:: '.$situacao[$key].'('.$situacao_nfe.')</h1>';



            }//end loop for SITUACOES


    //}//final IF notas == SAIDA

    $result = $tipoNota;

    logsys('############################################');
    logsys('Total de notas processadas: '.$notaProcessada);
    logsys('############################################');
    logsys('Notas Pendentes: '.$pendentes);
    logsys('Notas Emitidas: '.$emitidas);
    logsys('Notas Canceladas: '.$canceladas);
    logsys('Notas Aguardando recibo: '.$agrecibo);
    logsys('Notas Rejeitadas: '.$rejeitadas);
    logsys('Notas Autorizadas: '.$autorizadas);
    logsys('Notas Emitida Danfe: '.$emidanfe);
    logsys('Notas Registradas: '.$registrada);
    logsys('Notas Aguardando Protocolo: '.$agprotocolo);
    logsys('Notas Denegada(s): '.$denegada);
    logsys('Notas Consultar situacao: '.$conssitu);
    logsys('Notas Bloqueadas: '.$bloqueada);

    return $result;

}

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


function sincCSV_relatorioRedeCard($arquivo){
  logsys('Iniciando atualizacao relatorios REDECARD',true,'logs','log-rede.txt');
  $row = 1;
  if (($handle = fopen($arquivo, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $num = count($data);
          $row++;

          $dataVenda          = swdata($data[0]);
          $valorOriginal      = real2float($data[2]);
          $numParcelas        = $data[4];
          $modalidade         = $data[5];
          $tipo               = $data[6];
          $bandeira           = $data[7];
          $tarifa_percentual  = real2float($data[8]);
          $tarifa_valor       = real2float($data[12]);
          $valorLiquido       = real2float($data[13]);
          $numeroPedido       = $data[26];
          $cancelada          = $data[28];
          if(strlen($data[29])>1){
          $dataCancelamento   = swdata($data[29]);
          }else{
          $dataCancelamento   = '';
          }
          $cartao_tipo        = $data[35];

          if($dataVenda!='')          {$arrr['dataVenda']         = $dataVenda;}
          if($valorOriginal!='')      {$arrr['valorOriginal']     = $valorOriginal;}
          if($numParcelas!='')        {$arrr['numParcelas']       = $numParcelas;}
          if($modalidade!='')         {$arrr['modalidade']        = $modalidade;}
          if($tipo!='')               {$arrr['tipo']              = $tipo;}
          if($bandeira!='')           {$arrr['bandeira']          = $bandeira;}
          if($tarifa_percentual!='')  {$arrr['tarifa_percentual'] = $tarifa_percentual;}
          if($tarifa_valor!='')       {$arrr['tarifa_valor']      = $tarifa_valor;}
          if($valorLiquido!='')       {$arrr['valorLiquido']      = $valorLiquido;}
          if($numeroPedido!='')       {$arrr['numero_pedido']     = $numeroPedido;}
          if($cancelada!='')          {$arrr['cancelada_na_loja'] = $cancelada;}
          if($dataCancelamento!='')   {$arrr['dataCancelamento']  = $dataCancelamento;}
          if($cartao_tipo!='')        {$arrr['cartao_tipo']       = $cartao_tipo;}


          foreach($arrr as $key => $val){
            $caddata[$key] = $val;
          }

          logsys("Consultando se pedido($numeroPedido) item ja consta na tabela atual",false,'logs','log-rede.txt');
          //consulta se este item ja foi registrado na tabela de dados da rede
          $ch_rede = dbf('SELECT * FROM bs_redecard WHERE numero_pedido = :numero_pedido',
                        array(':numero_pedido'=>$numeroPedido),'num');

          if($ch_rede==1){//se item ja existe atualiza ele na tabela
            logsys("Pedido($numeroPedido) existe, atualizacao necessaria",false,'logs','log-rede.txt');
            $upd = updateDB('bs_redecard',$caddata,' WHERE numero_pedido = :numero_pedido');
            //echo "atualizando ($numeroPedido)= $upd<br />\n";
          }else{
            logsys("Pedido($numeroPedido) NAO EXISTE, CADASTRO necessario",false,'logs','log-rede.txt');
            $ins = insertDB('bs_redecard',$caddata);
            //echo "cadastrando ($numeroPedido)= $ins<br />\n";
          }


      }//end loop if(count($resRede)>0)


          //le toda a tabela bs_rede e copia valores dela para a tabela bs_notas
          $resRedeDB = dbf('SELECT * FROM bs_redecard ORDER BY dataVenda ASC','','fetch');

          if(count($resRedeDB)>0){

          for ($r = 0; $r < count($resRedeDB); $r++)
          {
              $resRede = $resRedeDB[$r];

              logsys("ATUALIZANDO dados da REDECARD item($r) ref ped($resRede[numero_pedido]) na nota fisca em bs_notas",false,'logs','log-rede.txt');
              $arUpdNFE=array();
              $arUpdNFE[':numero_pedido']                 = $resRede['numero_pedido'];

              $dadosVendaPlat                             = consultaPedidoPlataforma($resRede['numero_pedido']);
              $resRede['dataVenda']                       = epoch2Date($dadosVendaPlat['AcquiredDate']);

              if(!isSet($resRede['dataVenda'])||$resRede['dataVenda']==''){
              $arUpdNFE[':nfe_rede_dataVenda']            = $resRede['dataVenda'];
              }else{
              $arUpdNFE[':nfe_rede_dataVenda']            = null;
              }
              $arUpdNFE[':nfe_rede_valorOriginal']        = $resRede['valorOriginal'];
              $arUpdNFE[':nfe_rede_numParcelas']          = $resRede['numParcelas'];
              $arUpdNFE[':nfe_rede_modalidade']           = $resRede['modalidade'];
              $arUpdNFE[':nfe_rede_tipo']                 = $resRede['tipo'];
              $arUpdNFE[':nfe_rede_bandeira']             = $resRede['bandeira'];
              $arUpdNFE[':nfe_rede_ptarifa']              = $resRede['tarifa_percentual'];
              $arUpdNFE[':nfe_rede_vtarifa']              = $resRede['tarifa_valor'];
              $arUpdNFE[':nfe_rede_valorLiquido']         = $resRede['valorLiquido'];
              $arUpdNFE[':nfe_cancelada_na_loja']         = $resRede['cancelada_na_loja'];
              if(isSet($resRede['dataCancelamento']) && $resRede['dataCancelamento']!=null && $resRede['dataCancelamento']!=''){
              $arUpdNFE[':nfe_dataCancelamento']          = $resRede['dataCancelamento'];
              }else{
              $arUpdNFE[':nfe_dataCancelamento']          = null;
              }


              $upd_bsNotas = dbf('UPDATE bs_notas SET
                                nfe_rede_dataVenda        = :nfe_rede_dataVenda,
                                nfe_rede_valorOriginal		= :nfe_rede_valorOriginal,
                                nfe_rede_numParcelas			= :nfe_rede_numParcelas,
                                nfe_rede_modalidade			  = :nfe_rede_modalidade,
                                nfe_rede_tipo					    = :nfe_rede_tipo,
                                nfe_rede_bandeira				  = :nfe_rede_bandeira,
                                nfe_rede_ptarifa				  = :nfe_rede_ptarifa,
                                nfe_rede_vtarifa				  = :nfe_rede_vtarifa,
                                nfe_rede_valorLiquido			= :nfe_rede_valorLiquido,
                                nfe_cancelada_na_loja			= :nfe_cancelada_na_loja,
                                nfe_dataCancelamento			= :nfe_dataCancelamento
                                WHERE nfe_numeroPedidoLoja= :numero_pedido',$arUpdNFE);

              logsys("Resultado da atualizacao: (SE PEDIDO = $resRede[numero_pedido]) $upd_bsNotas",false,'logs','log-rede.txt');

              //localiza o numero da nota
              $chkNNota = dbf('SELECT * FROM bs_notas_itens WHERE item_pedidoLoja = :item_pedidoLoja',array(
              'item_pedidoLoja'=>$resRede['numero_pedido']
              ),'fetch');

              $numNotaDoPedido = $chkNNota[0]['item_numeroNota'];

              //depois de atualizar bs_nota entao gera as parcelas bs_parcelas
              logsys("GERANDO PARCELAS PARA O PEDIDO ($resRede[numero_pedido])",false,'logs','log-rede.txt');
              $tot_parcelas = (int) $resRede['numParcelas'];
              $dta_venda    = $resRede['dataVenda'];


              if($tot_parcelas>0){
                logsys("Total de parcelas: $tot_parcelas",false,'logs','log-rede.txt');

                $parcVctos = calcVctosParcelas($dta_venda,$tot_parcelas,30);

                for ($k = 1; $k <= $tot_parcelas; $k++)
                {
                    $num_notasPed   = $numNotaDoPedido;
                    $ordem_parcela  = "$k/$tot_parcelas";
                    $valor_parcela  = round(($resRede['valorOriginal'] / $tot_parcelas),2);

                    //dados das parcelas a serem cadastradas
                    $parcPed[':numeroNota']       = $numNotaDoPedido;
                    $vlrPARCELA                   = round(($resRede['valorOriginal'] / $tot_parcelas),2);
                    $parcPed[':vlr_parcela']      = $vlrPARCELA;
                    $parcPed[':vcto_parcela']     = $parcVctos[$k];
                    $parcPed[':ordem_parcela']    = "$k/$tot_parcelas";
                    $parcPed[':status_parcela']   = 0;


                    logsys("Conferindo se parcela nao esta cadastrada",false,'logs','log-rede.txt');
                    $chkParc = dbf('SELECT * FROM bs_parcelas WHERE
                                  numeroNota  = :numeroNota AND
                                  vlr_parcela = :vlr_parcela',array(':numeroNota'=>$numNotaDoPedido,':vlr_parcela'=>$vlrPARCELA),'fetch');

                    if(count($chkParc)==0){
                    logsys("Parcela nao existe ...inserindo parcela: ".$parcPed[':ordem_parcela']."",false,'logs','log-rede.txt');
                    //insere parcelas na tabela bs_parcela
                    $insParcela = dbf('INSERT bs_parcelas SET
                                    numeroNota      = :numeroNota,
                                    vlr_parcela     = :vlr_parcela,
                                    vcto_parcela    = :vcto_parcela,
                                    ordem_parcela   = :ordem_parcela,
                                    status_parcela  = :status_parcela',$parcPed);

                    }//endif if count($chkParc)==0

                }//end loopfor $tot_parcelas

              }//end id $tot_parcelas>1
              else{//caso contrario e uma PARCELA UNICA
                logsys("Parcela nao existe ...inserindo parcela: ".$parcPed[':ordem_parcela']."",false,'logs','log-rede.txt');

              }

          }//end loop for $resRede

      }
      logsys("Encerrando atualizacao da tabela da REDECARD",false,'logs','log-rede.txt');
      fclose($handle);
  }

}
?>
