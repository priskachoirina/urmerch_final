<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Adm_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->_data_template["css"] = array("/css/admin/dataTables.bootstrap.css");
		$this->_data_template["js"] = array("/js/admin/jquery.dataTables.js", "/js/admin/dataTables.bootstrap.js");
	}

	public function index()
	{
		$data = array();
		$data["title_page"] = "Bands";
		$data["data_content"] = $this->ur_band->get();

		foreach ($data["data_content"] as $key => $value) {
			$data["data_content"][$key]["verification_label"] = (($value["verification"] == "true") ? "checked" : "" );
		}
		$this->load_view("content/v_bands", $data);
	}

	public function vendor(){
		$data = array();
		$data["title_page"] = "Vendors";
		$this->load_view("content/v_vendor", $data);
	}

	public function customers(){
		$data = array();
		$data["title_page"] = "Customers";
		$this->load_view("content/v_customer", $data);
	}

	public function loadTable(){
		$type = $this->uri->segment(4);
		$arr_type = array("vendor"=>"ur_vendor", "band" =>"ur_band", "customer" => "ur_customer");
		$mdl = $arr_type[$type];

		$this->load->model($mdl);
		$data = array();

		if($type == "band"){
			$data = $this->$mdl->get("*, CASE WHEN verification = 'true' THEN 'checked' ELSE '' END  AS verification_c");
		}else{
			$data = $this->$mdl->get();
		}


		if(!empty($data)){
			$return = getDataTable($data);
		}else{
			$return = json_encode(array());
		}
		

		echo $return;
	}
	 
	 
	public function detail()
	{
		if($this->input->post('id') !== ""){
			echo json_encode($this->ur_band->get($this->input->post('id')));	
		}
	}

	public function update_verify(){
		$name 	= (isset($_POST["names"]) ? $_POST["names"]: "");
		$id 	= (isset($_POST["id"]) ? $_POST["id"]: "");
		$value	= (isset($_POST["verification"]) ? $_POST["verification"]: "");

		if($id !== ""){
			$update = $this->ur_band->update(array("verification" => $value), $id);
			$last_query = $this->db->last_query();

			if($update == true){
				echo json_encode(array("status" => "success" , "messages" => "Band ".$name." has updated" ,"q"=>$last_query));
			}else{
				echo json_encode(array("status" => "error" , "messages" => "Can't update this data, Try again later","q"=>$last_query));
			}
		}
	}

	public function band_product(){

			$data = $this->ur_product->getProductBand($_POST["id"]);
			if(!empty($data)){
				echo json_encode(array("status"=>"success", "content" =>$data));	
			}else{
				echo json_encode(array("status" => "error" , "messages" => "Try again later"));
			}

	}
}

/* End of file Bands.php */
/* Location: ./application/controllers/Bands.php */