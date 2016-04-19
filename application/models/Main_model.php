<?php
class Main_model extends CI_Model {

	function __construct() {}

	/**
	* Obtem os clietes cadastrados na base de dados e ordena conforme parametros
	*
	* @param array || String
	* @author Henrique Weiand
	* @return array
	*/

	function getClientes($orders = false) {

		// < Verifica se foi setado a ordenacao
		if(!empty($orders)) {

			// < Verififica se orders é um array
			if(is_array($orders)) {
				// < Percorre os valores de ordenacao
				foreach ($orders as $key => $order) {
					$orderSequence[] = $key.' '.$order;
				}
				// > Percorre os valores de ordenacao

				$this->db->order_by(implode(', ', $orderSequence)); // seta order
			} else {
				$this->db->order_by($orders); // seta order
			}
			// > Verififica se orders é um array
		}
		// > Verifica se foi setado a ordenacao

		$query_clientes = $this->db
			->select('clientes.*, cidades.CIDNome, cidades.ESTSigla')
			->join('cidades', 'cidades.CIDCod = clientes.CIDCod', 'INNER')
			->get('clientes');

		// < Verifica se a query retorna clientes
		if($query_clientes -> num_rows() > 0) {
			// < Percorre os clientes
			foreach($query_clientes -> result_array() as $CLICod => $cliente) {
				$clientes[$CLICod] = $cliente;
			}
			// > Percorre os clientes
			return $clientes;
		} else {
			return false;
		}
		// > Verifica se a query retorna clientes
	}

	/**
	* Obtem os clietes cadastrados na base de dados agrupados por estados
	*
	* @param array || String
	* @author Henrique Weiand
	* @return array
	*/

	function getClientesEstados($orders = false) {

		$this->db->select(array(
			'cidades.ESTSigla',
			'COUNT(cidades.ESTSigla) as total'
		));

		// < Verifica se foi setado a ordenacao
		if(!empty($orders)) {

			if(is_array($orders)) {
				// < Percorre os valores de ordenacao
				foreach ($orders as $key => $order) {
					$orderSequence[] = $key.' '.$order;
				}
				// > Percorre os valores de ordenacao

				$this->db->order_by(implode(', ', $orderSequence)); // seta order
			} else {
				$this->db->order_by($orders); // seta order
			}
		}
		// > Verifica se foi setado a ordenacao

		$query_clientes = 
			$this->db
			->group_by('cidades.ESTSigla')
			->join('cidades', 'cidades.CIDCod = clientes.CIDCod', 'INNER')
			->get('clientes');

		// < Verifica se a query retorna clientes
		if($query_clientes -> num_rows() > 0) {
			// < Percorre os clientes
			foreach($query_clientes -> result_array() as $CLICod => $cliente) {
				$clientes[$CLICod] = $cliente;
			}
			// > Percorre os clientes
			return $clientes;
		} else {
			return false;
		}
		// > Verifica se a query retorna clientes
	}

	/**
	* Processa o arquivo recebido e retorna um array multidimensional conforme csv
	*
	* @param array
	* @param string
	* @author Henrique Weiand
	* @return array
	*/

	function preocessarAquivo($FILES, $layout) {

		// < Verifica a extensao
		if($FILES['file']['type'] == 'application/vnd.ms-excel') {
			// Formato valido
			$arquivo = fopen($FILES['file']['tmp_name'], "r"); // abre o arquivo para leitura
			$row 	 = 1; 									   // Cedula inicial

			// < Percorre as linhas do CSV
			while(($data = fgetcsv($arquivo, 10000, ";")) !== FALSE) {
				$num = count($data);

				// < Percorre as cedulas
				for ($c=0; $c<$num; $c++) {
					$csv[$row][$this -> functions -> identificaCampo($c, $layout)] = $data[$c];
				}
				// > Percorre as cedulas

				$row++;
			}
			// > Percorre as linhas do CSV

		    fclose($arquivo); // fecha o arquivo lido
		    return $csv;
		} else {
			// Nao é um formato valido
			return false;
		}
		// > Verifica a extensao

	}

	/**
	* Processa as informacoes conforme filtros do enunciado
	*
	* @param array
	* @author Henrique Weiand
	* @return array
	*/

	function verificarInformacoes($csv) {
		unset($_SESSION['logEnvio']); // Remove log de erros caso houver novo envio

		if(empty($csv)) return false; // Verifica se o array nao esta vazio

		// < Percorre cada cliente do array
		foreach ($csv as $key => $linha) {

			$status_depuracao_da_linha = true;
			
			// < Testes do enunciado
			
			/*
			O campo Código aceita números positivos de até 4 dígitos.
			O campo Nome tem que possuir até 200 caracteres.
			O campo Situação tem que possuir apenas um caractere em letra maiúscula.
			O campo Data deve somente importar os clientes acima de 2010.
			O campo Cidade deve conter até 250 caracteres.
			O campo UF deve conter somente 2 caracteres em letra maiúscula.
			*/

			if(
				(strlen($linha['codigo']) > 4 && $linha['codigo'] < 0 && !is_int($linha['codigo'])) 
				|| strlen($linha['nome']) > 200
				|| (strlen($linha['situacao']) != 1 || ctype_upper($linha['situacao']) == false)
				|| date('Y', strtotime($this -> functions -> formataData($linha['data']))) <= 2010
				|| strlen($linha['cidade']) > 250
				|| (strlen($linha['uf']) != 2 || ctype_upper($linha['uf']) == false)
			) {
				$_SESSION['logEnvio'][$key] = $linha;
				unset($csv[$key]);
				continue;
			}
		}
		// > Percorre cada cliente do array

		return $csv;
	}

	/**
	* Metodo para verificar a existencia ja de algum cliente no sistema caso nao exista, o cadastra
	*
	* @param array
	* @author henrique weiand
	*/

	function salvaClientes($csv_verificado) {

		if(empty($csv_verificado)) return false; // Verifica se o array nao esta vazio

		// < Percorre cada cliente do array
		foreach($csv_verificado as $key => $linha) {

			$query_check_cliente_exists = $this -> db -> get_where('clientes', array('CLICodigo' => $linha['codigo']));

			// < Verifica se o cliente ja existe no sistema
			if($query_check_cliente_exists -> num_rows() == 0) {

				$query_cidade = $this -> db -> get_where('cidades', array('CIDNome' => $linha['cidade'], 'ESTSigla' => $linha['uf']));
				
				// < Verifica se a cidade existe no banco de dados
				if($query_cidade -> num_rows() > 0) {

					$cidade = $query_cidade -> row_array(); // Obtem os dados da cidade

					$query_insert = $this -> db -> insert('clientes', array(
						'CLICodigo'   => $linha['codigo'],
						'CLINome' 	  => $linha['nome'],
						'CLIData' 	  => $this -> functions -> formataData($linha['data']),
						'CLISituacao' => $linha['situacao'],
						'CIDCod'      => $cidade['CIDCod']						
					));

				}
				// > Verifica se a cidade existe no banco de dados

			} else {
				$_SESSION['logEnvio'][$key] = $linha;
			}
			// > Verifica se o cliente ja existe no sistema
			
		}
		// > Percorre cada cliente do array
		
	}
	
}
?>