<?php

class Utils {


    /**
     * 
     * @return string: hora atual, hora , minuto e segundo
     */
    static function HoraAtual(){
        
        return date('H:i:s') ;
    }

    /**
     * 
     * @return String: data atual fotmato BR
     */
    static function DataAtualBR(){
        
        return date('d/m/Y') ;
    }

    /**
     * 
     * @param type $valor
     * @return float - valor formatado em Real
     */
    static function MoedaBR($valor){
        // 500.99   500,99    1500.99  1.500,99
         return number_format($valor,2,",",".");  
     }

    /**
     * validar CPF
     * @param type string: CPF 
     * @return boolean: true caso o CPF seja correto
     */    
    public function ValidarCPF($cpf = false) {    
        // determina um valor inicial para o digito $d1 e $d2
      $d1 = 0;
      $d2 = 0;
              // remove tudo que não seja número
              $cpf = preg_replace("/[^0-9]/", "", $cpf);
              // lista de cpf inválidos que serão ignorados
              $ignore_list = array(
                '00000000000', '01234567890','11111111111','22222222222','33333333333',
                '44444444444', '55555555555','66666666666', '77777777777','88888888888',
                '99999999999'
              );
              // se o tamanho da string for dirente de 11 ou estiver
              // na lista de cpf ignorados já retorna false
              if(strlen($cpf) != 11 || in_array($cpf, $ignore_list)){
                  return false;
              } else {
                  // inicia o processo para achar o primeiro
                  // número verificador usando os primeiros 9 dígitos
                  for($i = 0; $i < 9; $i++){
                    // inicialmente $d1 vale zero e é somando.
                    // O loop passa por todos os 9 dígitos iniciais
                    $d1 += $cpf[$i] * (10 - $i);
                  }
                  // acha o resto da divisão da soma acima por 11
                  $r1 = $d1 % 11;
                  // se $r1 maior que 1 retorna 11 menos $r1 se não
                  // retona o valor zero para $d1
                  $d1 = ($r1 > 1) ? (11 - $r1) : 0;
                  // inicia o processo para achar o segundo
                  // número verificador usando os primeiros 9 dígitos
                  for($i = 0; $i < 9; $i++) {
                    // inicialmente $d2 vale zero e é somando.
                    // O loop passa por todos os 9 dígitos iniciais
                    $d2 += $cpf[$i] * (11 - $i);
                  }
                  // $r2 será o resto da soma do cpf mais $d1 vezes 2
                  // dividido por 11
                  $r2 = ($d2 + ($d1 * 2)) % 11;
                  // se $r2 mair que 1 retorna 11 menos $r2 se não
                  // retorna o valor zeroa para $d2
                  $d2 = ($r2 > 1) ? (11 - $r2) : 0;
                  // retona true se os dois últimos dígitos do cpf
                  // forem igual a concatenação de $d1 e $d2 e se não
                  // deve retornar false.
                  return (substr($cpf, -2) == $d1 . $d2) ? true : false;
              }
    }

    /**
     * Send WhatsApp Message
     * @param type string: number
     * @param type string: message 
     * @return boolean: json respose
     */ 
    public function SendMessage($number, $message){
      
    }
    
    /*
    *  Search cURL
    */   
    public function cURL($url,$method){
   	  
       $curl = curl_init();
    
    	curl_setopt_array($curl, [
    	  CURLOPT_URL => $url,
    	  CURLOPT_RETURNTRANSFER => true,
    	  CURLOPT_ENCODING => "",
    	  CURLOPT_MAXREDIRS => 10,
    	  CURLOPT_TIMEOUT => 420,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CONNECTTIMEOUT => 10,
    	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	  CURLOPT_CUSTOMREQUEST => $method,
    	  CURLOPT_POSTFIELDS => "",
    	]);
    
    	$response = curl_exec($curl);
    	$err = curl_error($curl);
    
    	curl_close($curl);
    
    	if ($err) {
    		return $err;
    	} else {
    	  return json_decode($response, true);
    	}
    }

}?>