<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends my_model {

    protected function set_table()
    {
        $this->table = 'Users';
    }

    protected function set_id_column()
    {
        $this->id_column = 'user_id';
    }
}