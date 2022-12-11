<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe que controla tudo sobre o user que está loggado
 */
final class My_Login
{
    // Variavel que indica se o user está atualmente logado
    private bool $is_logged = FALSE;

    // Objeto User de quem está logado
    private User $user;

    // Verifica se o $_SESSION está ativo e cria o login se estiver
    public function __construct()
    {
        $user = $this->verify_login();
        if($user <> null)
            $this->signed_in($user);
    }
    
    // Retorna true or false se o user está logado
    public function is_logged(): Bool
	{
		return $this->is_logged;
	}

    /**
     * Funcionalidade executada quando o user dá login
     * Já prepara toda a classe de uma vez
     */
    public function signed_in(Array $user): Void
    {
        $this->is_logged = TRUE;
        $this->set_user($user);
        $this->set_session();
    }

    public function set_user(Array $user): Void
    {
        $this->user = new User($user);
    }

    /**
     * É chamado quando o user cria a conta
     * Apenas executa a função de login 
     */
    public function signed_up(Array $user): Void
    {
        $this->signed_in($user);
    }
     
    // Faz o logout do user
	public function logout(): Void
	{
		session_unset();
		$this->is_logged = FALSE;
	}

    // Quarda a informação do user
	private function set_session(): Void
	{
        if(!$this->user)
            return;

		foreach($this->user->get_data() as $key => $data)
			$_SESSION[$key] = $data;
	}

    private function verify_login(): Array|Null
    {
        return $_SESSION ?? null;
    }
}