<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller que controla tudo com relação ao user apenas para o user
 * Como as definições do user com relação as permissões
 */
class Account extends My_controller
{
	private $user_permissions;

	// Constantes para os formularios
	private const USERNAME_RULES = 'required|min_length[3]|max_length[12]';
	private const EMAIL_RULES = 'valid_email';
	private const PHONE_RULES = 'exact_length[9]|numeric|integer|is_natural';
	private const NAME_RULES = 'min_length[3]|max_length[80]';

	/**
	 * É uma função obrigatória que carrega as funcionalidades usadas durante esse mesmo controller
	 * Nesse caso irá carregar o user na url
	 */
	public function constructor(): Void
	{
		// Recebe o username do perfil
		$user = array(
			'username' => $this->uri->segment(1)
		);

		// Carrega o modelo usado no Login
		$this->load->model('Account_model', 'account_model');
		
		// Verifica se o user que está na página é o mesmo que está logado
		if($user['username'] <> $this->session->userdata('username')){
			$this->go_to_home();
			return;
		}
		
		// Verifica se o user está logado
		if(!$this->session->userdata()){
			$this->go_to_home();
			return;
		}

		// Cria a variavel de permissões da informação do user
		$this->user_permissions = $this->account_model->get_data_permissions($this->session->userdata('user_id'));
	}

	/**
	 * Passa o username para a ancora
	 */
	public function index()
	{
		$data = array(
			'username' => $this->session->userdata('username')
		);
		$this->set_body_data($data);
		$this->create_site_details('Account Settings', 'account/account-view', 'accountStyle');
	}

	/**
	 * Funcionalidade para alterar os dados do user
	 * A senha é alterada em outra view
	 * 
	 * Recarrega a pagina quando atualizar os dados
	 */
	public function details(): Void
	{
		$this->form_validator->set_rules('username', 'Username', self::USERNAME_RULES);
		$this->form_validator->set_rules('email', 'Email', self::EMAIL_RULES);
		$this->form_validator->set_rules('phone', 'Phone', self::PHONE_RULES);
		$this->form_validator->set_rules('name', 'Name', self::NAME_RULES);

		$update_executed = $this->set_listener($this, 'update_user', 'POST', $this->form_validator->run());

		$info = $this->test_form($update_executed, 'update_status', 'update_info');

		$this->set_error_data(array('form_info' => $info));
		
		$this->set_body_data($this->session->userdata());
		
		$this->create_site_details('Account Details Settings', 'account/account-details-view', 'accountStyle');
	}

	/**
	 * Funcionalidade para alterar os dados do user
	 * A senha é alterada em outra view
	 *
	 * Recarrega a pagina quando atualizar os dados
	 */
	public function permissions(): Void
	{
		$update_executed = $this->set_listener($this, 'update_data_permissions', 'POST');

		$info = $this->test_form($update_executed, 'permissions_data_update_status', 'permissions_data_update_info');

		$this->set_error_data(array('form_info' => $info));

		$this->set_body_data($this->user_permissions);
		$this->set_body_data(array('username' => $this->session->userdata('username')));
		
		$this->create_site_details('Account Permissions Settings', 'account/account-permissions-view', 'accountStyle');
	}

	/**
	 * Funcionalidade para atualizar os dados do user
	 * Verifica se está settado e com as regras certas
	 */
	protected function update_user()
	{
		$post = $this->input->post();
		if(!$post || $this->form_validator->run() == FALSE)
			return;
			
		$user = array(
			'user_id' => $post['user_id'],
			'username' => $post['username'],
			'email' => $post['email'],
			'phone' => $post['phone'],
			'name' => $post['name'],
			'birthday' => $post['birthday']
		);

		$update_query = $this->account_model->update_user($user, $this->session->userdata());
		if(!$update_query){
			$this->session->set_flashdata('update_status', 0);
			$this->session->set_flashdata('update_info', 'Server error');
			return;
		}
		
		$this->session->set_flashdata('update_status', 1);
		$this->session->set_flashdata('update_info', 'User updated!');

		$new_account_data = $this->account_model->get_userdata($user, $this->session->userdata());
		$this->session->set_userdata($new_account_data);
	}

	protected function update_data_permissions()
	{
		$post = $this->input->post();
		if(!$post)
			return;

		$user = array(
			'user_id_data_permissions' => $post['user_id_data_permissions'],
			'email' => $post['email'],
			'phone' => $post['phone'],
			'name' => $post['name'],
			'birthday' => $post['birthday']
		);

		$update_query = $this->account_model->update_user_permissions($user, $this->session->userdata());
		if(!$update_query){
			$this->session->set_flashdata('permissions_data_update_status', 0);
			$this->session->set_flashdata('permissions_data_update_info', 'Server error');
			return;
		}

		$this->session->set_flashdata('permissions_data_update_status', 1);
		$this->session->set_flashdata('permissions_data_update_info', 'User permissions updated!');
	}
}