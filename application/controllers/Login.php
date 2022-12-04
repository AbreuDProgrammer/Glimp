<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends My_controller {

	private $login_css = 'loginStyle';

	public function index()
	{
		$title = 'Login';
		$this->setTitle($title);

		$this->setCssFiles($this->login_css);

		$data = array('createAccountLink' => 'create_account');
		$this->setLinkData($data);

		$view = 'login/login-view';
		$this->load_views($view);
	}

	public function create_account()
	{
		$title = 'Create Account';
		$this->setTitle($title);

		$this->setCssFiles($this->login_css);

		$data = array('loginLink' => 'login');
		$this->setLinkData($data);

		$view = 'login/create-account-view';
		$this->load_views($view);
	}
}