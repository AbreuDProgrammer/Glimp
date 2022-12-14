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

	// Configs para paths
	private const INCLUDES_PATH = 'includes';
	private const INCLUDE_HEADER = 'header';
	private const INCLUDE_NAV = 'menu';
	private const INCLUDE_FOOTER = 'footer';
	private const ASSETS_PATH = 'assets';
	private const CSS_PATH = 'css';
	private const JS_PATH = 'js';
	private const IMAGES_PATH = 'images';
	private const MAIN_CSS_PATH = 'mainStyle';
	private const NAV_CSS_PATH = 'navStyle';

	// Configs para configurações de variaveis
	private const HEADER_DATA = 'data_header';
	private const NAV_DATA = 'data_nav';
	private const BODY_DATA = 'data_body';
	private const FOOTER_DATA = 'data_footer';
	private const ERROR_DATA = 'data_body';
	private const ARRAY_DATAS = array(self::HEADER_DATA, self::NAV_DATA, self::BODY_DATA, self::FOOTER_DATA, self::ERROR_DATA);

	// Contrutor que carrega as funcionalidades de urls e adiciona um css padrão a todas as paginas
	public function __construct()
	{
		// Chama o constructor do CI_Controller
		parent::__construct();

		// Carrega as bibliotecas e os helpers 
		$this->load_libraries();
		$this->load_helpers();

		// Cria os arrays multidimensionais
		$this->create_data_arrays();
		
		// Define os ficheiros de css main do site
		$this->set_css_files(self::MAIN_CSS_PATH);
		
		// Verifica se o user não está loggado e não está na pagina de login ou criação de conta volta para o login
		if(!$this->session->userdata('username') && $this->uri->segment(1) <> 'login' && $this->uri->segment(1) <> 'create_account')
			$this->go_to('login');

		// Executa as funcionalidades essenciais do controller
		$this->constructor();
	}

	// Funcionalidade para carregar os models e outras funcionalidades
	abstract protected function constructor(): Void;

	/**
	 * Funcionalidade que todos os sites buscam para criar a parte grafica
	 * Css e Variaveis passadas como array associativos porque podem existir mais do que 
	 * um ficheiro de css e mais do que uma variavel
	 */
	protected function create_site_details(String $title, Array $css, String $view, Bool $nav = TRUE): Void
	{
		// Define um titulo para a pagina
		$this->set_title($title);

		// Carrega o array de css's
		$this->set_css_files($css);

		// Verifica se a nav está ligada nesta pagina
		if($nav)
			$this->set_nav();

		// Carrega as views
		$this->load_views($view);
	}

	/** 
	 * Funcionalidade que carrega as views padrões em todas as paginas mais a view do path passado
	 * as variaveis usadas nas views são carregadas por meio de funcionalidades
	 * O return é do code igniter para email
	 */
	protected function load_views(String $path, $return = FALSE): Void
	{
		$this->load->view(self::INCLUDES_PATH.'/'.self::INCLUDE_HEADER, $this->data_header);

		if($this->load_nav)
			$this->load->view(self::INCLUDES_PATH.'/'.self::INCLUDE_NAV, $this->data_nav);

		$this->load->view($path, $this->data_body, $return);

		$this->load->view(self::INCLUDES_PATH.'/'.self::INCLUDE_FOOTER, $this->data_footer);
	}

	// Funcionalidade que define o titulo da pagina
	protected function set_title(String $title): Void
	{
		$this->data_header['title'] = $title;
	}

	// Adiciona ficheiros de css ao header
	protected function set_css_files(String|Array $file): Void
	{
		if(is_array($file))
			foreach($file as $path)
				$this->data_header['css'][] = base_url(self::ASSETS_PATH.'/'.self::CSS_PATH.'/'.$path.'.css');
				
		elseif(is_string($file))
			$this->data_header['css'][] = base_url(self::ASSETS_PATH.'/'.self::CSS_PATH.'/'.$file.'.css');
	}

	// Adiciona um ficheiro de js ao header
	protected function set_js_files(String|Array $file): Void
	{
		if(is_array($file))
			foreach($array as $path)
				$this->data_header['js'][] = base_url(self::ASSETS_PATH.'/'.self::JS_PATH.'/'.$path.'.js');

		elseif(is_string($file))
			$this->data_header['js'][] = base_url(self::ASSETS_PATH.'/'.self::JS_PATH.'/'.$file.'.js');
	}

	// Funcionalidade que organiza tudo para que o menu seja abilitado
	protected function set_nav(): Void
	{
		$this->load_nav = TRUE;
		$this->set_css_files(self::NAV_CSS_PATH);
		$this->set_nav_data(array(
			'profile' => $this->session->userdata('username')
		));
		$this->set_js_files('navbar');
	}

	/**
	 * Funcionalidade que cria uma variavel para o lugar certo da view
	 * É apenas chamada pela própria classe
	 */
	private function set_data(Array $array, String $where): Void
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
	protected function set_footer_data(Array $array): Void
	{
		$this->set_data($array, self::FOOTER_DATA);
	}
	protected function set_body_data(Array $array): Void
	{
		$this->set_data($array, self::BODY_DATA);
	}
	protected function set_header_data(Array $array): Void
	{
		$this->set_data($array, self::HEADER_DATA);
	}
	protected function set_nav_data(Array $array): Void
	{
		$this->set_data($array, self::NAV_DATA);
	}

	/**
	 * Define uma nova variavel de link
	 * Tem que ser um array associativo para saber o nome da varivel
	 * Os links do header nav e footer são estáticos
	 * array('name' => 'data');
	 * $link[name] = 'data';
	 */
	protected function set_link_data(Array $array): Void
	{
		foreach($array as $key => $path)
			$this->data_body['link'][$key] = base_url($path);
	}

	/**
	 * Define uma nova variavel de erro
	 * Tem que ser um array associativo para saber o nome da varivel
	 * array('name' => 'data');
	 * $error[name] = 'data';
	 */
	protected function set_error_data(Array $array): Void
	{
		foreach($array as $key => $error)
			$this->data_body['error'][$key] = $error;
	}

	// Cria os arrays multidimensionais
	private function create_data_arrays(): Void
	{
		$this->data_header['css'] = array();
		$this->data_header['js'] = array();
		$this->data_header['link'] = array();
	}

	// Carrega todas as bibliotecas dos controllers
	private function load_libraries(): Void
	{
		// Carrega o iterator com o apelido de 'iterator'
		$this->load->library('my_iterator', null, 'iterator');

		// Carrega a biblioteca de validação de formularios com o apelido de 'form_validator'
		$this->load->library('form_validation', null, 'form_validator');

		// Carrega a biblioteca de sessão
		$this->load->library('session');
	}

	// Carrega todas os helpers dos controllers
	private function load_helpers(): Void
	{
		// Instancia as funcionalidades de ancoras
		$this->load->helper('url');

		// Carrega o helper do formulario
		$this->load->helper('form');
	}

	// Troca de controlador
	protected function go_to(String $action): Void
	{
		header('Location: '.$action);
	}

	// Troca de controlador para a pagina inicial sem o "/home/"
	protected function go_to_home(): Void
	{
		header('Location: '.base_url());
	}	

	/**
	 * Cria uma funcionalidade para POST e GET.
	 * 
	 * Essa funcionalidade tem de receber qual o controlador que está chamando
	 * para executar a funcionalidade, e esse controlador tem de ser herdeiro da classe atual.
	 * 
	 * É preciso passar uma funcionalidade para quando for passado alguma informação, só será executado se
	 * existir e as regras forem cumpridas, qualquer outra coisa não cumprida retorna false.
	 * 
	 * É preciso do metodo que está a tentar receber, POST ou GET.
	 * 
	 * Pode ser passado uma regra de formulario para a classe, ela verificará se essa regra foi cumprida
	 * antes de qualquer outra verificação.
	 */
	protected function set_listener(My_controller $controller, String $action, String $method, Bool $form_validation = TRUE): Bool
	{
		// Verifica se as regras foram cumpridas
		if(!$form_validation)
			return false;

		// Se o metodo não existir retorna e se for privado da erro
		if(!method_exists($controller, $action))
			return false;

		// Cria uma variavel com os metodos possiveis e as suas respectivas variaveis
		$ma = array(
			'POST' => $this->input->post(),
			'GET' => $this->input->get()
		);

		// Verifica se o metodo passado condiz com alguma key do array de metodos (POST ou GET no $method)
		if(!array_key_exists(strtoupper($method), $ma))
			return false;

		// Verifica se foi passado alguma coisa no GET ou POST e executa se for passado, retorna true para dizer que foi feito
		if($ma[$method]){
			$controller->{$action}();
			return true;
		}

		// Retorna false para avisar que não foi enviado nenhuma informação
		return false;
	}

	/**
	 * Essa funcionalidade é suposto para ser utilizada quando o formulario tem
	 * uma verificação já feita para escrever a mensagem
	 * 
	 * O listener_response já verifica o formulario, mas quando o site é ativo pela primeira vez
	 * o listener_response sempre é false, já que nada foi passado ainda.
	 * 
	 * O flashdata_name é o nome do session->flashdata que foi criada na ação da funcionalidade
	 * 
	 * Essa verificação só existe para que a DB não seja ativada sem propósito, já que
	 * se os dados não passarem nem as regras com certeza não existem
	 */
	protected function test_form(Bool $listener_response, String $flashdata_status, String $flashdata_name): String|Null
	{
		// Se o status está definido retorna a mensagem da tentativa
		if($this->session->flashdata($flashdata_status) !== NULL){
			$class = $this->session->flashdata($flashdata_status) == TRUE ? 'success' : 'error';
			$info = '<p class="'.$class.'">'.$this->session->flashdata($flashdata_name).'</p>';
			return $info;
		}

		// Se não cumpriu com as regras retorna o erro
		if($this->form_validator->run() == FALSE){
			$info = validation_errors();
			return $info;
		}

		// Se o listener_response ainda não executou a function então é null
		if(!$listener_response){
			$info = null;
			return $info;
		}
	}
}
