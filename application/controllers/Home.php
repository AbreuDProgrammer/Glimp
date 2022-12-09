<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends My_controller {

	protected function construtor(): void
	{
		// Carrega o modelo usado no Login
		$this->load_model('Home_model');
	}

	public function index()
	{
		// Cria a view
		$this->create_site_details('Rain', array('homeStyle'), 'home/home-view', TRUE);
	}
}