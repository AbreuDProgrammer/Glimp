<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends My_controller
{
	// Regras de logins
	private const USERNAME_RULES = 'required|min_length[3]|max_length[12]';
	private const CREATE_ACCOUNT_USERNAME_RULES = 'required|min_length[3]|max_length[12]|is_unique[Users.username]';
	private const PASSWORD_RULES = 'required|min_length[8]';
	private const PASSWORD_CONFIRMED_RULES = 'required|matches[password]';

	/**
	 * É uma função obrigatória que carrega as funcionalidades usadas durante esse mesmo controller
	 * Como a instancia dos models
	 */
	public function construtor(): Void
	{
		// Verifica se o user está loggado ou se está na pagina de logout
		if($this->session->userdata('username') && $this->uri->segment(1) <> 'logout'){
			$this->go_to('home');
			return;
		}

		// Carrega o modelo usado no Login
		$this->load->model('Account_model', 'login_model');
	}

	/**
	 * Primeiro verifica se o login foi enviado para o Post, se sim
	 * é retornado true e as funcionalidades são encaminhadas para o login, se não 
	 * é feito as funcionalidades normais.
	 * 
	 * As funcionalidades da pagina normal são as definições das regras do form, 
	 * a criação do link para a pagina de criação de conta e as views da pagina
	 * 
	 * Se o formulario já foi enviado é feito tudo igual a pagina normal mas é avisado que 
	 * o login está errado
	 */
	public function index(): Void
	{
		// Regras do formulários
		$this->form_validator->set_rules('username', 'Username', self::USERNAME_RULES);
		$this->form_validator->set_rules('password', 'Password', self::PASSWORD_RULES);

		// Testa se o login foi enviado e verifica o formulario
		$login_executed = $this->set_listener($this, 'login_action', 'POST', $this->form_validator->run());

		// Verifica se o formulario já foi enviado e retorna uma mensagem ao user
		$info = $this->test_form($login_executed, 'login_status', 'login_info');

		// Apresenta essa mensagem
		$this->set_error_data(array('form_info' => $info));

		// Envia as variaveis de link
		$data = array(
			'createAccountLink' => 'create_account'
		);
		$this->set_link_data($data);
		
		// Cria a view sem o menu
		$this->create_site_details('Login', array('loginStyle'), 'login/login-view', FALSE);

		// Se a funcionalidade foi executada e o user está logado
		if($login_executed && $this->session->userdata('username'))
			$this->go_to(base_url());
	}

	public function create_account(): Void
	{
		// Regras do formulários
		$this->form_validator->set_rules('username', 'Username', self::CREATE_ACCOUNT_USERNAME_RULES, array(
			'is_unique' => 'This %s already exists.'
		));
		$this->form_validator->set_rules('password', 'Password', self::PASSWORD_RULES);
		$this->form_validator->set_rules('password_confirm', 'Password Confirmed', self::PASSWORD_CONFIRMED_RULES);
		
		$account_creation_executed = $this->set_listener($this, 'create_account_action', 'POST', $this->form_validator->run());

		// Verifica se o formulario já foi enviado e retorna uma mensagem ao user
		$info = $this->test_form($account_creation_executed, 'create_status', 'create_info');

		// Apresenta essa mensagem
		$this->set_error_data(array('form_info' => $info));

		// Envia as variaveis de link
		$data = array(
			'loginLink' => 'login'
		);
		$this->set_link_data($data);

		// Cria a view sem o menu
		$this->create_site_details('Create Account', array('loginStyle'), 'login/create-account-view', FALSE);
		
		// Se a funcionalidade foi executada e o user está logado
		if($account_creation_executed && $this->session->userdata('username'))
			$this->go_to(base_url());
	}

	public function logout(): Void
	{
		// Faz o logout do user
		$this->logout_action();

		// Move o user para o login
		$this->go_to('login');
	}


	// Funcionalidade para fazer o login quando enviado pelo POST
	protected function login_action(): Void
	{
		// Verifica se o username e a password estão enviadas e com as regras certas
		if(!$this->input->post() || !$this->input->post('username') || !$this->input->post('password') || $this->form_validator->run() == FALSE)
			return;

		// Cria um array user para tentar fazer o login
		$user = array(
			'username' => $this->input->post('username'),
			'password' => $this->input->post('password')
		);

		// Verifica o user na DB retorna null se não conseguir
		$login_query = $this->login_model->login($user);
		if(!$login_query){
			$this->session->set_flashdata('login_status', 0);
			$this->session->set_flashdata('login_info', 'Either your email address or password were incorrect');
			return;
		}

		// Faz o login
		$this->session->set_userdata($login_query);
		
		// Set da mensagem de login
		$this->session->set_flashdata('login_status', 1);
		$this->session->set_flashdata('login_info', 'Logged in!');
	}
	 
	// Funcionalidade para criar um user
	protected function create_account_action(): Void
	{
		if(!$this->input->post() || !$this->input->post('username') || !$this->input->post('password') || !$this->input->post('password_confirm'))
			return;

		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$password_confirmed = $this->input->post('password_confirm');

		if($password <> $password_confirmed)
			return;

		$user = array(
			'username' => $username,
			'password' => $password
		);

		$create_query = $this->login_model->create_account($user);
		
		if(!$create_query){
			$this->session->set_flashdata('create_status', 0);
			$this->session->set_flashdata('create_info', 'Server error');
			return;
		}

		// Faz o login com as informação que o user acabou de criar
		$this->session->set_userdata($user);
		
		// Set da mensagem de login
		$this->session->set_flashdata('create_status', 1);
		$this->session->set_flashdata('create_info', 'Account created!');
	}

	// Funcionalidade para executar o logout do user
	protected function logout_action(): Void
	{
		// Pega toda a informação do user no session
		$this->session->unset_userdata('__ci_last_regenerate');
		$user = $this->session->userdata();
		
		// Altera todas as informações do user na db
		$this->login_model->logout($user);

		// Remove toda a informação do user na sessão
		$keydata_array = array_keys($user);
		$this->session->unset_userdata($keydata_array);
	}
}