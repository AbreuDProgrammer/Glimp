<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends my_controller {

	public function index()
	{
		// Carrega os modelos
		$this->load->model('users_model');

		// Define um titulo
		$title = 'Rain';
		$this->setTitle($title);

		// Define o ficheiro de css da pagina
		$css_file = array('homeStyle');
		$this->setCssFiles($css_file);

		// Define as variaveis passadas para a view
		$data = array();
		$this->setData($data);
		
		print_r($this->users_model->getWhere());

		// Prepara a view para o menu
		$this->setMenu();

		// Implementa todas as views
		$view = 'home/home-view';
		$this->load_views($view);
	}
}