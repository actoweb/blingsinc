<?php
include_once('config.all.php');

//echo date('w',strtotime('2022-02-18'));
$jsonAUTO = '287f73cc1f5362ba84a091c4d8239f18';
$jsonTESTE = '{"id":"15390605809","serie":"1","numero":"000132","loja":"203815689","numeroPedidoLoja":"01145","tipo":"S","situacao":"Emitida DANFE","cliente":{"nome":"Fabiana Aguiar da Silva","cnpj":"02219519970","ie":"","rg":"","endereco":"Rua Bar\u00e3o do Rio Branco","numero":"181","complemento":"Apto 701","cidade":"Crici\u00fama","bairro":"Centro","cep":"88.801-450","uf":"SC","email":"fabiaguiar05@hotmail.com","fone":"48996248051"},"contato":"Fabiana Aguiar da Silva","cnpj":"02219519970","vendedor":"","dataEmissao":"2021-11-29 13:43:02","valorNota":"988.00","chaveAcesso":"42211141747186000124550010000001321906058091","xml":"https:\/\/bling.com.br\/relatorios\/nfe.xml.php?s&chaveAcesso=42211141747186000124550010000001321906058091","linkDanfe":"https:\/\/bling.com.br\/doc.view.php?id=8f0b3288344b6f16abeba080925a3178","linkPDF":"https:\/\/bling.com.br\/doc.view.php?PDF=true&id=8f0b3288344b6f16abeba080925a3178","codigosRastreamento":[],"cfops":["5102","5102","5102","5102"],"tipoIntegracao":"CoreCommerce","transporte":{"enderecoEntrega":{"nome":"Fabiana Aguiar da Silva","endereco":"Rua Bar\u00e3o do Rio Branco","numero":"181","complemento":"Apto 701","cidade":"Crici\u00fama","bairro":"Centro","cep":"88.801-450","uf":"SC"}}}';


echo 'NOTA: AUTO - '. $jsonAUTO . '<br />';
echo 'NOTA: TESTE - '.md5($jsonTESTE) . '<br />';


if($jsonAUTO==md5($jsonTESTE)){
echo "Hash idênticos!!!";
}else{
echo "Hash DIFERENTES ERRROOOORRR!!!";
}

?>
