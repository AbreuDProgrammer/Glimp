<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends My_controller {

	public function index()
	{
		// Verifica se o user está loggado
		if($this->verify_login()){
			$this->go_to('home');
			return;
		}
		
		// Define as variaveis usadas no site e cria-o
		$title = 'Login';
		$css = array('loginStyle');
		$data = array('createAccountLink' => 'create_account');
		$view = 'login/login-view';
		$this->create_site_details($title, $css, $data, $view);

		if($_POST)
			$this->login_action();
	}

	public function create_account()
	{
		// Verifica se o user está loggado
		if($this->verify_login()){
			$this->go_to('home');
			return;
		}

		// Define as variaveis usadas no site e cria-o
		$title = 'Create Account';
		$css = array('loginStyle');
		$data = array('loginLink' => 'login');
		$view = 'login/create-account-view';
		$this->create_site_details($title, $css, $data, $view);

		if($_POST)
			$this->create_account_action();
	}
	
	// Pagina para o logout
	public function logout()
	{
		// Verifica se o user está loggado
		if(!$this->verify_login())
			return;
		
		$this->logout_action();

		$this->go_to('login');
	}

	// Funcionalidade chamada no inicio dos sites para verificar se o user já está loggado
	private function verify_login()
	{
		// Verifica se o user está loggado
		if(!isset($_SESSION) || $_SESSION == NULL)
			return false;
		return true;
	}

	// Funcionalidade para fazer o login quando enviado pelo POST
	private function login_action()
	{
		if(!$_POST || !isset($_POST['username']) || !isset($_POST['password']))
			return;

		$username = $_POST['username'];
		$password = $_POST['password'];

		$username_query = $this->Login_model->login($username, $password);
		if(!$username_query)
			return;

		$_SESSION['username'] = $username_query['username'];
		$_SESSION['password'] = $username_query['password'];

		$this->is_logged = true;
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

		$this->account_created($user);
		$this->is_logged = true;
		$this->go_to('home');
	}

	// Funcionalidade para fazer o login quando a conta é criada
	private function account_created($user)
	{
		if(!isset($user['username']) || !isset($user['password']))
			return;
		
		$_SESSION['username'] = $user['username'];
		$_SESSION['password'] = $user['password'];
	}

	// Funcionalidade que carrega o modelo
	protected function load_model()
	{
		$this->load->model('Login_model');
	}
}