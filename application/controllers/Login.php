<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends my_controller {

	public function index()
	{
		$this->setLink('createAccountLink', './create_account');
		$this->setCss('assets/css/loginStyle.css');
		$this->setTitle('Login');

		$this->load_views('login/login-view');
	}

	public function create_account()
	{
		$this->setTitle('Create Account');
		$this->setLink('loginLink', './index');
		$this->setCss('assets/css/loginStyle.css');

		$this->load_views('login/create-account-view');
	}
}