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
		// Testa se o login foi enviado
		$was_sent = $this->set_listener($this, 'login_action', 'POST');

		// Regras do formulários
		$this->form_validator->set_rules('username', 'Username', self::USERNAME_RULES);
		$this->form_validator->set_rules('password', 'Password', self::PASSWORD_RULES);

		if($this->form_validator->run() == FALSE && !$was_sent)
		{
			$info = validation_errors();
		}
		elseif($was_sent) // Se ainda não foi enviada a data para o login
		{
			$info = '<p class="success">'.$this->session->flashdata('login_info').'</p>';
		}
		$this->set_error_data(array('form_info' => $info));

		// Envia as variaveis de link
		$data = array(
			'createAccountLink' => 'create_account'
		);
		$this->set_link_data($data);
		
		// Cria a view sem o menu
		$this->create_site_details('Login', array('loginStyle'), 'login/login-view', FALSE);
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
		
		if(!$erro)
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

		// Tenta fazer login, retorna null se não conseguir
		$login_query = $this->login_model->login($user);
		
		// Se o login não for feito
		if(!$login_query){
			$this->session->set_flashdata('login_info', 'Either your email address or password were incorrect');
			return;
		}
		
		// Altera o objeto login caso tenho conseguido
		$this->login->signed_in($login_query);

		// Set da mensagem de login
		$this->session->set_flashdata('login_info', 'Logged in!');
		
		// Move o user para a pagina inicial
		//$this->go_to('home');
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