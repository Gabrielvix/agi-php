#!/usr/bin/php -q
<?php

  /*
  * Exemplo básico de AGI com conexão ao banco de dados MySQL 
  */

	require_once "phpagi.php";
  $agi = new AGI();
  
  $agi->answer();
  
  //Valor a ser pesquisado
  $phone = "027997037193";
  
  //Conexão com MySQL
  $bd = mysqli_connect("127.0.0.1","root","gla2021agi","clientes");
  
  if( ! $bd ){
   $agi->verbose("Erro ao tentar conectar no MYSQL " . mysqli_connect_error());
   exit(0);
  }
  //Query - Consulta realizada na tabela 'dados' 
  $res = $bd->query("SELECT * FROM dados WHERE telefone = '$phone';");
  if( $res->num_rows == 0 ){
    $agi->conlog("Cliente não encontrado"); //Se não encontrou escreve no LOG 'Cliente não encontrado'
  }else{
    $row = $res->fetch_object();
    $agi->conlog($row,"3"); //Encontrou, escreve no LOG os dados retornados da Query '$row'
  }
  
  $bd->close();
  
  $agi->conlog("TESTANDO AGI PHP");
  $agi->exec("NoOP","TESTANDO NOOP AGI PHP");
  $agi->stream_file("ola","#"); 
  
  $agi->hangup();
  

?>

