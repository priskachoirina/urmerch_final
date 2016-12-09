<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends Adm_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_data_template["css"] = array("/css/admin/dataTables.bootstrap.css");
		$this->_data_template["js"] = array("/js/admin/jquery.dataTables.js", "/js/admin/dataTables.bootstrap.js");

		$this->load->model(array('ur_vendor','ur_category'));
	}

	public function index()
	{
		$data = array();
		$data["title_page"] = "Products Data";
		$data["data_content"] = $this->ur_product->getProductBand();

		// echo_pre($data["data_content"][0]);
		$this->load_view("content/v_products", $data);
	}

	public function categories(){

		$dt_category = $this->ur_category->getCategoryVendor();
		
		$data = array();
		$data["data_content"] = $dt_category;

		foreach ($dt_category as $key => $value) {
			$data["data_content"][$key]["vendor_code"] = ($value["vendor_code"] == "0") ? null : $value["vendor_code"] ;
			$data["data_content"][$key]["edit_data"] = implode(",", $value);
		}

		$data["title_page"] 	= "Categories Data";
		$data["data_vendor"]  	= $this->ur_vendor->get("vendor_name,vendor_code");
		// echo_pre($data);exit;
		$this->load_view("content/v_categories", $data);
	}

	public function getTableCategories()
	{
		$dt_category = $this->ur_category->getCategoryVendor();

		foreach ($dt_category as $key => $value) {
			$dt_category[$key]["str"] = implode(",",$value);
		}

		echo json_encode($dt_category);
	}


	public function save_category(){
		$id 			= isset($_POST["id"]) ? $_POST["id"] : "";
		$category_name 	= isset($_POST["category_name"]) ? $_POST["category_name"] : "";
		$vendor_code 	= isset($_POST["vendor_code"]) ? $_POST["vendor_code"] : "";
		$vendor_code	= (($vendor_code == "") ? 0 :$vendor_code);

		$data = array("category_name" => $category_name ,"vendor_code" => $vendor_code,"production_cost" => 0);


		/* insert data */
		if($id == ""){
			$insert = $this->ur_category->insert($data);

			if($insert !== ""){
				echo json_encode(array("status"=>"success","messages" => "Data has been inserted with ID ". $insert."", "q" => $this->db->last_query()));
			}else{
				echo json_encode(array("status"=>"error","messages" => "Failed insert data , please try again", "q" =>$this->db->last_query()));
			}
		/* update data*/
		}else{
			$update = $this->ur_category->update($data, $id);

			if($update !== ""){
				echo json_encode(array("status"=>"success","messages" => "Data has been updated with ID ". $id));
			}else{
				echo json_encode(array("status"=>"error","messages" => "Failed update data , please try again", "q" =>$this->db->last_query()));
			}
		}
	}

	public function delete_category(){
		if(isset($_POST["id"])){
			if($this->ur_category->delete($_POST["id"]) == true){
				echo json_encode(array("status" => "success" , "messages"=>$_POST["name"]." has been deleted"));
			}else{
				echo json_encode(array("status" => "error" , "messages"=>$_POST["name"]." failed delete this data. Please try again ","q"=>$this->db->last_query()));
			}
		}		
	}
}

/* End of file products.php */
/* Location: ./application/controllers/products.php */