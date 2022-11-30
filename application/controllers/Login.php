<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends my_controller {

	public function index()
	{
		$title = 'Login';
		$this->setTitle($title);

		$css_file = array('loginStyle');
		$this->setCssFiles($css_file);

		$data = array('createAccountLink' => $this->stl('create_account'));
		$this->setData($data);

		$view = 'login/login-view';
		$this->load_views($view);
	}

	public function create_account()
	{
		$title = 'Create Account';
		$this->setTitle($title);

		$css_file = array('loginStyle');
		$this->setCssFiles($css_file);

		$data = array('loginLink' => $this->stl('login'));
		$this->setData($data);

		$view = 'login/create-account-view';
		$this->load_views($view);
	}
}