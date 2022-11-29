<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		$this->load->helper('url');
		$data['cssMain'] = base_url('assets/css/mainStyle.css');
		$data['cssPage'] = base_url('assets/css/loginStyle.css');

		$data['title'] = 'Login';
		$data['createAccountLink'] = "create_account";

		$this->load->view('includes/header', $data);
		$this->load->view('login/login-view', $data);
		$this->load->view('includes/footer', $data);
	}

	public function create_account()
	{
		$this->load->helper('url');
		$data['cssMain'] = base_url('assets/css/mainStyle.css');
		$data['cssPage'] = base_url('assets/css/loginStyle.css');
		$data['loginLink'] = 'index';
		$data['title'] = 'Create Account';

		$this->load->view('includes/header', $data);
		$this->load->view('login/create-account-view', $data);
		$this->load->view('includes/footer', $data);
	}
}
