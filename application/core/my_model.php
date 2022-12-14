<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe Main Model para ser herdada por todos os models
 * O modelo terá o dever de receber uma chamada do controller, e a partir daí
 * cuidar de toda a informação com relção a todas as tabelas necessárias
 */
abstract class My_model extends CI_Model 
{
	// Contrutor que carrega as funcionalidades de db e tables
	public function __construct()
	{
		// Carrega a database
		$this->load->database();
        $this->constructor();
	}

    // Funcionalidade para instanciar helper e instanciar a table usada
    abstract function constructor();

    /**
     * Funcionalidade para recolher toda a informação de uma ou mais rows
     * É sempre passado o nome da tabela como String
     * E é opciuonalmente passado a clausula where com um array associativo
     * $this->get('Users'); || $this->get('Users', array('username' => 'Leonardo'));
     */
    protected function get(String $table, Array $where = array()): Array|Null
    {
        if($where)
            $this->db->where($where);
        $query = $this->db->get($table);
        if($where)
            return $query->row_array();
        return $query->result_array();
    }

    /**
     * Funcionalidade para inserir uma row à uma tabela
     * É passado o nome da tabela e as informações em array associativo
     * $this->insert('Users', array('username' => 'Leonardo', 'password' => 'myPassword'));
     */
    protected function insert(String $table, Array $data_array): Bool
    {
        if(empty($data_array))
            return false;

        $insert_query = $this->db->insert($table, $data_array);
        return $insert_query; // True se funcionou e false se falhou
    }

    /**
     * Funcionalidade para recolher um número específico de informações de uma tabela
     * É passado o nome da tabela em String
     * É passado as informações para ser recolhidas como array associativo
     * E é opcionalmente passado uma clausula where como array associativo
     * $this->select('Users', 'username'); || $this->select('Users', array('username', 'email')); || $this->select('Users', 'email', array('username' => 'leonardo'));
     */
    protected function select(String $table, Array|String $specifc_data, Array $where_array = array()): Array|Null
    {        
        $this->db->select($specifc_data);
        if($where_array)
            $this->db->where($where_array);
        $query = $this->db->get($table);
        return $query->result_array();
    }

    /**
     * Funcionalidade para atualizar alguma row de uma tabela
     * É passado o nome da tabela, as informações a serem adicionadas em array associativo e uma clausula where array associativo
     * $this->update('Users', array('email' => 'leo@gmail.com'), array('username' => 'Leonardo'));
     */
    protected function update(String $table, Array $data, Array $where): Bool
    {
        $update = $this->db->update($table, $data, $where);
        return $update;
    }

    /**
     * Funcionalidade para deletar informações na tabela
     * É passado o nome da tabela em String
     * É passado a clausula where em array associativo
     * $this->delete('Users', array('username' => 'Leonardo'));
     */
    protected function delete(String $table, Array $where): Bool
    {
        if(empty($where))
            return false;
        $this->db->where($where);
        $this->db->delete($table);
    }
}
