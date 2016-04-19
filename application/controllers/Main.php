<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	/**
	* Redireciona para o metodo de listagem
	*
	* @access public
	* @return redirect
	* @author henrique weiand
	*/

	public function index()
	{
		redirect('main/listarClientes');
	}

	/**
	* Pagina de listagem de clientes adicionados conforme importacao realizada
	*
	* @access public
	* @author henrique weiand
	*/

	public function listarClientes() {

		$this->load->model('Main_model'); 

		$this->load->view('struct/header');
		$this->load->view('struct/navbar');
		$this->load->view('lista_clientes', array(
				'clientes' => $this->Main_model->getClientes(array(
					'cidades.ESTSigla' => 'ASC',
					'cidades.CIDNome'  => 'ASC',
					'clientes.CLINome' => 'ASC'
		))));
		$this->load->view('struct/footer');
	}

	/**
	* Pagina de listagem de clientes com agrupamento por estado
	*
	* @access public
	* @author henrique weiand
	*/

	public function listarPorEstado() {

		$this->load->model('Main_model'); 

		$this->load->view('struct/header');
		$this->load->view('struct/navbar');
		$this->load->view('lista_clientes_por_estado', array(
				'estados' => $this->Main_model->getClientesEstados(array(
					'cidades.ESTSigla' => 'ASC'
		))));
		$this->load->view('struct/footer');
	}

	/**
	* Pagina de listagem de clientes adicionados conforme importacao realizada
	*
	* @access public
	* @author henrique weiand
	*/

	public function receberArquivo() {

		@session_start();
		if(!empty($_GET['limparLog'])) unset($_SESSION['logEnvio']);

		// < Verifica se existe $_POST
		if(!empty($_FILES)) {
			$this->load->model('Main_model'); 
			$csv = $this->Main_model->preocessarAquivo($_FILES, $_POST['layout']); // Processamento do envio de arquivo

			// < Verifica se o processamento do arquivo foi executado com sucesso
			if(!empty($csv)) {

				$this->Main_model->salvaClientes($this->Main_model->verificarInformacoes($csv)); // Processa o CSV conforme filtros e salva as informarcoes no banco

				// < Verifica se houve alguma situacao de alerta
				if(!isset($_SESSION['logEnvio'])) {
					// Processamento ocurreu com sucesso
					$retorno['feedback']['tipo'] 	 = 'success';
					$retorno['feedback']['mensagem'] = 'Arquivo processado com sucesso. Aguarde que você será redirecionado em 3 segundos.';
					$retorno['feedback']['href']     = base_url().'main/listarClientes';
				} else {
					// Houve alguns alertas durante o processamento
					$retorno['feedback']['tipo'] 	 = 'warning';
					$retorno['feedback']['mensagem'] = 'Arquivo processado com sucesso. Porem, apresentou alguns resultados inesperados. Aguarde que a página ira atualizar em 3 segundos';
					$retorno['feedback']['href']     = base_url().'main/receberArquivo';
				}
				// > Verifica se houve alguma situacao de alerta

			} else {
				// Extensao nao permitida
				$retorno['feedback']['tipo'] 	 = 'danger';
				$retorno['feedback']['mensagem'] = 'Extensão "<b>'.$_FILES['file']['type'].'</b>", enviada não é permitida.';
			}
			// > Verifica se o processamento do arquivo foi executado com sucesso

			echo json_encode($retorno);

		} else {
			$this->load->view('struct/header');
			$this->load->view('struct/navbar');

			$this->load->view('receber'); // Pagina de envio de arquivo

			if(!empty($_SESSION['logEnvio'])) 
				$this->load->view('log', array('log' => $_SESSION['logEnvio'])); // Log de alertas na importacao

			$this->load->view('struct/footer');
		}
		// > Verifica se existe $_POST
		
	}

}
