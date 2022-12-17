<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends My_model 
{    
    // Funcionalidade constructora de cada model
    public function constructor()
    {
        // Carrega o PasswordHash
        $this->load->helper('PasswordHash_helper');
        $this->PasswordHash = new PasswordHash(8);

        $this->user_exists('vini');
    }

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
        $username_query = $this->user_exists($user['username']);

        // Verifica se o user existe
        if(!$username_query)
            return false;
        
        // Recebe a password do user
        $user_password = $this->user_password($user['username']);

        // Verifica se as palavras passes são as mesmas
        if(!$this->PasswordHash->CheckPassword($user['password'], $user_password))
            return false;

        // Informa que o login foi feito para a table users
        $this->set_is_logged(TRUE, $user['username']);
            
        // Retorna os dados da DB
        return $this->get_user($user);
    }

    /**
     * Cria o user para a DB
     * O ID é AI
     * Set do is_logged para TRUE
     * Verifica se o username já existe
     */
    public function create_account(Array $user): Bool
    {
        if(!isset($user['username']) || !isset($user['password']))
            return false;

        $username_exists = $this->user_exists($user['username']);

        if($username_exists)
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
    public function get_user(Array|String $userdata): Array|NULL
    {
        $username = is_array($userdata) ? $userdata['username'] : $userdata;

        $where = array(
            'username' => $username
        );
        $username_query = $this->get('Users', $where);
        return $username_query ?? NULL;
    }

    /**
     * Funcionalidade para testar se o username bate com algum existente na DB
     * Usado por exemplo no login para verificar a existência do user sem trazer todo seu dado
     */
    public function user_exists(Array|String $userdata): Bool
    {
        $username = is_array($userdata) ? $userdata['username'] : $userdata;

        $where = array(
            'username' => $username
        );
        $username_query = $this->select('Users', 'username', $where);

        return $username_query <> NULL;
    }

    /**
     * Funcionalidade para recber a palavra-passe do user pelo username
     * Usado no login para trazer apenas a password para testar com a passada
     */
    public function user_password(Array|String $userdata): String|NULL
    {
        $username = is_array($userdata) ? $userdata['username'] : $userdata;

        $where = array(
            'username' => $username
        );
        $username_query = $this->select('Users', 'password', $where);

        return $username_query[0]['password'] ?? NULL;
    }

    // Transforma o is_logged em TRUE ou FALSE
    public function set_is_logged(Bool $is_logged, String $username): Void
    {
        $user = array();
        $user['is_logged'] = $is_logged;
        $this->update('Users', $user, array('username' => $username));
    }
}