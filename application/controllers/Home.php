<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends My_controller {

	public function index()
	{
		$this->set_nav();

		// Define as variaveis usadas no site e cria-o
		$title = 'Rain';
		$css = array('homeStyle');
		$data = array();
		$view = 'home/home-view';
		$this->create_site_details($title, $css, $data, $view);
	}

	//! Funcionalidade que carrega o modelo
	protected function load_model()
	{
		$this->load->model('Home_model');
	}
}