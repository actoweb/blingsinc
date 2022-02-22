<?php
include_once('config.all.php');

function cadItensDoPedido($args=array()){

  $notaID               = $args['nota_id'];
  $nfe_numeroPedidoLoja = $args['nfe_numeroPedidoLoja'];
  $xml_nota             = $args['nfe_xml_nota'];

  $fx = simplexml_load_string($xml_nota);

  //obtem os itens da nota
  $itensNota  = $data2 = $fx->NFe->infNFe->det;
  $numNota    = $data2 = $fx->NFe->infNFe->ide->nNF;
  $nfeRef     = '';
  if(isSet($fx->NFe->infNFe->ide->NFref->refNFe)){
  $nfeRef     = $data2 = $fx->NFe->infNFe->ide->NFref->refNFe;
  }
  $chvNota    = $data2 = $fx->protNFe->infProt->chNFe;


    //insere os itens da nota na tabela de dados
    $item_nota = array();
    for ($i = 0; $i < count($itensNota); $i++)
    {
      $ncm  = $itensNota[$i]->prod->NCM;
      $skuI = $itensNota[$i]->prod->cProd;
      $desc = $itensNota[$i]->prod->xProd;
      $vlr  = $itensNota[$i]->prod->vProd;
      $cfop = $itensNota[$i]->prod->CFOP;

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
      $item_nota['item_numNotaDev'] = $cfop;
      $item_nota['item_observacoes']= $cfop;

      print_r($item_nota);

    }

}

