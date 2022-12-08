<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

// Classe Main Controller para ser herdada por todos os controladores
// com funções padronizadas e úteis
abstract class My_controller extends CI_Controller {

	// Variavel que verifica se a pagina tem menu abilitado
	private bool $load_nav = FALSE;

	// Variaveis arrays associativos para usar nas diferentes views
	private $data_header = array();
	private $data_nav = array();
	private $data_body = array();
	private $data_footer = array();

	// Variavel que verifica se o user está loggado
	private $is_logged = false;

	// Configs para paths
	private const INCLUDES_PATH = 'includes';
	private const INCLUDE_HEADER = 'header';
	private const INCLUDE_NAV = 'menu';
	private const INCLUDE_FOOTER = 'footer';
	private const ASSETS_PATH = 'assets';
	private const CSS_PATH = 'css';
	private const JS_PATH = 'js';
	private const MAIN_CSS_PATH = 'mainStyle';
	private const NAV_CSS_PATH = 'navStyle';

	// Contrutor que carrega as funcionalidades de urls e adiciona um css padrão a todas as paginas
	public function __construct()
	{
		// Chama o construtor do CI_Controller
		parent::__construct();
		
		// Chama as variaveis de login
		session_start();

		// Instancia as funcionalidades de ancoras
		$this->load->helper('url');

		// Carrega o iterator
		$this->load->library('my_iterator');

		// Cria os arrays multidimensionais
		$this->create_data_arrays();

		// Define os ficheiros de css main do site
		$this->setMainCssFile();

		// Carrega os modelos
		$this->load_model();
	}

	//? Funcionalidade para carregar o modelo do controller
	abstract protected function load_model();

	/**
	 * Funcionalidade que todos os sites buscam para criar a parte grafica
	 * Css e Variaveis passadas como array associativos porque podem existir mais do que 
	 * um ficheiro de css e mais do que uma variavel
	 */
	protected function create_site_details(String $title, Array $css, Array $variables, String $view): void
	{
		// Define um titulo para a pagina
		$this->setTitle($title);

		// Carrega o array de css's
		$this->setCssFiles($css);

		// Carrega as variaveis usadas no body do site
		$this->setLinkData($variables);

		// Carrega as views
		$this->load_views($view);
	}

	/** 
	 * Funcionalidade que carrega as views padrões em todas as paginas mais a view do path passado
	 * as variaveis usadas nas views são carregadas por meio de funcionalidades
	 */
	protected function load_views($path, $return = FALSE)
	{
		$this->load->view(My_controller::INCLUDES_PATH.'/'.My_controller::INCLUDE_HEADER, $this->data_header);

		if($this->load_nav)
			$this->load->view(My_controller::INCLUDES_PATH.'/'.My_controller::INCLUDE_NAV, $this->data_nav);

		$this->load->view($path, $this->data_body, $return);

		$this->load->view(My_controller::INCLUDES_PATH.'/'.My_controller::INCLUDE_FOOTER, $this->data_footer);
	}

	// Funcionalidade que define o titulo da pagina
	protected function setTitle($title = 'Undefined title')
	{
		if(!$title || !is_string($title))
			return;

		$this->data_header['title'] = $title;
	}

	// Adiciona ficheiros de css ao header
	protected function setCssFiles($file)
	{
		if(!$file)
			return;

		if(is_array($file))
			foreach($file as $path)
				$this->data_header['css'][] = base_url(My_controller::ASSETS_PATH.'/'.My_controller::CSS_PATH.'/'.$path.'.css');
				
		elseif(is_string($file))
			$this->data_header['css'][] = base_url(My_controller::ASSETS_PATH.'/'.My_controller::CSS_PATH.'/'.$file.'.css');
		
		return;
	}

	// Adiciona um ficheiro de js ao header
	protected function setJsFiles($array)
	{
		if(!$array || !is_array($array))
			return;

		foreach($array as $path){
			$this->data_header['js'][] = base_url(My_controller::ASSETS_PATH.'/'.My_controller::JS_PATH.'/'.$path.'.js');
		}
	}

	// Funcionalidade que organiza tudo para que o menu seja abilitado
	protected function setMenu()
	{
		$this->load_nav = TRUE;
		$this->setNavCssFile();
		$this->setNavLinksFile();
	}

	// Funcionalidade para enviar para a nav os links
	private function setNavLinksFile()
	{
		$this->data_nav['logout'] = base_url('logout');
	}

	// Funcionalidade que define alguma data qualquer da pagina
	protected function setData($array)
	{
		if(!$array || !is_array($array))
			return;

		foreach($array as $key => $value){
			$this->data_body[$key] = $value;
		}
	}

	// Funcionalidade que define alguma data qualquer do footer
	protected function setFooterData($array)
	{
		if(!$array || !is_array($array))
			return;

		foreach($array as $key => $value){
			$this->data_footer[$key] = $value;
		}
	}

	// Funcionalidade que define alguma data qualquer do header
	protected function setHeaderData($array)
	{
		if(!$array || !is_array($array))
			return;

		foreach($array as $key => $value){
			$this->data_header[$key] = $value;
		}
	}

	// Funcionalidade que define alguma data qualquer do menu
	protected function setNavData($array)
	{
		if(!$array || !is_array($array))
			return;

		foreach($array as $key => $value){
			$this->data_nav[$key] = $value;
		}
	}

	// Definir um novo link
	protected function setLinkData($array)
	{
		if(!$array || !is_array($array))
			return;

		foreach($array as $key => $path){
			$this->data_body['link'][$key] = base_url($path);
		}
	}

	// Funcionalidade que retorna se o user está loggado ou não
	protected function is_logged()
	{
		return $this->is_logged;
	}

	// Funcionalidade que define que o user está loggado
	protected function user_logged_in()
	{
		$this->is_logged = true;
	}

	// Funcionalidade que define que o user não está loggado
	protected function user_logged_out()
	{
		$this->is_logged = false;
	}

	// Define o ficheiro de css main do site
	private function setMainCssFile()
	{
		$this->data_header['css'][] = base_url(My_controller::ASSETS_PATH.'/'.My_controller::CSS_PATH.'/'.My_controller::MAIN_CSS_PATH.'.css');
	}

	// Define o css do nav
	private function setNavCssFile()
	{
		$this->data_header['css'][] = base_url(My_controller::ASSETS_PATH.'/'.My_controller::CSS_PATH.'/'.My_controller::NAV_CSS_PATH.'.css');
	}

	// Cria os arrays multidimensionais
	private function create_data_arrays()
	{
		$this->data_header['css'] = array();
		$this->data_header['js'] = array();
		$this->data_header['link'] = array();
	}

	// Troca de controlador
	protected function go_to($action)
	{
		header('Location: '.$action);
	}

	// Funcionalidade de logout aqui para poder ser acedida por outros controladores
	protected function logout_action()
	{
		session_unset();
	}
}
