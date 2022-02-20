<?php
//function para retornar dados explodidos
function xpl($delimiter,$string){
  $a = explode($delimiter,$string);
  return $a;
}


//obtem a URL da app
function urlPath(){
  $prot = 'http';
  if (isSet($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || isSet($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {$prot = 'https';}
  $url  = $_SERVER['SERVER_ADDR'] . str_replace(basename($_SERVER["SCRIPT_FILENAME"]),'',$_SERVER['SCRIPT_NAME']);
  return "$prot://$url";
}

//funcao para retornar dados da querystring no mod_rewrite
function mkQString($url){
  $_varname='';
  $a = explode('/',$url);
  for ($i = 0; $i < sizeof($a); $i++)
  {
    if($i % 2 == 0){
        $_varname = $a[$i];
    }else{
        $_GET[$_varname] = $a[$i];
    }
  }
}
if(isSet($_GET['vurl'])){
    if($_GET['vurl']!=''){
        mkQString($_GET['vurl']);
    }
}
/* ************** ************* ************ */
/* ************** ************* ************ */
/* ************** ************* ************ */

//sndMail(array('to'=>'destino@email.com','from'=>'site@email.com.br','subject'=>'Assunto','message'=>'Corpo da Msg'))
//FUNCAO PARA ENVIAR EMAIL (DEVERÁ SER REMOVIDA TROCADA POR UMA CLASSE)
function sndMail($args=array()){
  //prepara a mensagem de email
  //$argsMail = mailPrepare($args);
  $argsMail = $args;
  // To send HTML mail, the Content-type header must be set
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
  // Additional headers
  $headers .= 'To: ' . arrayVar($argsMail,'to') . "\r\n";
  $headers .= 'From: ' . arrayVar($argsMail,'from') . "\r\n";
  $headers .= 'Reply-To: ' . arrayVar($argsMail,'from') . "\r\n";
  $headers .= 'X-Mailer: PHP/ ' . phpversion();
  $to       = arrayVar($argsMail,'to');
  $subject  = arrayVar($argsMail,'subject');
  $message  = arrayVar($argsMail,'message');

  //se destinatario, assunto e mensagem estiverem definidos procede com o envio
  if($to!='' && $subject!='' && $message!=''){

    if(!mail($to, $subject, $message, $headers)){
      return false;
    }else{
      return true;
    }

  //caso contrario aborta e retorna false
  }else{
    return false;
  }

}


/* ************** ************* ************ */
/* ************** ************* ************ */
/* ************** ************* ************ */

function swdate($str){
  if(strstr($str,'/')){$d=explode('/',$str);return "$d[2]-$d[1]-$d[0]";}
  if(strstr($str,'-')){$d=explode('-',$str);return "$d[2]/$d[1]/$d[0]";}
}
function swdata($str){
  return swdate($str);
}

function swdatetime($dateTime){
  $t  = explode(' ',$dateTime);
  $str= $t[0];
  if(strstr($str,'/')){$d=explode('/',$str);return "$d[2]-$d[1]-$d[0] $t[1]";}
  if(strstr($str,'-')){$d=explode('-',$str);return "$d[2]/$d[1]/$d[0] $t[1]";}
}
function swdatatime($dateTime){
  return swdatetime($dateTime);
}




/* ************** ************* ************ */
/* ************** ************* ************ */
/* ************** ************* ************ */


function uppercase($str){
  $str = str_replace('á','Á',$str);
  $str = str_replace('à','À',$str);
  $str = str_replace('ã','Ã',$str);
  $str = str_replace('â','Â',$str);
  $str = str_replace('é','É',$str);
  $str = str_replace('è','È',$str);
  $str = str_replace('ẽ','Ẽ',$str);
  $str = str_replace('ê','Ê',$str);
  $str = str_replace('í','Í',$str);
  $str = str_replace('ì','Ì',$str);
  $str = str_replace('ĩ','Ĩ',$str);
  $str = str_replace('î','Î',$str);
  $str = str_replace('ó','Ó',$str);
  $str = str_replace('ò','Ò',$str);
  $str = str_replace('õ','Õ',$str);
  $str = str_replace('ô','Ô',$str);
  $str = str_replace('ú','Ú',$str);
  $str = str_replace('ù','Ù',$str);
  $str = str_replace('ũ','Ũ',$str);
  $str = str_replace('û','Û',$str);
  $str = str_replace('ç','Ç',$str);
  $str = strtoupper($str);
  return $str;
}

/* ************** ************* ************ */
/* ************** ************* ************ */
/* ************** ************* ************ */


function removeAcentos($str){
  $str = str_replace('á','a',$str);
  $str = str_replace('à','a',$str);
  $str = str_replace('ã','a',$str);
  $str = str_replace('â','a',$str);
  $str = str_replace('é','e',$str);
  $str = str_replace('è','e',$str);
  $str = str_replace('ẽ','e',$str);
  $str = str_replace('ê','e',$str);
  $str = str_replace('í','i',$str);
  $str = str_replace('ì','i',$str);
  $str = str_replace('ĩ','i',$str);
  $str = str_replace('î','i',$str);
  $str = str_replace('ó','o',$str);
  $str = str_replace('ò','o',$str);
  $str = str_replace('õ','o',$str);
  $str = str_replace('ô','o',$str);
  $str = str_replace('ú','u',$str);
  $str = str_replace('ù','u',$str);
  $str = str_replace('ũ','u',$str);
  $str = str_replace('û','u',$str);
  $str = str_replace('ç','c',$str);
  $str = str_replace('Á','A',$str);
  $str = str_replace('À','A',$str);
  $str = str_replace('Ã','A',$str);
  $str = str_replace('Â','A',$str);
  $str = str_replace('É','E',$str);
  $str = str_replace('È','E',$str);
  $str = str_replace('Ẽ','E',$str);
  $str = str_replace('Ê','E',$str);
  $str = str_replace('Í','I',$str);
  $str = str_replace('Ì','I',$str);
  $str = str_replace('Ĩ','I',$str);
  $str = str_replace('Î','I',$str);
  $str = str_replace('Ó','O',$str);
  $str = str_replace('Ò','O',$str);
  $str = str_replace('Õ','O',$str);
  $str = str_replace('Ô','O',$str);
  $str = str_replace('Ú','U',$str);
  $str = str_replace('Ù','U',$str);
  $str = str_replace('Ũ','U',$str);
  $str = str_replace('Û','U',$str);
  $str = str_replace('Ç','C',$str);
  return $str;
}


/*
 * funcao novo nome para upload de arquivo
 * */
function name2Upload($str='',$newName=''){

  $c    = explode('.',$str);
  $ext  = $c[ count($c)-1 ];
  $nname='';
  for ($i = 0; $i < count($c)-1; $i++)
  {
    $nname .= $c[$i];
  }

  if($newName!=''){
    $newName = removeAcentos($newName); //remove acentos
    $newName = ucwords($newName); //converte camelCase
    $newName = str_replace(' ','',$newName); //remove espaços
    $newName = "${newName}_" . date('dmy-Hmi') . '_'.time(); //monta novo nome
  }else{
    $newName = $nname;
  }

  $arq['nome']  = $newName;
  $arq['ext']   = $ext;
  $arq['full']  = "$newName.$ext";

  return $arq;
}





function readonly($a,$b,$c=0){
  if($a!=$b && $c==0){
    return 'readonly';
  }
}


function dt2br($str){
  if(strstr($str,'-')){
  $a = explode('-',$str);
  $d = "$a[2]/$a[1]/$a[0]";
  return $d;
  }else{
  return $str;
  }
}

function dt2brTime($str){
  if(strstr($str,'-')){
  $t = explode(' ',$str);
  $date=$t[0];
  $time=$t[1];
  $a = explode('-',$date);
  $d = "$a[2]/$a[1]/$a[0] ".$time;
  return $d;
  }else{
  return $str;
  }
}


function dt2my($str){
  $a = explode('/',$str);
  $d = "$a[2]-$a[1]-$a[0]";
  return $d;
}

$saveLogs=true;
//funcao para anotar no log de atividades
function logsys($str,$reset=false){
  global $saveLogs;
  if($saveLogs){
    $fp = fopen('logs/sys.log.txt', 'a');
    fwrite($fp, date('d/m/y - H:i:s').' - '.$str."\n");
    fclose($fp);
  }
  if($reset==true){
    $fp = fopen('logs/sys.log.txt', 'w');
    fwrite($fp, 'ZERANDO O ARQUIVO: '.date('d/m/y - H:i:s').' - '.$str."\n");
    fclose($fp);
  }
  if(isSet($_GET['logclear']) && $_GET['logclear']=='clearll'){
    $fp = fopen('logs/sys.log.txt', 'w');
    fwrite($fp, 'ZERANDO O ARQUIVO: '.date('d/m/y - H:i:s').' - '.$str."\n");
    fclose($fp);
  }
}
//function syslog($str){logsys($str);}


// funcao helper para acesso de variaveis post, get, session, cookie, data e array
function chkVar($varname){
  if(isSet($varname)){return $varname;}
  else
  {return false;}
}
function getVar($varname){
  if(isSet($_GET[$varname])){if($_GET[$varname]=='' || $_GET[$varname]!=''){return $_GET[$varname];}}
  else
  {return false;}
}
function postVar($varname){
  if(isSet($_POST[$varname])){if($_POST[$varname]=='' || $_POST[$varname]!=''){return $_POST[$varname];}}
  else{return false;}
}
function sessionVar($varname){
  if(isSet($_SESSION[$varname])){if($_SESSION[$varname]=='' || $_SESSION[$varname]!=''){return $_SESSION[$varname];}}
  else{return false;}
}
function cookieVar($varname){
  if(isSet($_COOKIE[$varname])){if($_COOKIE[$varname]=='' || $_COOKIE[$varname]!=''){return $_COOKIE[$varname];}}
  else{return false;}
}
function dataVar($_RESOURCE,$varname){
  if(isSet($_RESOURCE[$varname])){if($_RESOURCE[$varname]=='' || $_RESOURCE[$varname]!=''){return $_RESOURCE[$varname];}}
  else{return false;}
}
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

//funcao para gerar senhas temporarias com 8 caracteres (POR PADRÃO)
function genPwd($intsize=8){
  //DETERMINA OS CARACTERES QUE CONTERÃO A SENHA
  $caracteres = "0123456789abcdefghijklmnopqrstuvwxyz+-/()";
  //EMBARALHA OS CARACTERES E PEGA APENAS OS 10 PRIMEIROS
  $mistura = substr(str_shuffle($caracteres),0,$intsize);
  //EXIBE O RESULTADO
  return $mistura;
}

//FUNCAO PARA ENCRIPTAR STR DA SENHA
function mkpwd($str){
 $str = base64_encode($str.'obav');
 $str = md5($str);
 $str = base64_encode($str.'obav');
 $str = base64_encode(md5($str).'===');
 return $str;
}


//funcao para validar datas informadas
/*
 * https://www.linhadecomando.com/php/php-funcao-para-validar-data
 * */
function ValidaData($data){
    // data é menor que 8
    if ( strlen($data) < 8){
        return false;
    }else{
        // verifica se a data possui
        // a barra (/) de separação
        if(strpos($data, "/") !== FALSE){
            //
            $partes = explode("/", $data);
            // pega o dia da data
            $dia = $partes[0];
            // pega o mês da data
            $mes = $partes[1];
            // prevenindo Notice: Undefined offset: 2
            // caso informe data com uma única barra (/)
            $ano = isset($partes[2]) ? $partes[2] : 0;

            if (strlen($ano) < 4) {
                return false;
            } else {
                // verifica se a data é válida
                if (checkdate($mes, $dia, $ano)) {
                     return true;
                } else {
                     return false;
                }
            }
        }else{
            return false;
        }
    }
}


/*
 * funcao para repopular formularios de acordo com a origem dos dados
 * ex recebe 2 indices o primeiro para retornos do db o segundo para
 * retornos do post no formulario...
 * se POST for falso busca em Array de Dados e utilizada em formularios
 * de cadastros diversos onde caso ocorra erro no processamento os
 * campos do form serao repopulados com o conteudo do ultimo post
 * evitando a redigitacao
 * */
function popform($dbSrc=array(),$dbIndex='',$postIndex=''){
  $res=false;
  if(!postVar($postIndex)){//caso "NAO SEJA POST" retorna dados do array de dados
    if(dataVar($dbSrc,$dbIndex)!=''){$res = dataVar($dbSrc,$dbIndex);}else{$res = '';}
  }else{//caso "SEJA POST" retorna dados do POST
    if(postVar($postIndex)!=''){$res = postVar($postIndex);}else{$res = '';}
  }
  return $res;
}


/*
 * funcao para selecionar option em campo select
 * */
function selselr($a='',$b=''){
    $selected='';
    if($a!=''&&$b!=''){
      if($a==$b){$selected = ' selected';}
    }
    return $selected;
}


/*
 * funcao para formatar strings sem pontuacao de CPF ou CNPJ
 * */
function formata_cpf_cnpj($cpf_cnpj){
    /*
        Pega qualquer CPF e CNPJ e formata

        CPF: 000.000.000-00
        CNPJ: 00.000.000/0000-00
    */

    ## Retirando tudo que não for número.
    $cpf_cnpj = preg_replace("/[^0-9]/", "", $cpf_cnpj);
    $tipo_dado = NULL;
    if(strlen($cpf_cnpj)==11){
        $tipo_dado = "cpf";
    }
    if(strlen($cpf_cnpj)==14){
        $tipo_dado = "cnpj";
    }
    switch($tipo_dado){
        default:
            $cpf_cnpj_formatado = "Não foi possível definir tipo de dado";
        break;

        case "cpf":
            $bloco_1 = substr($cpf_cnpj,0,3);
            $bloco_2 = substr($cpf_cnpj,3,3);
            $bloco_3 = substr($cpf_cnpj,6,3);
            $dig_verificador = substr($cpf_cnpj,-2);
            $cpf_cnpj_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."-".$dig_verificador;
        break;

        case "cnpj":
            $bloco_1 = substr($cpf_cnpj,0,2);
            $bloco_2 = substr($cpf_cnpj,2,3);
            $bloco_3 = substr($cpf_cnpj,5,3);
            $bloco_4 = substr($cpf_cnpj,8,4);
            $digito_verificador = substr($cpf_cnpj,-2);
            $cpf_cnpj_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."/".$bloco_4."-".$digito_verificador;
        break;
    }
    return $cpf_cnpj_formatado;
}




?>
