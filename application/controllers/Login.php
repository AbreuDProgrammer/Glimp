<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends My_controller {

	/**
	 * É uma função obrigatória que carrega as funcionalidades usadas durante esse mesmo controller
	 * Como a instancia dos models
	 */
	public function construtor(): void
	{
		// Verifica se o user está loggado
		if($this->verify_login()){
			$this->go_to('home');
			return;
		}

		// Carrega o modelo usado no Login
		$this->load_model('Login_model');
	}

	public function index()
	{
		// Envia as variaveis de link
		$data = array(
			'createAccountLink' => 'create_account'
		);
		$this->set_link_data($data);
		
		// Cria o view
		$this->create_site_details('Login', array('loginStyle'), 'login/login-view', FALSE);

		if($_POST)
			$this->login_action();
	}

	public function create_account()
	{
		$data = array(
			'loginLink' => 'login'
		);
		$this->set_link_data($data);

		// Cria a view
		$this->create_site_details('Create Account', array('loginStyle'), 'login/create-account-view');

		if($_POST)
			$this->create_account_action();
	}
	
	// Pagina para o logout
	public function logout()
	{
		$this->logout_action();

		$this->go_to('login');
	}

	// Funcionalidade para fazer o login quando enviado pelo POST
	private function login_action()
	{
		if(!$_POST || !isset($_POST['username']) || !isset($_POST['password']))
			return;

		$user = array(
			'username' => $_POST['username'],
			'password' => $_POST['password']
		);

		$login_query = $this->Login_model->login($user);

		if(!$login_query)
			return;

		$this->set_session($login_query);

		$this->go_to('home');
	}

	// Funcionalidade para criar um user
	private function create_account_action()
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
			return false;

		$this->set_session($user);

		$this->go_to('home');
	}

	// Funcionalidade para criar as variaveis de login
	private function set_session($user)
	{
		foreach($user as $key => $data)
			$_SESSION[$key] = $data;
		$this->user_logged_in();
	}
}