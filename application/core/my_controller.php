<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Classe Main Controller para ser herdada por todos os controladores
// com funções padronizadas e úteis
abstract class My_controller extends CI_Controller 
{
	// Variavel que verifica se a pagina tem menu abilitado
	private bool $load_nav = FALSE;

	// Variaveis arrays associativos para usar nas diferentes views
	private $data_header = array();
	private $data_nav = array();
	private $data_body = array();
	private $data_footer = array();

	// Variavel que verifica se o user está loggado
	private bool $is_logged = false;

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

	private const HEADER_DATA = 'data_header';
	private const NAV_DATA = 'data_nav';
	private const BODY_DATA = 'data_body';
	private const FOOTER_DATA = 'data_footer';
	private const ARRAY_DATAS = array(self::HEADER_DATA, self::NAV_DATA, self::BODY_DATA, self::FOOTER_DATA);

	// Contrutor que carrega as funcionalidades de urls e adiciona um css padrão a todas as paginas
	public function __construct()
	{
		// Chama o construtor do CI_Controller
		parent::__construct();
		
		// Chama as variaveis de login
		session_start();

		// Instancia as funcionalidades de ancoras
		$this->load->helper('url');

		// Carrega o iterator com o apelido
		$this->load->library('my_iterator', 'iterator');

		// Carrega a biblioteca de validação de formularios
		$this->load->library('form_validation');

		// Carrega o helper do formulario
		$this->load->helper('form');

		// Cria os arrays multidimensionais
		$this->create_data_arrays();

		// Define os ficheiros de css main do site
		$this->set_css_files(self::MAIN_CSS_PATH);

		// Verifica se o user não está loggado e não está na pagina de login ou criação de conta volta para o login
		if(!$this->verify_login() && $this->uri->segment(1) <> 'login' && $this->uri->segment(1) <> 'create_account')
			$this->go_to('login');

		// Executa as funcionalidades essenciais do controller
		$this->construtor();
	}

	// Funcionalidade para carregar os models e outras funcionalidades
	abstract protected function construtor(): void;

	// Funcionalidade para carregar o(s) modelo(s) do controller
	protected function load_model(String|Array $model): void
	{
		if(is_array($model))
			foreach($model as $path)
				$this->load->model($path);

		elseif(is_string($model))
			$this->load->model($model);
	}

	/**
	 * Funcionalidade que todos os sites buscam para criar a parte grafica
	 * Css e Variaveis passadas como array associativos porque podem existir mais do que 
	 * um ficheiro de css e mais do que uma variavel
	 */
	protected function create_site_details(String $title, Array $css, String $view, Bool $nav = TRUE): void
	{
		// Define um titulo para a pagina
		$this->set_title($title);

		// Carrega o array de css's
		$this->set_css_files($css);

		// Carrega as views
		$this->load_views($view);

		// Verifica se a nav está ligada nesta pagina
		if($nav)
			$this->set_nav();
	}

	/** 
	 * Funcionalidade que carrega as views padrões em todas as paginas mais a view do path passado
	 * as variaveis usadas nas views são carregadas por meio de funcionalidades
	 * O return é do code igniter para email
	 */
	protected function load_views(String $path, $return = FALSE): void
	{
		$this->load->view(self::INCLUDES_PATH.'/'.self::INCLUDE_HEADER, $this->data_header);

		if($this->load_nav)
			$this->load->view(self::INCLUDES_PATH.'/'.self::INCLUDE_NAV, $this->data_nav);

		$this->load->view($path, $this->data_body, $return);

		$this->load->view(self::INCLUDES_PATH.'/'.self::INCLUDE_FOOTER, $this->data_footer);
	}

	// Funcionalidade que define o titulo da pagina
	protected function set_title(String $title): void
	{
		$this->data_header['title'] = $title;
	}

	// Adiciona ficheiros de css ao header
	protected function set_css_files(String|Array $file): void
	{
		if(is_array($file))
			foreach($file as $path)
				$this->data_header['css'][] = base_url(self::ASSETS_PATH.'/'.self::CSS_PATH.'/'.$path.'.css');
				
		elseif(is_string($file))
			$this->data_header['css'][] = base_url(self::ASSETS_PATH.'/'.self::CSS_PATH.'/'.$file.'.css');
	}

	// Adiciona um ficheiro de js ao header
	protected function set_js_files(String|Array $array): void
	{
		if(is_array($file))
			foreach($array as $path)
				$this->data_header['js'][] = base_url(self::ASSETS_PATH.'/'.self::JS_PATH.'/'.$path.'.js');

		elseif(is_string($file))
			$this->data_header['js'][] = base_url(self::ASSETS_PATH.'/'.self::JS_PATH.'/'.$file.'.js');
	}

	// Funcionalidade que organiza tudo para que o menu seja abilitado
	protected function set_nav(): void
	{
		$this->load_nav = TRUE;
		$this->set_css_files(self::NAV_CSS_PATH);
	}

	/**
	 * Funcionalidade que cria uma variavel para o lugar certo da view
	 * É apenas chamada pela própria classe
	 */
	private function set_data(Array $array, String $where): void
	{
		if(!in_array($where, self::ARRAY_DATAS))
			return;
			
		foreach($array as $key => $value)
			$this->{$where}[$key] = $value;
	}

	/**
	 * Funcionalidades que chamam a mesma funcionalidade com constantes diferentes
	 * Cada uma envia para um lugar da view diferente
	 * Feita 3 funcionalidades para simplificar o codigo na parte do controller
	 */
	protected function set_footer_data(Array $array): void
	{
		$this->set_data($array, self::FOOTER_DATA);
	}
	protected function set_body_data(Array $array): void
	{
		$this->set_data($array, self::BODY_DATA);
	}
	protected function set_header_data(Array $array): void
	{
		$this->set_data($array, self::HEADER_DATA);
	}
	protected function set_nav_data(Array $array): void
	{
		$this->set_data($array, self::NAV_DATA);
	}

	/**
	 * Define uma nova variavel de link
	 * Tem que ser um array associativo para saber o nome da varivel
	 * Os links do header nav e footer são estáticos
	 * array('name' => 'data');
	 * $name = 'data';
	 */
	protected function set_link_data(Array $array): void
	{
		foreach($array as $key => $path)
			$this->data_body['link'][$key] = base_url($path);
	}

	/**
	 * Funcionalidades para controlar o login
	 * São chamadas quando o modelo retornar que foi realizado o login
	 */
	protected function is_logged(): bool
	{
		return $this->is_logged;
	}
	protected function user_logged_in(): void
	{
		$this->is_logged = true;
	}
	protected function user_logged_out(): void
	{
		$this->is_logged = false;
	}
	protected function logout_action(): void
	{
		session_unset();
	}
	protected function verify_login(): bool
	{
		if(!isset($_SESSION) || $_SESSION == NULL)
			return false;
		return true;
	}

	// Cria os arrays multidimensionais
	private function create_data_arrays(): void
	{
		$this->data_header['css'] = array();
		$this->data_header['js'] = array();
		$this->data_header['link'] = array();
	}

	// Troca de controlador
	protected function go_to(String $action): void
	{
		header('Location: '.$action);
	}
}