<?php
//funcao para cadastrar ou atualizar notas fiscais na tabela de notas
function insertNfe($args=array()){

  if(count($args)>0){

  //consulta se nota ja esta cadastrada
  $numero_nota    = $args[':nfe_numero'];//numero da NFE
  $nfe_tipo       = $args[':nfe_tipo'];//tipo nfe E entrada ou S saida
  $documento_nota = $args[':nfe_doc'];//numero do documento CPF ou CNPJ (sem pontuacao)
  $nfe_xml_link   = $args[':nfe_linkXml'];//endereco do xml da nota fiscal

  //retorna o xml da nota fiscal
  $xmlnota='';
  if($nfe_xml_link!=''){$xmlnota = file_get_contents($nfe_xml_link);}
  if($xmlnota!=''){
  $args[':nfe_xml_nota'] = $xmlnota;
  }else{
  $args[':nfe_xml_nota'] = '0';
  }

  $checkNota = dbf('SELECT * FROM bs_notas
                  WHERE
                  nfe_numero  = :nfe_numero AND
                  nfe_tipo    = :nfe_tipo AND
                  nfe_doc     = :nfe_doc',array(
                  ':nfe_numero' =>$numero_nota,
                  ':nfe_tipo'   =>$nfe_tipo,
                  ':nfe_doc'    =>$documento_nota),'num');

    if($checkNota==0){//caso nota nao esteja cadastrada em cadastra ela

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
      nfe_transportadora     = :nfe_transportadora',$args);

      var_dump($insert);

    }//end if checkNota == 0 (ROTINA DE CADASTRO)
    else
    {//caso nota ja exista entao atualiza ela na tabela de dados

      //cadastra nota na tabela de dados
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
      nfe_transportadora     = :nfe_transportadora
      WHERE
      nfe_numero  = :nfe_numero AND
      nfe_tipo    = :nfe_tipo   AND
      nfe_doc     = :nfe_doc',$args);

      var_dump($update);

    }//end if checkNota != 0 (ROTINA DE ATUALIZACAO)
  }
}

function executeGetFiscalDocuments($url, $apikey){
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $url . '&apikey=' . $apikey);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($curl_handle);
    curl_close($curl_handle);

    echo '<h3>* '.$url . '&apikey=' . $apikey.'</h3>';
    return $response;
}

function str2int($str=''){
  if($str!=''){
  $str  = str_replace('"','',$str);
  $int  = (int) $str;
  }else{$int = '';}
  return $int;
}


function sincNotasFiscais($args=array()){

    $pendentes=0;
    $emitidas=0;
    $canceladas=0;
    $agrecibo=0;
    $rejeitadas=0;
    $autorizadas=0;
    $emidanfe=0;
    $registrada=0;
    $agprotocolo=0;
    $denegada=0;
    $conssitu=0;
    $bloqueada=0;

    logsys('iniciando sincronizacao das notas fiscais');

    $apikey_bling = APIKEYBLING;

/*
 * antiga doca da funcao executeGetFiscalDocuments()
 * */

    $tipoNota     = '';
    $filtroTipo   = '';
    if($args['tipo']=='S'){$tipoNota = 'saida'; $filtroTipo = ';tipo[S]';}
    if($args['tipo']=='E'){$tipoNota = 'entrada'; $filtroTipo = ';tipo[E]';}

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

                            echo '<h1>Nota num: '.$drow['numero'].'</h1>';

                            $insertNFe = array();

                            //obtemos os dados basicos para o cadastro na tabela de dados
                            //$insertNFe[$i]['counter']                = $nc;
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
                            print_r($dbNota);

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


            echo '<h1> status:: '.$situacao[$key].'('.$situacao_nfe.')</h1>';



            }//end loop for SITUACOES


    //}//final IF notas == SAIDA

    $result = $tipoNota;

    logsys('############################################');
    logsys('Total de notas processadas: '.$notaProcessada);
    logsys('############################################');
    logsys('Notas pendentes: '.$pendentes);
    logsys('Notas emitidas: '.$emitidas);
    logsys('Notas canceladas: '.$canceladas);
    logsys('Notas agrecibo: '.$agrecibo);
    logsys('Notas rejeitadas: '.$rejeitadas);
    logsys('Notas autorizadas: '.$autorizadas);
    logsys('Notas emidanfe: '.$emidanfe);
    logsys('Notas registrada: '.$registrada);
    logsys('Notas agprotocolo: '.$agprotocolo);
    logsys('Notas denegada: '.$denegada);
    logsys('Notas conssitu: '.$conssitu);
    logsys('Notas bloqueada: '.$bloqueada);

    return $result;

}




?>