$nfe1 = '<?xml version="1.0" encoding="UTF-8"?><nfeProc xmlns="http://www.portalfiscal.inf.br/nfe" versao="4.00"><NFe xmlns="http://www.portalfiscal.inf.br/nfe"><infNFe versao="4.00" Id="NFe42211241747186000124550010000002991066814250"><ide><cUF>42</cUF><cNF>06681425</cNF><natOp>Venda de mercadoria adq de terceiros, dest nao contribuinte</natOp><mod>55</mod><serie>1</serie><nNF>299</nNF><dhEmi>2021-12-30T11:35:54-03:00</dhEmi><dhSaiEnt>2021-12-30T11:35:54-03:00</dhSaiEnt><tpNF>1</tpNF><idDest>2</idDest><cMunFG>4204608</cMunFG><tpImp>1</tpImp><tpEmis>1</tpEmis><cDV>0</cDV><tpAmb>1</tpAmb><finNFe>1</finNFe><indFinal>1</indFinal><indPres>2</indPres><indIntermed>0</indIntermed><procEmi>0</procEmi><verProc>Bling 1.0</verProc></ide><emit><CNPJ>41747186000124</CNPJ><xNome>LACCORD BRASIL LTDA</xNome><xFant>LACCORD</xFant><enderEmit><xLgr>Ac Estadual Rio Maina</xLgr><nro>1420</nro><xCpl>SL 01</xCpl><xBairro>Vila Macarini</xBairro><cMun>4204608</cMun><xMun>Criciuma</xMun><UF>SC</UF><CEP>88818800</CEP><cPais>1058</cPais><xPais>Brasil</xPais><fone>4834449469</fone></enderEmit><IE>261046497</IE><IEST>12216688</IEST><CRT>3</CRT></emit><dest><CPF>11653075708</CPF><xNome>Andressa Veiga Moreira</xNome><enderDest><xLgr>Rua Imboassu</xLgr><nro>232</nro><xCpl>Bl 3 707</xCpl><xBairro>Boacu</xBairro><cMun>3304904</cMun><xMun>Sao Goncalo</xMun><UF>RJ</UF><CEP>24465220</CEP><cPais>1058</cPais><xPais>Brasil</xPais><fone>21982072444</fone></enderDest><indIEDest>9</indIEDest><email>dressa_veiga@hotmail.com</email></dest><det nItem="1"><prod><cProd>SHVE220006LAB36</cProd><cEAN>SEM GTIN</cEAN><xProd>SHORT CINTURA ALTA Tamanho:36</xProd><NCM>61046900</NCM><CFOP>6108</CFOP><uCom>UN</uCom><qCom>1.0000</qCom><vUnCom>237.00</vUnCom><vProd>237.00</vProd><cEANTrib>SEM GTIN</cEANTrib><uTrib>UN</uTrib><qTrib>1.0000</qTrib><vUnTrib>237.00</vUnTrib><indTot>1</indTot><nItemPed>1</nItemPed></prod><imposto><vTotTrib>41.12</vTotTrib><ICMS><ICMS00><orig>0</orig><CST>00</CST><modBC>3</modBC><vBC>237.00</vBC><pICMS>12.0000</pICMS><vICMS>28.44</vICMS></ICMS00></ICMS><PIS><PISAliq><CST>01</CST><vBC>237.00</vBC><pPIS>0.65</pPIS><vPIS>1.54</vPIS></PISAliq></PIS><COFINS><COFINSAliq><CST>01</CST><vBC>237.00</vBC><pCOFINS>3.00</pCOFINS><vCOFINS>7.11</vCOFINS></COFINSAliq></COFINS><ICMSUFDest><vBCUFDest>237.00</vBCUFDest><vBCFCPUFDest>237.00</vBCFCPUFDest><pFCPUFDest>2.0000</pFCPUFDest><pICMSUFDest>18.0000</pICMSUFDest><pICMSInter>12.00</pICMSInter><pICMSInterPart>100.0000</pICMSInterPart><vFCPUFDest>4.74</vFCPUFDest><vICMSUFDest>14.22</vICMSUFDest><vICMSUFRemet>0.00</vICMSUFRemet></ICMSUFDest></imposto></det><det nItem="2"><prod><cProd>SAVE220003LAB36</cProd><cEAN>SEM GTIN</cEAN><xProd>SAIA EVASE CINTURA ALTA RESINADA Tamanho:36</xProd><NCM>62045200</NCM><CFOP>6108</CFOP><uCom>UN</uCom><qCom>1.0000</qCom><vUnCom>277.00</vUnCom><vProd>277.00</vProd><cEANTrib>SEM GTIN</cEANTrib><uTrib>UN</uTrib><qTrib>1.0000</qTrib><vUnTrib>277.00</vUnTrib><indTot>1</indTot><nItemPed>2</nItemPed></prod><imposto><vTotTrib>48.06</vTotTrib><ICMS><ICMS00><orig>0</orig><CST>00</CST><modBC>3</modBC><vBC>277.00</vBC><pICMS>12.0000</pICMS><vICMS>33.24</vICMS></ICMS00></ICMS><PIS><PISAliq><CST>01</CST><vBC>277.00</vBC><pPIS>0.65</pPIS><vPIS>1.80</vPIS></PISAliq></PIS><COFINS><COFINSAliq><CST>01</CST><vBC>277.00</vBC><pCOFINS>3.00</pCOFINS><vCOFINS>8.31</vCOFINS></COFINSAliq></COFINS><ICMSUFDest><vBCUFDest>277.00</vBCUFDest><vBCFCPUFDest>277.00</vBCFCPUFDest><pFCPUFDest>2.0000</pFCPUFDest><pICMSUFDest>18.0000</pICMSUFDest><pICMSInter>12.00</pICMSInter><pICMSInterPart>100.0000</pICMSInterPart><vFCPUFDest>5.54</vFCPUFDest><vICMSUFDest>16.62</vICMSUFDest><vICMSUFRemet>0.00</vICMSUFRemet></ICMSUFDest></imposto></det><det nItem="3"><prod><cProd>SHVE220003LA36</cProd><cEAN>SEM GTIN</cEAN><xProd>SHORT EVASE CINTURA ALTA RESINADO Tamanho:36</xProd><NCM>61046900</NCM><CFOP>6108</CFOP><uCom>UN</uCom><qCom>1.0000</qCom><vUnCom>277.00</vUnCom><vProd>277.00</vProd><cEANTrib>SEM GTIN</cEANTrib><uTrib>UN</uTrib><qTrib>1.0000</qTrib><vUnTrib>277.00</vUnTrib><indTot>1</indTot><nItemPed>3</nItemPed></prod><imposto><vTotTrib>48.06</vTotTrib><ICMS><ICMS00><orig>0</orig><CST>00</CST><modBC>3</modBC><vBC>277.00</vBC><pICMS>12.0000</pICMS><vICMS>33.24</vICMS></ICMS00></ICMS><PIS><PISAliq><CST>01</CST><vBC>277.00</vBC><pPIS>0.65</pPIS><vPIS>1.80</vPIS></PISAliq></PIS><COFINS><COFINSAliq><CST>01</CST><vBC>277.00</vBC><pCOFINS>3.00</pCOFINS><vCOFINS>8.31</vCOFINS></COFINSAliq></COFINS><ICMSUFDest><vBCUFDest>277.00</vBCUFDest><vBCFCPUFDest>277.00</vBCFCPUFDest><pFCPUFDest>2.0000</pFCPUFDest><pICMSUFDest>18.0000</pICMSUFDest><pICMSInter>12.00</pICMSInter><pICMSInterPart>100.0000</pICMSInterPart><vFCPUFDest>5.54</vFCPUFDest><vICMSUFDest>16.62</vICMSUFDest><vICMSUFRemet>0.00</vICMSUFRemet></ICMSUFDest></imposto></det><total><ICMSTot><vBC>791.00</vBC><vICMS>94.92</vICMS><vICMSDeson>0.00</vICMSDeson><vFCPUFDest>15.82</vFCPUFDest><vICMSUFDest>47.46</vICMSUFDest><vFCP>0.00</vFCP><vBCST>0.00</vBCST><vST>0.00</vST><vFCPST>0.00</vFCPST><vFCPSTRet>0.00</vFCPSTRet><vProd>791.00</vProd><vFrete>0.00</vFrete><vSeg>0.00</vSeg><vDesc>0.00</vDesc><vII>0.00</vII><vIPI>0.00</vIPI><vIPIDevol>0.00</vIPIDevol><vPIS>5.14</vPIS><vCOFINS>23.73</vCOFINS><vOutro>0.00</vOutro><vNF>791.00</vNF><vTotTrib>137.24</vTotTrib></ICMSTot></total><transp><modFrete>0</modFrete><transporta><CNPJ>17856244000173</CNPJ><xNome>E-COMMERCE-LOG BRASIL COLETAS E ENTREGAS DE ENCOMENDAS LTDA</xNome><IE>258218770</IE><xEnder>Rua Irene Dal Pont Milioli</xEnder><xMun>Criciuma</xMun><UF>SC</UF></transporta><vol><qVol>1</qVol><esp>Volumes</esp><pesoL>1.060</pesoL><pesoB>0.000</pesoB></vol></transp><cobr><fat><nFat>000299</nFat><vOrig>791.00</vOrig><vDesc>0</vDesc><vLiq>791.00</vLiq></fat><dup><nDup>001</nDup><dVenc>2021-12-30</dVenc><vDup>791.00</vDup></dup></cobr><pag><detPag><tPag>04</tPag><vPag>791.00</vPag><card><tpIntegra>2</tpIntegra><tBand>02</tBand></card></detPag></pag><infAdic><infCpl>Total aproximado de tributos: R$ 137,24 (17,35%)   .&lt;br /&gt;Operacao contratada no ambito do comercio eletronico ou de telemarketing EC87. Inscricao substituto: 12216688&lt;br /&gt;Valor do ICMS DIFAL para UF de destino R$ 47,46&lt;br /&gt;Valor do FCP R$ 15,82&lt;br /&gt;</infCpl></infAdic><infRespTec><CNPJ>01056417000139</CNPJ><xContato>Organisys Software SA</xContato><email>fiscal@bling.com.br</email><fone>05430579470</fone></infRespTec></infNFe><Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/><Reference URI="#NFe42211241747186000124550010000002991066814250"><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/><Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/><DigestValue>aNwbpKzCRdSuVRf6LhIEoKfzazc=</DigestValue></Reference></SignedInfo><SignatureValue>lyedes2Ey8JaNS/2uzZ1EBigkCHdYVnjeyuUMmRJAs+ui33FgaBhzm+vSPFPMy6rwNGImF+XBgzGj1gZatwC/Q2r9eUFw733/6DKcDN0A30CfurWJiEpBb0n/8kZSLQE0yh1J7/qHkSBOBkbXqm1IpTpG7/awXkTJ6eIZOFg2Uiu/X08ALbg3NN/yMd9I0lQf+zWntAkwSIMCh/zFiAypGdW3PpPI2fnrtZETHA5T2ydrKbv2M00aejo1WKwLkMvO3MSl362YRxgj4j6Lz7LjhvcEF+c+FzqIqis0lwcawa6llenyzlw04Sqb5eM+FI4IYmWO4fQfz86SXVsJsG56Q==</SignatureValue><KeyInfo><X509Data><X509Certificate>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</X509Certificate></X509Data></KeyInfo></Signature></NFe><protNFe versao="4.00"><infProt><tpAmb>1</tpAmb><verAplic>SVRS202112300918</verAplic><chNFe>42211241747186000124550010000002991066814250</chNFe><dhRecbto>2021-12-30T11:35:54-03:00</dhRecbto><nProt>342210248897387</nProt><digVal>aNwbpKzCRdSuVRf6LhIEoKfzazc=</digVal><cStat>100</cStat><xMotivo>Autorizado o uso da NF-e</xMotivo></infProt></protNFe></nfeProc>';
$nfe2 = '<?xml version="1.0" encoding="UTF-8"?><nfeProc xmlns="http://www.portalfiscal.inf.br/nfe" versao="4.00"><NFe xmlns="http://www.portalfiscal.inf.br/nfe"><infNFe versao="4.00" Id="NFe42220241747186000124550010000006211094456264"><ide><cUF>42</cUF><cNF>09445626</cNF><natOp>Devolucao de venda de mercadoria adq ou rec de terceiros</natOp><mod>55</mod><serie>1</serie><nNF>621</nNF><dhEmi>2022-02-01T12:00:56-03:00</dhEmi><dhSaiEnt>2022-02-01T12:00:56-03:00</dhSaiEnt><tpNF>0</tpNF><idDest>2</idDest><cMunFG>4204608</cMunFG><tpImp>1</tpImp><tpEmis>1</tpEmis><cDV>4</cDV><tpAmb>1</tpAmb><finNFe>4</finNFe><indFinal>1</indFinal><indPres>9</indPres><indIntermed>0</indIntermed><procEmi>0</procEmi><verProc>Bling 1.0</verProc><NFref><refNFe>42211241747186000124550010000002991066814250</refNFe></NFref></ide><emit><CNPJ>41747186000124</CNPJ><xNome>LACCORD BRASIL LTDA</xNome><xFant>LACCORD</xFant><enderEmit><xLgr>Ac Estadual Rio Maina</xLgr><nro>1420</nro><xCpl>SL 01</xCpl><xBairro>Vila Macarini</xBairro><cMun>4204608</cMun><xMun>Criciuma</xMun><UF>SC</UF><CEP>88818800</CEP><cPais>1058</cPais><xPais>Brasil</xPais><fone>4834449469</fone></enderEmit><IE>261046497</IE><IEST>12216688</IEST><CRT>3</CRT></emit><dest><CPF>11653075708</CPF><xNome>Andressa Veiga Moreira</xNome><enderDest><xLgr>Rua Imboassu</xLgr><nro>232</nro><xCpl>Bl 3 707</xCpl><xBairro>Boacu</xBairro><cMun>3304904</cMun><xMun>Sao Goncalo</xMun><UF>RJ</UF><CEP>24465220</CEP><cPais>1058</cPais><xPais>Brasil</xPais><fone>21982072444</fone></enderDest><indIEDest>9</indIEDest><email>dressa_veiga@hotmail.com</email></dest><det nItem="1"><prod><cProd>SHVE220003LA36</cProd><cEAN>SEM GTIN</cEAN><xProd>SHORT EVASE CINTURA ALTA RESINADO Tamanho:36</xProd><NCM>61046900</NCM><CFOP>2202</CFOP><uCom>UN</uCom><qCom>1.0000</qCom><vUnCom>277.00</vUnCom><vProd>277.00</vProd><cEANTrib>SEM GTIN</cEANTrib><uTrib>UN</uTrib><qTrib>1.0000</qTrib><vUnTrib>277.00</vUnTrib><indTot>1</indTot><nItemPed>3</nItemPed></prod><imposto><vTotTrib>48.06</vTotTrib><ICMS><ICMS00><orig>0</orig><CST>00</CST><modBC>3</modBC><vBC>277.00</vBC><pICMS>12.0000</pICMS><vICMS>33.24</vICMS></ICMS00></ICMS><PIS><PISNT><CST>07</CST></PISNT></PIS><COFINS><COFINSNT><CST>07</CST></COFINSNT></COFINS><ICMSUFDest><vBCUFDest>277.00</vBCUFDest><vBCFCPUFDest>277.00</vBCFCPUFDest><pFCPUFDest>2.0000</pFCPUFDest><pICMSUFDest>18.0000</pICMSUFDest><pICMSInter>12.00</pICMSInter><pICMSInterPart>100.0000</pICMSInterPart><vFCPUFDest>5.54</vFCPUFDest><vICMSUFDest>16.62</vICMSUFDest><vICMSUFRemet>0.00</vICMSUFRemet></ICMSUFDest></imposto></det><total><ICMSTot><vBC>277.00</vBC><vICMS>33.24</vICMS><vICMSDeson>0.00</vICMSDeson><vFCPUFDest>5.54</vFCPUFDest><vICMSUFDest>16.62</vICMSUFDest><vFCP>0.00</vFCP><vBCST>0.00</vBCST><vST>0.00</vST><vFCPST>0.00</vFCPST><vFCPSTRet>0.00</vFCPSTRet><vProd>277.00</vProd><vFrete>0.00</vFrete><vSeg>0.00</vSeg><vDesc>0.00</vDesc><vII>0.00</vII><vIPI>0.00</vIPI><vIPIDevol>0.00</vIPIDevol><vPIS>0.00</vPIS><vCOFINS>0.00</vCOFINS><vOutro>0.00</vOutro><vNF>277.00</vNF><vTotTrib>48.06</vTotTrib></ICMSTot></total><transp><modFrete>0</modFrete><transporta><CNPJ>17856244000173</CNPJ><xNome>E-COMMERCE-LOG BRASIL COLETAS E ENTREGAS DE ENCOMENDAS LTDA</xNome><IE>258218770</IE><xEnder>Rua Irene Dal Pont Milioli</xEnder><xMun>Criciuma</xMun><UF>SC</UF></transporta><vol><qVol>1</qVol><esp>Volumes</esp><pesoL>0.232</pesoL><pesoB>0.000</pesoB></vol></transp><pag><detPag><tPag>90</tPag><vPag>0.00</vPag></detPag></pag><infAdic><infCpl>Total aproximado de tributos: R$ 48,06 (17,35%)   .&lt;br /&gt;Operacao contratada no ambito do comercio eletronico ou de telemarketing EC87. Inscricao substituto: 12216688&lt;br /&gt;Valor do ICMS DIFAL para UF de destino R$ 16,62&lt;br /&gt;Valor do FCP R$ 5,54&lt;br /&gt;Troca por credito no site NF de venda ref 299</infCpl></infAdic><infRespTec><CNPJ>01056417000139</CNPJ><xContato>Organisys Software SA</xContato><email>fiscal@bling.com.br</email><fone>05430579470</fone></infRespTec></infNFe><Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/><Reference URI="#NFe42220241747186000124550010000006211094456264"><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/><Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/><DigestValue>T49zYK0ZwnJ7u+bcsY9PVYuwkFQ=</DigestValue></Reference></SignedInfo><SignatureValue>PdcS0jeBgMPN657/rJNVQA41OP+g1u4/TNj4Y63cF91hOprPGq9YippuiUfR3oR6OIdrv5zES+jD0byNCaMi1NihT+jEGlzynTNPAabOuL0UhNBWUB/LIb6E9nDSoBiS8GK1kajUTMvw3U4cq8ghiKpM4wQ1MEGuaV6HFYDHT42ckNUDQgBvSMa9hpfb+IFu3VM53Rzba1U+wnM30vQbr4Uivy3WXsTIxvA5UbenqCH4EFthlD2NZa+untmiZR0U+qBAwMwMq1Qb2TvwWjoKOJr3ctX3DoHgIQruGL5L7hhJ/fe98UO5Y4q9rBOozN58sT/BZECarThjNGsoZHA5pg==</SignatureValue><KeyInfo><X509Data><X509Certificate>MIIHQDCCBSigAwIBAgIIUx4hBhY5l/QwDQYJKoZIhvcNAQELBQAwWTELMAkGA1UEBhMCQlIxEzARBgNVBAoTCklDUC1CcmFzaWwxFTATBgNVBAsTDEFDIFNPTFVUSSB2NTEeMBwGA1UEAxMVQUMgU09MVVRJIE11bHRpcGxhIHY1MB4XDTIxMDYxNjE3MTgwMFoXDTIyMDYxNjE3MTgwMFowgdoxCzAJBgNVBAYTAkJSMRMwEQYDVQQKEwpJQ1AtQnJhc2lsMQswCQYDVQQIEwJTQzERMA8GA1UEBxMIQ3JpY2l1bWExHjAcBgNVBAsTFUFDIFNPTFVUSSBNdWx0aXBsYSB2NTEXMBUGA1UECxMOMjY0MTA4NjMwMDAxMjAxEzARBgNVBAsTClByZXNlbmNpYWwxGjAYBgNVBAsTEUNlcnRpZmljYWRvIFBKIEExMSwwKgYDVQQDEyNMJ0FDQ09SRCBCUkFTSUwgTFREQTo0MTc0NzE4NjAwMDEyNDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALlAU2Kepk1Rxu0EcVKVCfj0VgjForzvBoWIvkGmzUMUCFr0rsfasbLETIFIMXENcVX9qx3/I+NoHk6cTSJQOJ1S2Di0bst7ZShx6PYQNq5XikPyMbtcI7OinKqcBmvU4Vu2tcH8GcHQeLWJO3gyFfA0GgMpZxrhYTplMMWvEg+sCB2vmOiq0jOZ8pXnqYUBXB1+8gq6eBSLWRppXRP5daQoaqzcLxh7WKxZyW+mbvNoroYmgXzvbkF9L1ecPL4/7vHLY9KALwkXmL9Y14KLZVnHHzRuK1aAF2lzpIVtjw8IWB+wNWryxoAqT33K9uTIWe2W7D1bqqSLMnHtwNnJbWsCAwEAAaOCAogwggKEMAkGA1UdEwQCMAAwHwYDVR0jBBgwFoAUxVLtJYAJ35yCyJ9Hxt20XzHdubEwVAYIKwYBBQUHAQEESDBGMEQGCCsGAQUFBzAChjhodHRwOi8vY2NkLmFjc29sdXRpLmNvbS5ici9sY3IvYWMtc29sdXRpLW11bHRpcGxhLXY1LnA3YjCBwwYDVR0RBIG7MIG4gRxjb250YWJpbGlkYWRlMUB1Zm93YXkuY29tLmJyoCQGBWBMAQMCoBsTGUdSQVNJRUxBIERBIFNJTFZBIE1PUkVUVE+gGQYFYEwBAwOgEBMONDE3NDcxODYwMDAxMjSgPgYFYEwBAwSgNRMzMjIwNjE5ODEwMzQwMzQzODkzNzAwMDAwMDAwMDAwMDAwMDAwMy43OTkuNDE5U0VTUFNDoBcGBWBMAQMHoA4TDDAwMDAwMDAwMDAwMDBdBgNVHSAEVjBUMFIGBmBMAQIBJjBIMEYGCCsGAQUFBwIBFjpodHRwOi8vY2NkLmFjc29sdXRpLmNvbS5ici9kb2NzL2RwYy1hYy1zb2x1dGktbXVsdGlwbGEucGRmMB0GA1UdJQQWMBQGCCsGAQUFBwMCBggrBgEFBQcDBDCBjAYDVR0fBIGEMIGBMD6gPKA6hjhodHRwOi8vY2NkLmFjc29sdXRpLmNvbS5ici9sY3IvYWMtc29sdXRpLW11bHRpcGxhLXY1LmNybDA/oD2gO4Y5aHR0cDovL2NjZDIuYWNzb2x1dGkuY29tLmJyL2xjci9hYy1zb2x1dGktbXVsdGlwbGEtdjUuY3JsMB0GA1UdDgQWBBQrG0G+tkPEabPanTbLw7LFnEmfSjAOBgNVHQ8BAf8EBAMCBeAwDQYJKoZIhvcNAQELBQADggIBAA8aYeRj9hokI0qmhgXvcPSVctNaWfy7j7I7ZjqqSrrnc4lve8ij+rROGvGg42U97Xk2F4bzKP7MriX3h2Nc1e1FXtzRmdoAgYdqQvlr4QkqwASdM6DaNNf2jONNeEZqvc6NGwOr0p9NSsXsJ2JTn4RDE8caV/J2jq+z4UlMVz06T0wKoe+cBL3wPZz5quOVsvHntv2thw0/iDKRei5gSK8P0qglDf1UxWYMPmVd+AX+JltXvjNL0xUVAOUBLLQI2g/IW23Zjk7EtztXT0jgBmErbw+n4GLu+AohbwXX5BLlwVp2Ep+/4bax+3FEDOcf7D+P06jAwR3LoCqZyqAZYAMqEzKyV02O2aOSMVNKyteqf+cHhUgQJDGFtf+xAo9H+bAwrl2F5pPq3puy07vOXy/sbJ6yX2o/JyukpLrg0l8kjZHk2FwzyEwkIgm9xwDuXTlbg+fRA7omdhA4LTYtzi/5mxAg7vw5ggzG8Hzm7dKbA+K8TtGzvIAPlkv0QiogFw4S1sEubjykHKnQNyC4uCjF8XXL5XwpEFQExiZZ9PDnPensM6jfalR0rsrPFjEZIqXGDYGZACQHr6AYi91/dBOyMHiqiJh90OHnAJqLH6bJBJd3QaFRtApLJKg14Tj5RMZBpybqdbw5aRZbxbthoREzpzpsINKUNcf4zu+r+2N9</X509Certificate></X509Data></KeyInfo></Signature></NFe><protNFe versao="4.00"><infProt><tpAmb>1</tpAmb><verAplic>SVRS202201281355</verAplic><chNFe>42220241747186000124550010000006211094456264</chNFe><dhRecbto>2022-02-01T12:00:56-03:00</dhRecbto><nProt>342220020549851</nProt><digVal>T49zYK0ZwnJ7u+bcsY9PVYuwkFQ=</digVal><cStat>100</cStat><xMotivo>Autorizado o uso da NF-e</xMotivo></infProt></protNFe></nfeProc>';

//$fx = simplexml_load_file('tnota.xml');
//$fx = simplexml_load_file('tnota-dev.xml');

$args['nfe_xml_nota'] = $nfe2;
$args['nota_id'] = 1000;
$args['nfe_numeroPedidoLoja'] = 101;

cadItensDoPedido($args);


/*
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

*/


?>
