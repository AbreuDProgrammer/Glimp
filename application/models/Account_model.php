<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends My_model 
{

    // Funcionalidade construtora de cada model
    public function constructor()
    {
        // Carrega o PasswordHash
        $this->load->helper('PasswordHash_helper');
        $this->PasswordHash = new PasswordHash(8);
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
        $username_query = $this->get_user($user['username']);
        
        // Verifica se o user existe
        if(!$username_query)
            return false;
            
        // Verifica se as palavras passes são as mesmas
        if(!$this->PasswordHash->CheckPassword($user['password'], $username_query['password']))
            return false;

        // Informa que o login foi feito
        $username_query['is_logged'] = TRUE;
        $this->db->where('username', $username_query['username']);
        $this->db->update('Users', $username_query);
            
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
        $user['is_logged'] = TRUE;

        $create_query = $this->insert('Users', $user);

        if(!$create_query)
            return false;

        return $create_query; // Retorna true se funcionar e false se não
    }

    /**
     * Funcionalidade para a tabela 'Logs' e para o is_logged
     */
    public function logout($user)
    {
        // Informa que o login foi feito para a table users
        $user['is_logged'] = FALSE;
        $this->db->where('username', $user['username']);
        $this->db->update('Users', $user);
    }

    // Retorna os dados do user pelo username
    public function get_user($username)
    {
        $where = array(
            'username' => $username
        );
        $username_query = $this->get('Users', $where);
        return $username_query ?? null;
    }
}