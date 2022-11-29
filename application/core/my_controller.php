<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class my_controller extends CI_Controller {

	private $data_header = array();
	private $data_body = array();
	private $data_footer = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->data_header['cssMain'] = base_url('assets/css/mainStyle.css');
	}

	protected function load_views($path, $return = FALSE)
	{
		if(!isset($this->data_header["cssPage"]) || !isset($this->data_header['title'])){
			$this->load->view('index.html');
			return;
		}

		$this->load->view('includes/header', $this->data_header);
		$this->load->view($path, $this->data_body, $return);
		$this->load->view('includes/footer', $this->data_footer);
	}

	protected function setTitle($title = 'Undefined title')
	{
		$this->data_header['title'] = $title;
	}

	protected function setCss($path = '')
	{
		$this->data_header['cssPage'] = base_url($path);
	}

	protected function setLink($key, $value)
	{
		$this->data_body[$key] = $value;
	}
}
