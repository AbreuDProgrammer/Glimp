<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

// Classe Main Controller para ser herdada por todos os controladores
// com funções padronizadas e úteis
abstract class my_controller extends CI_Controller {

	// Variavel que verifica se todos os requisitos foram cumpridos para 
	// o carregamento da pagina ser sucedido
	private bool $ready_to_load = false;

	// Variavel de strings keys necessarias para o carregamento de qualquer pagina
	private array $data_needed = array('cssMain', 'cssPage', 'title');

	// Variavel que verifica se a pagina tem menu abilitado
	private bool $load_menu = FALSE;

	// Variaveis arrays associativos para usar nas diferentes views
	private $data_header = array();
	private $data_menu = array();
	private $data_body = array();
	private $data_footer = array();

	// Contrutor que carrega as funcionalidades de urls e adiciona um css padrão a todas as paginas
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		
		$this->data_header['cssMain'] = base_url('assets/css/mainStyle.css');
	}

	// Funcionalidade que carrega as views padrões em todas as paginas mais a view do path passado
	// as variaveis usadas nas views são carregadas por meio de funcionalidades
	protected function load_views($path, $return = FALSE)
	{
		$reasons = $this->verify_datas();
		if(!$this->ready_to_load){
			//! Retirar depois do modo programador
			print_r($reasons);
			return;
		}

		$this->load->view('includes/header', $this->data_header);

		if($this->load_menu)
			$this->load->view('includes/menu', $this->data_menu);

		$this->load->view($path, $this->data_body, $return);

		$this->load->view('includes/footer', $this->data_footer);
	}

	// Funcionalidade que define o titulo da pagina
	protected function setTitle($title = 'Undefined title')
	{
		$this->data_header['title'] = $title;
	}

	// Funcionalidade que define o ficheiro de css do menu
	private function setMenuCssFile()
	{
		$this->data_header['cssMenu'] = base_url('assets/css/menuStyle.css');
	}

	// Funcionalidade que define o ficheiro de css da pagina
	protected function setCssFile($path = '')
	{
		$this->data_header['cssPage'] = base_url('assets/css/'.$path.'.css');
	}

	// Funcionalidade que define alguma data qualquer da pagina
	protected function setData($array = [])
	{
		if(!$array)
			return;

		foreach($array as $key => $value){
			$this->data_body[$key] = $value;
		}

		$this->data_body[$key] = $value;
	}

	// Funcionalidade que verifica se todas as datas necessárias foram preenchidas no header e no footer
	private function verify_datas()
	{
		$allDone = TRUE;
		$why = array();
		for($i = 0; $i < count($this->data_needed); $i++){
			$key = $this->data_needed[$i];
			if(!isset($this->data_header[$key]) && !isset($this->data_footer[$key])){
				$allDone = FALSE;
				$why[] = $key;
			}
		}
		$this->ready_to_load = ($allDone === TRUE) ? TRUE : FALSE;
		return $why;
	}

	// Funcionalidade que organiza tudo para que o menu seja abilitado
	protected function setMenu()
	{
		$this->load_menu = TRUE;
		$this->setMenuCssFile();
	}
}
