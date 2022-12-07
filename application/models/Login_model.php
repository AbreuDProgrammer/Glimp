<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends My_model {

    public function login($username, $password)
    {
        $where = array('username' => $username);
        $username_query = $this->get('Users', $where);

        if(!$username_query)
            return false;

        return $username_query;
    }

    public function create_account($user)
    {
        if(!isset($user['username']) || !isset($user['password']))
            return false;
        
        $password_input = $user['password'];
        $password_hashed = $this->PasswordHash->HashPassword($password_input);
        $user['password'] = $password_hashed;

        $create_query = $this->insert('Users', $user);

        if(!$create_query)
            return false;

        return $create_query;
    }

    public function logout()
    {
        
    }
}