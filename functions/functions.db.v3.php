<?php

function dbf_o($sql,$ret='fetch'){
$hostname = DBHOST;
$username = DBUSER;
$password = DBPWD;
$dbname   = DBNAME;

$mysqli = mysqli_connect($hostname,$username,$password,$dbname);

if (mysqli_connect_errno($mysqli)){
  echo "Falha na conexao com servidor MySql: " . mysqli_connect_error();
}

$result = mysqli_query($mysqli,$sql);

if(
  !strstr(strtoupper($sql),'INSERT') &&
  !strstr(strtoupper($sql),'UPDATE') &&
  !strstr(strtoupper($sql),'DELETE'))
  {
  if($ret=='num')       {$res = mysqli_num_rows($result);}
  elseif($ret=='fetch') {$res = mysqli_fetch_array($result,MYSQLI_ASSOC);}
  else{
  $res = $result;
  }
  //mysqli_free_result($result);
}else{
  $res = mysqli_affected_rows($mysqli);
}

//mysqli_close($mysqli);

return $res;
}

/*
 * funcao pre historica REFATORADA dbf(str1,array,str2);
 * recebe
 * str1 a query a ser executada
 * array() campos para o prepare do PDO
 * str2 tipo de retorno esperado (fetch,num)
 * */
function dbf($query='',$args=array(),$ret=''){
  $results=false;
    if($query!=''){
    $hostname = DBHOST;
    $username = DBUSER;
    $password = DBPWD;
    $dbname   = DBNAME;
    try {
        $conn     = new PDO("mysql:dbname=$dbname;host=$hostname",$username,$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt     = $conn->prepare($query);

        //BINDPARAMS
        if(is_array($args)&&count($args)>0){
          foreach ($args as $key => &$value) {
            $valor = $value;
            $stmt->bindParam( $key , $value);
          }
        }
        //EXECUTE QUERY
        $stmt->execute();
          if(strstr(strtoupper($query),'INSERT')){$results  = $conn->lastInsertId();}//ultimo ID inserido
          if(strstr(strtoupper($query),'UPDATE')){$results  = $stmt->rowCount();}//ultimo ID inserido
          if(strstr(strtoupper($query),'DELETE')){$results  = $stmt->rowCount();}//ultimo ID inserido
          if($ret=='fetch'){$results  = $stmt->fetchAll(PDO::FETCH_ASSOC);}
          if($ret=='num'){$results  = $stmt->rowCount();}//numero de linhas afetadas
    } catch (PDOException $e) {
    $results = 'ERROR:'.$e->getMessage();
    return $results;
    }
  }
  return $results;
}

function deleteDB($tabela,$campos=array(),$WHERE=''){
  $into='';
  $arr    = array();
  if(count($campos)>0){
    $n      = 0;
    foreach($campos as $key => $val){
      $t        = ":$key";
      $arr["$t"]= $val; $n++;
    }
  }
  $sql = "DELETE FROM ${tabela} $WHERE";
  $res = dbf($sql,$arr);
  return $res;
}


function updateDB($tabela,$campos=array(),$WHERE=''){
  $into='';
  $arr    = array();
  if(count($campos)>0){
    $n      = 0;
    foreach($campos as $key => $val){
      if($n==0) {$into .= "$key = :$key";}
      else      {$into .= ", $key = :$key";}
      $t        = ":$key";
      $arr["$t"]= $val; $n++;
    }
  }
  $sql = "UPDATE ${tabela} SET $into $WHERE";
  $res = dbf($sql,$arr);
  return $res;
}


function insertDB($tabela,$campos=array()){
  $into='';
  $arr    = array();
  if(count($campos)>0){
    $n      = 0;
    foreach($campos as $key => $val){
      if($n==0) {$into .= "$key = :$key";}
      else      {$into .= ", $key = :$key";}
      $t        = ":$key";
      $arr["$t"]= $val; $n++;
    }
  }
  $sql = "INSERT ${tabela} SET $into";
  $res = dbf($sql,$arr);
  return $res;
}


function selectDB($campos,$tabela,$condicao=array(),$order=''){
  $where='';
  $arr    = array();
  if(count($condicao)>0){
    $where  = ' WHERE ';
    $n      = 0;
    foreach($condicao as $key => $val){
      if($n==0) {$where .= "$key = :$key ";}
      else      {$where .= " AND $key = :$key";}
      $t        = ":$key";
      $arr["$t"]= $val; $n++;
    }
  }
  $sql = "SELECT $campos FROM ${tabela}$where $order";
  $res = dbf($sql,$arr,'fetch');
  if(count($res)==1){
  return $res[0];
  }else{
  return $res;
  }
}

?>
