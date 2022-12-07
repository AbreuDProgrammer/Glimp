<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

// Classe Main Model para ser herdada por todos os models
// com funções padronizadas e úteis
abstract class My_model extends CI_Model {

    // Where clauses consts
    public const LESS_THAN = '<';
    public const GREATER_THAN = '>';
    public const NOT_EQUAL = '<>';
    public const EQUAL = '=';
    public const LESS_THAN_OR_EQUAL_TO = '<=';
    public const GREATER_THAN_OR_EQUAL_TO = '>=';
    public const LIKE = 'LIKE';


	// Contrutor que carrega as funcionalidades de db e tables
	public function __construct()
	{
		// Carrega a database
		$this->load->database();

        // Carrega o PasswordHash
        $this->load->helper('PasswordHash_helper');
	}

    protected function get($table, $where_array = NULL)
    {
        if(!$where_array)
        {
            $query = $this->db->get($table);
            return $query->result_array();
        }

        $query = $this->db->get_where($table, $where_array);
        return $query->row_array();
    }

    protected function insert($table, $data_array)
    {
        if(empty($data_array))
            return;

        $insert_query = $this->db->insert($table, $data_array);
        return $insert_query;
    }
}
