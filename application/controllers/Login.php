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
		// Verifica se o user está loggado
		if($this->login->is_logged()){
			$this->go_to('home');
			return;
		}

		// Carrega o modelo usado no Login
		$this->load->model('Account_model', 'login_model');
	}

	/**
	 * São as funcionalidades que são chamadas 
	 * para a inicialização de um site
	 * sempre públicas e o nome da funcionalidade é
	 * o nome do site nas rotas
	 */
	public function index(): Void
	{
		// Regras do formulários
		$this->form_validator->set_rules('username', 'Username', self::USERNAME_RULES);
		$this->form_validator->set_rules('password', 'Password', self::PASSWORD_RULES);

		// Variavel de erros
		$erro = $this->form_validator->run() == FALSE ? validation_errors() : null;
		$this->set_error_data(array('form_error' => $erro));

		// Envia as variaveis de link
		$data = array(
			'createAccountLink' => 'create_account'
		);
		$this->set_link_data($data);
		
		// Cria a view sem o menu
		$this->create_site_details('Login', array('loginStyle'), 'login/login-view', FALSE);
		
		$this->set_listener($this, 'login_action', 'POST');
	}
	public function create_account(): Void
	{
		// Regras do formulários
		$this->form_validator->set_rules('username', 'Username', self::CREATE_ACCOUNT_USERNAME_RULES, array(
			'is_unique' => 'This %s already exists.'
		));
		$this->form_validator->set_rules('password', 'Password', self::PASSWORD_RULES);
		$this->form_validator->set_rules('password_confirm', 'Password Confirmed', self::PASSWORD_CONFIRMED_RULES);
		
		// Variavel de erros
		$erro = $this->form_validator->run() == FALSE ? validation_errors() : null;
		$this->set_error_data(array('form_error' => $erro));
		
		// Envia as variaveis de link
		$data = array(
			'loginLink' => 'login'
		);
		$this->set_link_data($data);

		// Cria a view sem o menu
		$this->create_site_details('Create Account', array('loginStyle'), 'login/create-account-view', FALSE);
		
		$this->set_listener($this, 'create_account_action', 'POST');
	}
	public function logout(): Void
	{
		// Retira toda a atividade da classe login
		$this->login->logout();

		// Move o user para o login
		$this->go_to('login');
	}

	// Funcionalidade para fazer o login quando enviado pelo POST
	protected function login_action(): Void
	{
		if(!$_POST || !isset($_POST['username']) || !isset($_POST['password']))
			return;

		// Cria um array user para tentar fazer o login
		$user = array(
			'username' => $_POST['username'],
			'password' => $_POST['password']
		);

		if(!$this->username_check($user['username']))
			return;

		// Tenta fazer login, retorna null se não conseguir
		$login_query = $this->login_model->login($user);
		
		// Se o login não for feito
		if(!$login_query)
			return;
		
		// Altera o objeto login caso tenho conseguido
		$this->login->signed_in($login_query);
		
		// Move o user para a pagina inicial
		$this->go_to('home');
	}

	// Funcionalidade para criar um user
	protected function create_account_action(): Void
	{
		if(!$_POST || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['password_confirm']))
			return;

		$username = $_POST['username'];
		$password = $_POST['password'];
		$password_confirmed = $_POST['password_confirm'];

		if($password <> $password_confirmed)
			return;

		$user = array(
			'username' => $username,
			'password' => $password
		);

		$create_query = $this->login_model->create_account($user);
		
		if(!$create_query)
			return;

		// Altera o objeto login caso tenho conseguido criar
		$this->login->signed_up($user);

		// Move o user para a pagina inicial
		$this->go_to('home');
	}
}