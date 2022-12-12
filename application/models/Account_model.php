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
        // Cria a query onde busca apenas pelo username
        $where = array(
            'username' => $user['username']
        );
        $username_query = $this->get('Users', $where);
        
        // Verifica se o user existe
        if(!$username_query)
            return false;
            
        // Verifica se as palavras passes são as mesmas
        if(!$this->PasswordHash->CheckPassword($user['password'], $username_query['password']))
            return false;

        // Informa que o login foi feito
        $user['is_logged'] = TRUE;
        $this->db->where('user_id', $username_query['user_id']);
        $this->db->update('Users', $user);
            
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

        if(verify_username($user['username']))
            return false;
        
        $user['password'] = $this->PasswordHash->HashPassword($user['password']);
        $user['is_logged'] = TRUE;

        $create_query = $this->insert('Users', $user);

        if(!$create_query)
            return false;

        return $create_query; // Retorna true se funcionar e false se não
    }

    public function verify_username($username): Bool
    {
        // Verifica se o username já existe
        $name_query = $this->select('username', 'Users', array('username' => $username));
                
        // Retorna se existir
        if($name_query <> null)
            return true;
        return false;
    }

    /**
     * Funcionalidade para a tabela 'Logs' e para o is_logged
     */
    public function logout($user)
    {
        // Informa que o login foi feito para a table users
        $user['is_logged'] = FALSE;
        $this->db->where('user_id', $user['user_id']);
        $this->db->update('Users', $user);
    }
}