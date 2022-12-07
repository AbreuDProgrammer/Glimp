<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends My_controller {

	public function index()
	{
		$title = 'Login';
		$this->setTitle($title);

		$css = array('loginStyle');
		$this->setCssFiles($css);

		$data = array('createAccountLink' => 'create_account');
		$this->setLinkData($data);

		$view = 'login/login-view';
		$this->load_views($view);

		if($_POST)
			$this->login();
	}

	public function create_account()
	{
		$title = 'Create Account';
		$this->setTitle($title);

		$css = array('loginStyle');
		$this->setCssFiles($css);

		$data = array('loginLink' => 'login');
		$this->setLinkData($data);
		
		$view = 'login/create-account-view';
		$this->load_views($view);
		
		if($_POST)
			$this->create_account_action();
	}

	private function login()
	{
		if(!$_POST || !isset($_POST['username']) || !isset($_POST['password']))
			return;

		$username = $_POST['username'];
		$password = $_POST['password'];

		$username_query = $this->Login_model->login($username, $password);
		print_r($username_query);
	}

	private function create_account_action()
	{
		if(!$_POST || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['password_repeated']))
			return;

		$username = $_POST['username'];
		$password = $_POST['password'];
		$password_repeated = $_POST['password_repeated'];

		if($password <> $password_repeated)
			return;

		$create_query = $this->Login_model->create_account($username, $password);
		print_r($create_query);
	}

	//! Funcionalidade que carrega o modelo
	protected function load_model()
	{
		$this->load->model('Login_model');
	}
}