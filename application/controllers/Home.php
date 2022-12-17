<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends My_controller 
{
	protected function constructor(): Void
	{
		// Carrega o modelo usado no Login
		//?$this->load->model('Home_model', 'home_model');
	}

	public function index()
	{
		// Cria a view 
		$this->create_site_details('Glimp', 'home/home-view', 'homeStyle');
	}
}