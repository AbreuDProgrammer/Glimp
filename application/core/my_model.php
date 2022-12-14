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
     * Funcionalidades de get's, insert's e updates
     * Os gets pegam toda a informação do user
     * A ordem de passagem de informação dos parametros começa sempre pelo nome da tabela
     * E o ultimo parametro é a clausula where que não é orbigatória ser passada em todos os casos
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
    protected function insert(String $table, Array $data_array): Bool
    {
        if(empty($data_array))
            return false;

        $insert_query = $this->db->insert($table, $data_array);
        return $insert_query; // True se funcionou e false se falhou
    }
    protected function select(String $table, Array|String $specifc_data, Array $where_array = array()): Array|Null
    {        
        $this->db->select($specifc_data);
        if($where_array)
            $this->db->where($where_array);
        $query = $this->db->get($table);
        return $query->result_array();
    }
    protected function update(String $table, Array $data, Array $where): Bool
    {
        $update = $this->db->update($table, $data, $where);
        return $update;
    }
}
