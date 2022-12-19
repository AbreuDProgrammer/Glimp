<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller que controla tudo com relação ao user para o público
 * Como a visão da conta e de seus sites a todos
 */
class Profile extends My_controller 
{
	/**
	 * Guarda toda a informação acessível ao público do user na url
	 * ATENÇÃO que esse controller mostra tudo que é público, então o modelo
	 * vai carregar apenas as informação públicas
	 */
	private $user;

	/**
	 * É uma função obrigatória que carrega as funcionalidades usadas durante esse mesmo controller
	 * Nesse caso irá carregar o user na url
	 */
	public function constructor(): Void
	{
		// Recebe o username do perfil
		$username = $this->uri->segment(1);

		// Carrega o modelo usado no Login
		$this->load->model('Account_model', 'account_model');
 
		// Guarda os dados públicos do user
		$this->user = $this->account_model->get_user_by_username($username, $this->session->userdata());

		// Verifica se o user com esse username existe
		if(!$this->user){
			$this->go_to_home();
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
		/**
		 * Pode ser passado o user aqui porque os dados pessoais
		 * só serão mostrados se o user decidir que sim
		 */
		$this->set_body_data($this->user??NULL);
		
		// Cria a view sem o menu
		$this->create_site_details('Profile', 'profile/index-view', 'profileStyle');
	}
}