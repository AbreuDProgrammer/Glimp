<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class User
{
    /**
     * Todas as informações do user
     * Guardado em array associativo
     * Funcionalidades para retornar essas informações
     */
    private $data = array();

    /**
     * Salvo os tipo de variaveis
     * Com relação as informações do user
     */
    private const STRING_DATA = array('username', 'password', 'email', 'email_extra', 'birthday', 'description');
    private const INT_DATA = array('phone');
    private const ARRAY_DATA = array('apps_permissions', 'configs');

    /**
     * Funcionalidade constructor é responsavel
     * por verificar as informações e
     * por definir as informações do user
     */
    public function __construct(Array $data)
    {
        $user = $this->clean_data($data);

        $this->data['username'] = $user['username'] ?? null;
        $this->data['password'] = $user['password'] ?? null;
        $this->data['email'] = $user['email'] ?? null;
        $this->data['phone'] = $user['phone'] ?? null;
        $this->data['name'] = $user['name'] ?? null;
        $this->data['email_extra'] = $user['email_extra'] ?? null;
        $this->data['birthday'] = $user['birthday'] ?? null;
        $this->data['description'] = $user['description'] ?? null;
        $this->data['session_id'] = $user['session_id'] ?? null;
        $this->data['apps_permissions'] = $user['apps_permissions'] ?? null;
        $this->data['configs'] = $user['configs'] ?? null;
    }

    // Organiza toda a informação do user antes de ser definida
    private function clean_data(Array $data): Array
    {
        // Cria a variavel que guarda a informação correta
        $clean_data = array();

        // Todas as informações guardadas em string
        foreach(self::STRING_DATA as $key)
            if(isset($data[$key]) && is_string($data[$key]))
                $clean_data[$key] = $data[$key];

        foreach(self::INT_DATA as $key)
            if(isset($data[$key]) && is_int($data[$key]))
                $clean_data[$key] = $data[$key];

        foreach(self::ARRAY_DATA as $key)
            if(isset($data[$key]) && is_string($data[$key])){
                $json[$key] = json_decode($data[$key]);
                if(is_array($json[$key]))
                    $clean_data[$key] = $json[$key];
            }

        return $clean_data;
    }

    // GET'S
    // Password não tem get
    public function get_id(): Int { return $this->data['user_id']; }
    public function get_username(): String { return $this->data['username']; }
    public function get_email(): String { return $this->data['email']; }
    public function get_phone(): Int { return $this->data['phone']; }
    public function get_name(): String { return $this->data['name']; }
    public function get_email_extra(): String { return $this->data['email_extra']; }
    public function get_birthday(): String { return $this->data['birthday']; }
    public function get_description(): String { return $this->data['description']; }
    public function get_session_id(): String { return $this->data['session_id']; }
    public function get_apps_permissions(): Array { return $this->data['apps_permissions']; }
    public function get_configs(): Array { return $this->data['configs']; }

    // SET'S
    // Id não tem set
    public function set_username(String $data): Void { $this->data['username'] = $data; }
    public function set_password(String $data): Void { $this->data['password'] = $data; }
    public function set_email(String $data): Void { $this->data['email'] = $data; }
    public function set_phone(Int $data): Void { $this->data['phone'] = $data; }
    public function set_name(String $data): Void { $this->data['name'] = $data; }
    public function set_email_extra(String $data): Void { $this->data['email_extra'] = $data; }
    public function set_birthday(String $data): Void { $this->data['birthday'] = $data; }
    public function set_description(String $data): Void { $this->data['description'] = $data; }
    public function set_session_id(Int $data): Void { $this->data['session_id'] = $data; }
    public function set_apps_permissions(Array $data): Void { $this->data['apps_permissions'] = $data; }
    public function set_configs(Array $data): Void { $this->data['configs'] = $data; }

    // Funcionalidade que retorna toda a data do user em array
    public function get_data(): Array { return $this->data; }
}