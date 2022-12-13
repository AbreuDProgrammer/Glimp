<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends My_controller 
{
	// Regras de logins das contas
	private $user;

	/**
	 * É uma função obrigatória que carrega as funcionalidades usadas durante esse mesmo controller
	 * Como a instancia dos models
	 */
	public function construtor(): Void
	{
		// Recebe o username do perfil
		$username = $this->uri->segment(1);

		// Carrega o modelo usado no Login
		$this->load->model('Account_model', 'account_model');

		$this->user = $this->account_model->get_user($username);

		// Verifica se o user com esse username existe
		if(!$this->user){
			//! Melhorar informação de user não existir
			$this->go_to('home');
			return;
		}
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
			'username' => $this->user['username'],
			'email' => $this->user['email']
		);
		$this->set_body_data($data);
		
		// Cria a view sem o menu
		$this->create_site_details('Account Settings', array('profileStyle'), 'profile/index-view');
	}
}