<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed'); 

class Functions {
	protected $ci;

	public function __construct() {
		$this->ci =& get_instance();
	}

	/**
	* Verifica qual o campo conforme padrao estipulado para retornar qual é o campo
	*
	* @param int
	* @param string
	* @return string
	* @author henrique weiand
	* @access public
	*/

	public function identificaCampo($num, $tipoLayout = 'teste-programador') {

		// < Identifica o campo que esta entrado
		switch ($num) {
			case 0:
				$campoTeste 	  = 'codigo';
				$campoProgramador = 'codigo';
				break;

			case 1:
				$campoTeste 	  = 'situacao';
				$campoProgramador = 'nome';
				break;

			case 2:
				$campoTeste 	  = 'data';
				$campoProgramador = 'situacao';
				break;

			case 3:
				$campoTeste 	  = 'nome';
				$campoProgramador = 'data';
				break;

			case 4:
				$campoTeste 	  = 'cidade';
				$campoProgramador = 'cidade';
				break;

			case 5:
				$campoTeste 	  = 'uf';
				$campoProgramador = 'uf';
				break;
			
			default:
				$campoTeste 	  = 'Nao identificado';
				$campoProgramador = 'Nao identificado';
				break;
		}
		// > Identifica o campo que esta entrado

		if($tipoLayout == 'teste-programador')
			return $campoProgramador;
		else 
			return $campoTeste;
	}

	/**
	* Transforma a data que é passada por parametro em US -> PT ou PT -> US
	*
	* @param string
	* @return string
	* @author henrique weiand
	* @access public
	*/

	public function formataData($date) {
		
		if (preg_match ("(-)", $date)) {
			list($y, $m, $d) = explode("-", $date);
			return $d."/".$m."/".$y;
		}
		else if (preg_match ("(/)", $date)) {
			list($d, $m, $y) = explode("/", $date);
			return $y."-".$m."-".$d;
		}
		
	}

}
?>