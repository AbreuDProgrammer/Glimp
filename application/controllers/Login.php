<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends My_controller 
{
	/**
	 * É uma função obrigatória que carrega as funcionalidades usadas durante esse mesmo controller
	 * Como a instancia dos models
	 */
	public function construtor(): void
	{
		// Verifica se o user está loggado
		if($this->login->is_logged()){
			$this->go_to('home');
			return;
		}

		// Carrega o modelo usado no Login
		$this->load_model('Login_model');
	}

	public function index(): void
	{
		// Envia as variaveis de link
		$data = array(
			'createAccountLink' => 'create_account'
		);
		$this->set_link_data($data);
		
		// Cria o view
		$this->create_site_details('Login', array('loginStyle'), 'login/login-view', FALSE);

		$this->set_listener($this, 'login_action', 'POST');
	}

	public function create_account(): void
	{
		$data = array(
			'loginLink' => 'login'
		);
		$this->set_link_data($data);

		// Cria a view
		$this->create_site_details('Create Account', array('loginStyle'), 'login/create-account-view');

		$this->set_listener($this, 'create_account_action', 'POST');
	}
	
	public function logout(): void
	{
		// Retira toda a atividade da classe login
		$this->login->logout();

		// Move o user para o login
		$this->go_to('login');
	}

	// Funcionalidade para fazer o login quando enviado pelo POST
	protected function login_action(): void
	{
		if(!$_POST || !isset($_POST['username']) || !isset($_POST['password']))
			return;

		// Cria um array user para tentar fazer o login
		$user = array(
			'username' => $_POST['username'],
			'password' => $_POST['password']
		);

		// Tenta fazer login, retorna null se não conseguir
		$login_query = $this->Login_model->login($user);

		// Se o login não for feito
		if(!$login_query)
			return;
		
		// Altera o objeto login caso tenho conseguido
		$this->login->signed_in($login_query);
		
		// Move o user para a pagina inicial
		$this->go_to('home');
	}

	// Funcionalidade para criar um user
	protected function create_account_action(): void
	{
		if(!$_POST || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['password_repeated']))
			return;

		$username = $_POST['username'];
		$password = $_POST['password'];
		$password_repeated = $_POST['password_repeated'];

		if($password <> $password_repeated)
			return;

		$user = array(
			'username' => $username,
			'password' => $password
		);

		$create_query = $this->Login_model->create_account($user);
		
		if(!$create_query)
			return;

		// Altera o objeto login caso tenho conseguido
		$this->login->signed_in($create_query);

		// Move o user para a pagina inicial
		$this->go_to('home');
	}
}