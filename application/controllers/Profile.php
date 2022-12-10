<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends My_controller 
{
	// Regras de logins das contas
	private const CHANGE_ACCOUNT_USERNAME_RULES = 'required|min_length[3]|max_length[12]|is_unique[Users.username]';
	private const PASSWORD_RULES = 'required|min_length[8]';
	private const PASSWORD_CONFIRMED_RULES = 'required|matches[password]';

	/**
	 * É uma função obrigatória que carrega as funcionalidades usadas durante esse mesmo controller
	 * Como a instancia dos models
	 */
	public function construtor(): Void
	{
		// Carrega o modelo usado no Login
		$this->load->model('Account_model', 'account_model');
	}

	/**
	 * São as funcionalidades que são chamadas 
	 * para a inicialização de um site
	 * sempre públicas e o nome da funcionalidade é
	 * o nome do site nas rotas
	 */
	public function index(): Void
	{
		// Envia as variaveis de link
		$data = array(
			'' => ''
		);
		$this->set_link_data($data);
		
		// Cria a view sem o menu
		$this->create_site_details('Account Settings', array('profileStyle'), 'account-settings/index-view');
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
		$login_query = $this->Login_model->login($user);
		
		// Se o login não for feito
		if(!$login_query)
			return;
		
		// Altera o objeto login caso tenho conseguido
		$this->login->signed_in($login_query);
		
		// Move o user para a pagina inicial
		$this->go_to('home');
	}
}