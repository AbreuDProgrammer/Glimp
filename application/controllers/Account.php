<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller que controla tudo com relação ao user apenas para o user
 * Como as definições do user com relação as permissões
 */
class Account extends My_controller
{	
	private $account;

	// Constantes para os formularios
	private const USERNAME_RULES = 'required|min_length[3]|max_length[12]';

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
 
		// Faz a requisição do user e verifica também no model se os dados estão corretos
		$this->account = $this->account_model->get_user_by_username($username, $this->session->userdata());
		
		// Verifica se o user com esse username existe
		if(!$this->account){
			$this->go_to_home();
			return;
		}
	}

	/**
	 * Funcionalidade para alterar os dados do user
	 * A senha é alterada em outra view
	 */
	public function index(): Void
	{
		// Regras do formulários
		$this->form_validator->set_rules('username', 'Username', self::USERNAME_RULES);

		// Testa se o login foi enviado e verifica o formulario
		$login_executed = $this->set_listener($this, 'update_user', 'POST', $this->form_validator->run());

		// Verifica se o formulario já foi enviado e retorna uma mensagem ao user
		$info = $this->test_form($login_executed, 'update_status', 'update_info');

		// Apresenta essa mensagem
		$this->set_error_data(array('form_info' => $info));

		// Envia as variaveis de link
		$this->set_body_data($this->account);
		
		// Cria a view sem o menu
		$this->create_site_details('Account Settings', 'account/account-view', 'accountStyle');
	}

	// Funcionalidade para atualizar os dados do user
	public function update_user()
	{
		// Verifica se está com as regras certas
		if(!$this->input->post() || $this->form_validator->run() == FALSE)
			return;

		// Cria um array user para tentar fazer o login
		$user = array(
			'user_id' => $this->input->post('user_id'),
			'username' => $this->input->post('username'),
			'email' => $this->input->post('email'),
			'phone' => $this->input->post('phone'),
			'name' => $this->input->post('name'),
			'birthday' => $this->input->post('birthday')
		);

		// Verifica o user na DB retorna NULL se não conseguir
		$update_query = $this->account_model->update_user($user, $this->session->userdata());
		if(!$update_query){
			$this->session->set_flashdata('update_status', 0);
			$this->session->set_flashdata('update_info', 'Server error');
			return;
		}
		
		// Set da mensagem de login
		$this->session->set_flashdata('update_status', 1);
		$this->session->set_flashdata('update_info', 'User updated!');
	}
}