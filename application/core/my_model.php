<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

// Classe Main Model para ser herdada por todos os models
// com funções padronizadas e úteis
abstract class My_model extends CI_Model {

	// Variavel que define uma tabela para o modelo
	protected $table;

    // Variavel que define um id
    protected $id_column;

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

        // Define uma tabela para o modelo
		$this->table = $this->table_name();
	}

    //! Função para selecionar a tabela que o modelo irá trabalhar
    protected abstract function table_name();
    
}
