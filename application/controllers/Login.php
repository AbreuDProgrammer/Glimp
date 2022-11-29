<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends my_controller {

	public function index()
	{
		$title = 'Login';
		$this->setTitle($title);

		$css_file = 'loginStyle';
		$this->setCssFile($css_file);

		$data = array('createAccountLink' => './create_account');
		$this->setData($data);

		$view = 'login/login-view';
		$this->load_views($view);
	}

	public function create_account()
	{
		$title = 'Create Account';
		$this->setTitle($title);

		$css_file = 'loginStyle';
		$this->setCssFile($css_file);

		$data = array('loginLink' => './index');
		$this->setData($data);

		$view = 'login/create-account-view';
		$this->load_views($view);
	}
}