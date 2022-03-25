<?php 

Class Conexao extends Config{
	private $host, $user, $senha, $banco;

	protected $obj, $itens=array(), $prefix, $xcon;


	public $paginacao_links, $totalpags, $limite, $inicio;



	function __construct(){
		$this->host = self::BD_HOST;
		$this->user = self::BD_USER;
		$this->senha = self::BD_SENHA;
		$this->banco = self::BD_BANCO;
		$this->prefix = self::BD_PREFIX;

		try {
			if($this->Conectar() == null){
				$this->Conectar();
			}
			

		} catch (Exception $e) {
			echo "$e->getMessage().'<h2> Erro ao conectar com o banco de dados! </h2>'";
		}

	}

	private function Conectar(){
		$options = array(
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
			PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
			);
		$this->xcon = $link = new PDO("mysql:host={$this->host};dbname={$this->banco}" , 
			$this->user, $this->senha, $options);

		return $link;
	}


	function ExecuteSQL($query, array $params = NULL){
		$this->obj = $this->Conectar()->prepare($query);
	
	$pkCount = (is_array($params) ? count($params) : 0);
		if($pkCount > 0){
			foreach($params as $key => $value){
				$this->obj->bindvalue($key, $value);
			}
		}

		return $this->obj->execute();
	}

	function ListarDados(){
		return $this->obj->fetch(PDO::FETCH_ASSOC);
	}

	function TotalDados(){
		return $this->obj->rowCount();
	}

	function GetItens(){
		return $this->itens;
	}

	function GetId(){
		return $this->xcon->lastInsertId(); //Pegando o ID do ultimo insert
	}



}


 ?>