<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends My_model {

    public function login($username, $password)
    {
        $where = array('username' => $username);
        $username_query = $this->get('Users', $where);

        if(!$username_query)
            return false;

        return $username_query;
    }

    public function create_account($username, $password)
    {
        $data = array(
            'username' => $username,
            'password' => $password
        );
        
        $create_query = $this->insert('Users', $data);

        if(!$create_query)
            return false;

        return $create_query;
    }
}