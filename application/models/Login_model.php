<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends My_model {

    public function login($user)
    {
        // Cria a query onde busca apenas pelo username
        $where = array(
            'username' => $user['username']
        );
        $username_query = $this->get('Users', $where);
        
        // Verifica se o user existe
        if(!$username_query)
            return;
            
        // Verifica se as palavras passes são as mesmas
        if(!$this->PasswordHash->CheckPassword($user['password'], $username_query['password']))
            return;
            
        // Retorna os dados da DB
        return $username_query;
    }

    public function create_account($user)
    {
        if(!isset($user['username']) || !isset($user['password']))
            return false;
        
        $user['password'] = $this->PasswordHash->HashPassword($user['password']);

        $create_query = $this->insert('Users', $user);

        if(!$create_query)
            return false;

        return $create_query;
    }

    public function logout()
    {
        
    }
}