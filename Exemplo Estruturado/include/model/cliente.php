<?php 

class Cliente extends Conexao{

	function __construct(){
		parent::__construct();
	}

	function GetCliente($cpf){
		
		$query = "SELECT * FROM {$this->prefix}dados WHERE cpf = :cpf ";

		$params = array(
			':cpf' => $cpf,
			);

		$this->ExecuteSQL($query, $params);

		if($this->TotalDados() > 0){
			$this->GetListCliente();

		}else{
			echo "Cliente não cadastrado";
		}

	}


    private function GetListCliente(){
        $i = 1;
        while($lista = $this->ListarDados()):
        $this->itens[$i] = array(
             'id'       => $lista['id'],
             'nome'   => $lista['nome'],  
             'cpf'   => $lista['cpf'],  
             'nascimento' => $lista['nascimento'],  
             'telefone'     => $lista['telefone']
            );

        $i++;
        endwhile;
    }



}

 ?>