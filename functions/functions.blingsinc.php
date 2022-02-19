<?php

function sincNotasFiscais($args=array()){
    global $apikey_bling;

    $tipoNota = '';
    if($args['tipo']=='S'){$tipoNota = 'saida';}
    if($args['tipo']=='E'){$tipoNota = 'entrada';}

    //SE PROCESSAMENTO FOR PARA NOTAS DE SAIDA
    if($tipoNota=='saida'){
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


            /* iremos localizar todas as notas fiscais de acordo com os 12 tipo de
            situacao possiveis */

            foreach($situacao as $key => $val){

                //situacao da NFe
                $situacao_nfe   = $key;

                //chave de controle para o loop while
                $processa       = true;

                //pagina inicial a ser processada
                $page           = 1;

                //enquanto processa for true repete o loop
                while($processa==true){

                    //abrimos uma conexao CURL com o bling para requisitar as notas com essa situacao
                    $outputType = "json";
                    function executeGetFiscalDocuments($url, $apikey){
                        $curl_handle = curl_init();
                        curl_setopt($curl_handle, CURLOPT_URL, $url . '&apikey=' . $apikey);
                        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
                        $response = curl_exec($curl_handle);
                        curl_close($curl_handle);
                        return $response;
                    }
                    
                    $url            = 'https://bling.com.br/Api/v2/notasfiscais/page='.$page.'/filters=situacao['.$situacao_nfe.']/' . $outputType .'/';
                    $retorno        = executeGetFiscalDocuments($url, $apikey_bling);
                    
                    //convertemos o retorno JSON em um array
                    $array_notas    =  (array) json_decode($retorno,true);


                    //conferimos se nao existe mensagem de erro avisando a ausencia de dados
                    if(isSet($array_notas['retorno']['erros'][0]['erro']['cod'])){
                        $retorno_ERRO = $array_notas['retorno']['erros'][0]['erro']['cod'];
                    }else{$retorno_ERRO = 0;}                

                    if($retorno_ERRO==0){ //se nao for erro 14 processa

                        //selecionamos o elemento do array com a lista de notas fiscais
                        $notas_fiscais  =  $array_notas['retorno']['notasfiscais'];

                        //listamos o array para o seu processamento
                        for ($i=0; $i < count($notas_fiscais); $i++) { 

                            //linha de dados do array
                            $drow = $notas_fiscais[$i]['notafiscal'];
                            
                            $insertNFe = array();
                            //obtemos os dados basicos para o cadastro na tabela de dados
                            $insertNFe['nfe_serie']              = $drow['serie'];
                            $insertNFe['nfe_numero']             = $drow['numero'];
                            $insertNFe['nfe_numeroPedidoLoja']   = $drow['numeroPedidoLoja'];
                            $insertNFe['nfe_tipo']               = $drow['tipo'];
                            $insertNFe['nfe_loja']               = $drow['loja'];
                            $insertNFe['nfe_situacao']           = $drow['situacao'];
                            $insertNFe['nfe_nome']               = $drow['cliente']['nome'];
                            $insertNFe['nfe_doc']                = $drow['cliente']['cnpj'];
                            $insertNFe['nfe_cep']                = $drow['cliente']['cep'];
                            $insertNFe['nfe_uf']                 = $drow['cliente']['uf'];
                            $insertNFe['nfe_email']              = $drow['cliente']['email'];
                            $insertNFe['nfe_fone']               = $drow['cliente']['fone'];
                            $insertNFe['nfe_dataEmissao']        = $drow['dataEmissao'];
                            $insertNFe['nfe_valorNota']          = $drow['valorNota'];
                            $insertNFe['nfe_chaveAcesso']        = $drow['chaveAcesso'];
                            $insertNFe['nfe_xml']                = $drow['xml'];
                            $insertNFe['nfe_linkDanfe']          = $drow['linkDanfe'];
                            $insertNFe['nfe_linkPDF']            = $drow['linkPDF'];
                            $insertNFe['nfe_tipoIntegracao']     = $drow['tipoIntegracao'];
                            $insertNFe['nfe_cfops']              = $drow['cfops'];
                            $insertNFe['nfe_transportadora']     = $drow['transporte']['transportadora'];
                            $insertNFe['nfe_volumes']            = count($drow['volumes']); /* array com os dados dos volumes */

                            //funcao a ser implentada, vai receber o array $insert e inserir as notas na tabela de dados
                            //insertNfe($insert);

                            //print_r para conferencia dos dados
                            print_r($insertNFe);

                        }

                    }else{  //caso seja erro 14 interrompe o processamento

                        $process = false;
                    
                    }
                    $page++;

            }//end loop while processa==true

            }


    }//final IF notas == SAIDA

    $result = $tipoNota;

    return $result;

}




?>