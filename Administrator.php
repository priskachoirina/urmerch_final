<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrator extends Adm_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ur_order');
	}

	public function index()
	{
		$data = array();
		$data["title_page"] = "Home";
		$this->load_view("content/v_home", $data);
	}

	public function getNotifProductions(){
		$data = $this->ur_order->getDateProductions() ;

		$date = date_create($data[0]["order_date"]);
		$date = date_format($date ,"Y-m-d");
		echo json_encode($date);
	}
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */