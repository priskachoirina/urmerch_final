<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bands extends Adm_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = array();
		$data["title_page"] = "Bands Data";
		$data["data_content"] = $this->ur_band->get();

		// echo_pre($data["data_content"][0]);
		$this->load_view("content/v_bands", $data);
	}

}

/* End of file Bands.php */
/* Location: ./application/controllers/Bands.php */