<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends My_model 
{
    /**
     * Verifica se o username e password batem com um user
     * Retorna false se o user não existir ou se a palavra-passe não estiver certa
     * Retorna os dados do user se existir e estiver certa
     */
    public function login(Array $user): Bool|Array
    {
        if(!isset($user['username']) || !isset($user['password']))
            return false;
            
        // Cria a query onde busca apenas pelo username
        $username_query = $this->get_user($user['username']);
        
        // Verifica se o user existe
        if(!$username_query)
            return false;
            
        // Verifica se as palavras passes são as mesmas
        if(!$this->PasswordHash->CheckPassword($user['password'], $username_query['password']))
            return false;

        // Informa que o login foi feito para a table users
        $this->set_is_logged(TRUE, $user['username']);
            
        // Retorna os dados da DB
        return $username_query;
    }

    /**
     * Cria o user para a DB
     * O ID é AI
     * Set do is_logged para TRUE
     */
    public function create_account(Array $user): Bool
    {
        if(!isset($user['username']) || !isset($user['password']))
            return false;
        
        $user['password'] = $this->PasswordHash->HashPassword($user['password']);

        $create_query = $this->insert('Users', $user);

        if(!$create_query)
            return false;

        // Informa que o login foi feito para a table users
        $this->set_is_logged(TRUE, $user['username']);

        return $create_query; // Retorna true se funcionar e false se não
    }

    /**
     * Funcionalidade que prepara o logout
     */
    public function logout(Array|String $userdata): Void
    {
        // Informa que o logout foi feito para a table users
        $username = is_array($userdata) ? $userdata['username'] : $userdata;
        $this->set_is_logged(FALSE, $username);
    }

    // Retorna os dados do user pelo username
    public function get_user(Array|String $userdata): Array|Null
    {
        $username = is_array($userdata) ? $userdata['username'] : $userdata;

        $where = array(
            'username' => $username
        );
        $username_query = $this->get('Users', $where);
        return $username_query ?? null;
    }

    // Transforma o is_logged em TRUE ou FALSE
    public function set_is_logged(Bool $is_logged, String $username): Void
    {
        $user = array();
        $user['is_logged'] = $is_logged;
        $this->db->where('username', $username);
        $this->db->update('Users', $user);
    }
}