<?php
function arrayVar($_ARRAY,$varname='',$default=false){
  if($varname==''){
    if(isSet($_ARRAY)){return true;}else{return $default;}
  }else{
    if(isSet($_ARRAY[$varname])){
        if($_ARRAY[$varname]=='' || $_ARRAY[$varname]!=''){
          return $_ARRAY[$varname];
        }
    }
    else
    {
      return $default;
    }
  }
}


//funcao para cadastrar ou atualizar notas fiscais na tabela de notas
function insertNfe($args,$situacao){





}



function sincNotasFiscais($args=array()){
    $apikey_bling = '80abacee149bf99db5e7da4a8f371895429c1aaeb58934c8b8e5351ac4bc95bc527d5db5';

    function executeGetFiscalDocuments($url, $apikey){
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url . '&apikey=' . $apikey);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);

        echo '<h3>* '.$url . '&apikey=' . $apikey.'</h3>';
        return $response;
    }

    $tipoNota = '';
    if($args['tipo']=='S'){$tipoNota = 'saida';}
    if($args['tipo']=='E'){$tipoNota = 'entrada';}

    //SE PROCESSAMENTO FOR PARA NOTAS DE SAIDA
    if($tipoNota=='saida'){
    $situacao = array();
    //$situacao[1]  = 'Pendente';
    // $situacao[2]  = 'Emitida';
    $situacao[3]  = 'Cancelada';
    // $situacao[4]  = 'Enviada - Aguardando recibo';
    // $situacao[5]  = 'Rejeitada';
    //$situacao[6]  = 'Autorizada';
    //$situacao[7]  = 'Emitida DANFE';
    // $situacao[8]  = 'Registrada';
    // $situacao[9]  = 'Enviada - Aguardando protocolo';
     $situacao[10] = 'Denegada';
     $situacao[11] = 'Consultar situação';
     $situacao[12] = 'Bloqueada';


            /* iremos localizar todas as notas fiscais de acordo com os 12 tipo de
            situacao possiveis */

            foreach($situacao as $key => $val){

                //situacao da NFe
                $situacao_nfe   = $key;
                echo '<h1> status:: '.$situacao[$key].'('.$situacao_nfe.')</h1>';

                //chave de controle para o loop while
                $processa       = true;

                //pagina inicial a ser processada
                $page           = 1;

                //enquanto processa for true repete o loop
                while($processa==true){

                    //abrimos uma conexao CURL com o bling para requisitar as notas com essa situacao
                    $outputType = "json";

                    $url            = 'https://bling.com.br/Api/v2/notasfiscais/page='.$page.'/'. $outputType .'/?filters=situacao['.$situacao_nfe.']';

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

                        //print_r($notas_fiscais);

                        //listamos o array para o seu processamento
                        for ($i=0; $i < count($notas_fiscais); $i++) {

                            //linha de dados do array
                            $drow = $notas_fiscais[$i]['notafiscal'];

                            echo '<h1>Nota num: '.$drow['numero'].'</h1>';

                            $insertNFe = array();

                            //obtemos os dados basicos para o cadastro na tabela de dados
                            $insertNFe[$i]['nfe_serie']              = $drow['serie'];
                            $insertNFe[$i]['nfe_numero']             = $drow['numero'];
                            $insertNFe[$i]['nfe_numeroPedidoLoja']   = $drow['numeroPedidoLoja'];
                            $insertNFe[$i]['nfe_tipo']               = $drow['tipo'];
                            $insertNFe[$i]['nfe_loja']               = $drow['loja'];
                            $insertNFe[$i]['nfe_situacao']           = $drow['situacao'];
                            $insertNFe[$i]['nfe_nome']               = $drow['cliente']['nome'];
                            $insertNFe[$i]['nfe_doc']                = $drow['cliente']['cnpj'];
                            $insertNFe[$i]['nfe_cep']                = $drow['cliente']['cep'];
                            $insertNFe[$i]['nfe_uf']                 = $drow['cliente']['uf'];
                            $insertNFe[$i]['nfe_email']              = $drow['cliente']['email'];
                            $insertNFe[$i]['nfe_fone']               = $drow['cliente']['fone'];
                            $insertNFe[$i]['nfe_dataEmissao']        = $drow['dataEmissao'];
                            $insertNFe[$i]['nfe_valorNota']          = $drow['valorNota'];
                            $insertNFe[$i]['nfe_chaveAcesso']        = $drow['chaveAcesso'];
                            $insertNFe[$i]['nfe_linkXml']            = $drow['xml'];
                            $insertNFe[$i]['nfe_linkDanfe']          = $drow['linkDanfe'];
                            $insertNFe[$i]['nfe_linkPDF']            = $drow['linkPDF'];
                            if(isSet($drow['tipoIntegracao'])){
                            $insertNFe[$i]['nfe_tipoIntegracao']     = arrayVar($drow,'tipoIntegracao',0);
                            }
                            $insertNFe['nfe_cfops']                  = $drow['cfops'][0];
                            if(isSet($drow['transporte']['transportadora'])){
                            $insertNFe[$i]['nfe_transportadora']     = $drow['transporte']['transportadora'];
                            }else{
                            $insertNFe[$i]['nfe_transportadora']     = 0;
                            }
                            /* array com os dados dos volumes */

                            //funcao a ser implentada, vai receber o array $insert e inserir as notas na tabela de dados
                            //insertNfe($insert);

                            //print_r para conferencia dos dados
                            print_r($insertNFe);

                            //if($i==99){$process = false;}
                        }
                        //insertNfe($insert,$situacao_nfe);


                    }else{  //caso seja erro 14 interrompe o processamento

                        $processa = false;

                    }
                    $page++;

            }//end loop while processa==true

            }


    }//final IF notas == SAIDA

    $result = $tipoNota;

    return $result;

}




?>
