#!/usr/bin/php -q
<?php

	  require_once "phpagi.php";
    
	  $agi = new AGI();
    
  
    //Função para facilitar a escrita na CLI
    function debug($string, $level=3) {
        global $agi;
        $agi->verbose($string, $level);
    }

    //Recebendo params
    $cep = $argv[1];
    debug("CEP:".$cep);

      
    /*
    * Função consulta API VIACEP recebe como parâmetro um CEP
    * return json data
    */
    function searchCEP($cep){
    	$curl = curl_init();
    
    	curl_setopt_array($curl, [
    	  CURLOPT_URL => "http://viacep.com.br/ws/".$cep."/json/",
    	  CURLOPT_RETURNTRANSFER => true,
    	  CURLOPT_ENCODING => "",
    	  CURLOPT_MAXREDIRS => 10,
    	  CURLOPT_TIMEOUT => 30,
    	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	  CURLOPT_CUSTOMREQUEST => "GET",
    	  CURLOPT_POSTFIELDS => "",
    	]);
    
    	$response = curl_exec($curl);
    	$err = curl_error($curl);
    
    	curl_close($curl);
    
    	if ($err) {
    		$agi->exec('NOOP',"cURL Error #:" . $err);
    	} else {
    	  return json_decode($response, true);
    	}
    } ////Fim funções

    $ret = searchCEP($cep);
    debug($ret);
    
    //enviando uma variavel para o dialplan
    $agi->set_variable("cep", $cep);
    $agi->set_variable("logradouro",$ret[logradouro]);
    $agi->set_variable("complemento", $ret[complemento]);
    $agi->set_variable("bairro", $ret[bairro]);
    $agi->set_variable("localidade", $ret[localidade]);
    $agi->set_variable("uf", $ret[uf]);
    $agi->set_variable("ddd", $ret[ddd]);



?>

