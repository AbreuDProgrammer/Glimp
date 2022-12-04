<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

// Classe Main Model para ser herdada por todos os models
// com funções padronizadas e úteis
abstract class my_model extends CI_Model {

	// Variavel que define uma tabela para o modelo
	protected $table;

    // Variavel que controla qual o nome da coluna de id
    protected $id_column;

    // Where clauses consts
    public $less_than = '<';
    public $greater_than = '>';
    public $not_equal = '<>';
    public $equal = '=';
    public $less_than_or_equal_to = '<=';
    public $greater_than_or_equal_to = '>=';


	// Contrutor que carrega as funcionalidades de db e tables
	public function __construct()
	{
		// Carrega a database
		$this->load->database();

        // Define uma tabela para o modelo
		$this->set_table();

        // Define o nome da coluna do id
        $this->set_id_column();

        // Cria as cláusulas possíveis
        $this->set_where_clauses();
	}

    //! Função para selecionar a tabela que o modelo irá trabalhar
    protected abstract function set_table();

    //! Função para selecionar o nome da coluna de id da tabela
    protected abstract function set_id_column();
}
