#!/usr/bin/php -q
<?php

	  require_once "phpagi.php";
    require_once "include/class/config.php";
    require_once "include/class/conexao.php";
    require_once "include/utils/utils.php";
    require_once "include/model/cliente.php";
    
	  $agi = new AGI();
    
    
    //Formas de escrever log na CLI 
    $agi->conlog("Escrevendo com CONLOG");
    $agi->exec("NOOP", "Escrevemdp com NOOP");
    $agi->verbose('Escrevendo com VERBOSE',1);

    //Função para facilitar a escrita na CLI (OPICIONAL)
    function debug($string, $level=3) {
        global $agi;
        $agi->verbose($string, $level);
    }

    
    //Pegando variaveis disponíveis no dialplan
    $myvar = $agi->get_variable("minha_var");
    debug($myvar);
    
    //Variaveis que são passadas ao chamar a AGI
    debug("agi_request: ".$agi->request['agi_request']);
    debug("agi_channel: ".$agi->request['agi_channel']);
    debug("agi_language: ".$agi->request['agi_language']);
    debug("agi_type: ".$agi->request['agi_type']);
    debug("agi_uniqueid: ".$agi->request['agi_uniqueid']);
    debug("agi_version: ".$agi->request['agi_version']);
    debug("agi_callerid: ".$agi->request['agi_callerid']);
    debug("agi_calleridname: ".$agi->request['agi_calleridname']);
    debug("agi_callingpres: ".$agi->request['agi_callingpres']);
    debug("agi_callingtani2: ".$agi->request['agi_callingtani2']);
    debug("agi_callington: ".$agi->request['agi_callington']);
    debug("agi_callingtns: ".$agi->request['agi_callingtns']);
    debug("agi_dnid: ".$agi->request['agi_dnid']);
    debug("agi_rdnis: ".$agi->request['agi_rdnis']);
    debug("agi_context: ".$agi->request['agi_context']);
    debug("agi_extension: ".$agi->request['agi_extension']);
    debug("agi_priority: ".$agi->request['agi_priority']);
    debug("agi_enhanced: ".$agi->request['agi_enhanced']);
    debug("agi_accountcode: ".$agi->request['agi_accountcode']);
    debug("agi_threadid: ".$agi->request['agi_threadid']);

  

    //Formar interação com cliente
    $agi->stream_file("ola","#");
    $digts = $agi->get_data("digiteseucpf");

    
    debug("Digitou: ".json_encode($digts['result']));
    
    if(strlen($digts['result']) < 10){
      $digts = $agi->get_data("naolocalizei");
    }
    
    
    
    //Enviando uma variavel para o dialplan
    $agi->set_variable("DIGITOS", $digts['result']);
    
    debug("Digitou: ".json_encode($digts['result']));

    //Query - Busca no Banco se existe cadastro do telefone passado como parâmetro e exibe o registro encontrado.
    $cliente = new Cliente();
    $cliente->GetCliente($digts['result']);
    $cli = ($cliente->GetItens()) ? $cliente->GetItens() : null ;
    
    if($cli == null){
      //Pode tratar se o cliente não foi encontrado aqui.
      $agi->stream_file("beep");
      $agi->hangup();
    }
    
    $agi->stream_file("obrigado");
    debug($cli);

    //Exemplo de consulta a API externa VIA cep de forma estruturada. (Poderia receber na variavel '$cep' os dados do CEP digitado pelo cliente e realizar a consulta) com essa 2 linhas
    //$cep = "29047550";    
    //$url = "http://viacep.com.br/ws/".$cep."/json/";
    


    //Outro exemplo básico para consumir uma API externa utilizando 
    $dnas = "06/02/1990";
    $cpf = $digts['result'];
    $url = "https://ws.hubdodesenvolvedor.com.br/v2/cpf/?cpf=".$cpf."&data=".$dnas."&token=111710030NYADsiVbFf201689072";
    
    $ret = Utils::cURL($url,"GET"); //Pega o retorno da consulta a API.
    debug($ret); //Escreve no Log do Asterisk
    
    //Desligandod a chamada do Contexto Recebido
    //$agi->hangup($agi->request['agi_channel']);



?>

