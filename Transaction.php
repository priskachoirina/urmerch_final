<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction extends Adm_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_data_template["css"] = array("/css/admin/dataTables.bootstrap.css");
		$this->_data_template["js"] = array("/js/admin/jquery.dataTables.js", "/js/admin/dataTables.bootstrap.js");
		$this->load->model(array('ur_order_detail','ur_product','ur_product_detail', 'ur_category'));
	}

	function loadDataTable(){
		$type = array(	't_print' => array("C.category_type"=>"print"), 
						"t_preorder1" => array("B.status"=>"new"),
						"t_preorder2" => array("B.status"=>"Preorder2"),
						"t_stock" => array("B.status"=>"Stock"),
						);
		$type = $type[(isset($_POST["type"]) ? $_POST["type"]: "" )];

		$data = $this->ur_product->getProductCategory($type);
		
		foreach($data as $key => $value){
			$detail = $this->ur_order_detail->get("*", array("product_code"=>$value["product_code"]));
			$data[$key]["order_status"] = (!empty($detail) ? true : false);
		}

		echo json_encode($data);
	}

	function loadDetail(){
		$id = isset($_POST["id"]) ? $_POST["id"]:"";
		$data = $this->ur_product_detail->get("*",$id);

		echo json_encode($data);
	}

	public function loadOrderByID()
	{
		$id = isset($_POST["id"]) ? $_POST["id"]:"";
		$data = $this->ur_order_detail->get("*", array("product_code"=>$id));

		foreach ($data as $key => $value) {
			$productionTime = 14;
			$datediff = datediff(date("Y-m-d h:i:s"),$value["date_modified"]);

			if($value["status"] == "Production"){
				if($datediff >= 12){
					$data[$key]["status"] = "less ".($productionTime- $datediff)." days ";
				}else if($datediff >= 8){
					$data[$key]["status"] = "finished";
				}
			} 
		}
		echo json_encode($data);
	}

	function t_print(){
		$data = array();
		$data["title_page"] = "Data Print";
		$this->load_view("content/v_transactions", $data);
	}

	function t_preorder1(){
		$data = array();
		$data["title_page"] = "Data PreOrder 1";
		$this->load_view("content/v_transactions", $data);
	}

	function t_preorder2(){
		$data = array();
		$data["title_page"] = "Data PreOrder 2";
		$this->load_view("content/v_transactions", $data);
	}

	function t_stock(){
		$data = array();
		$data["title_page"] = "Data Stock";
		$this->load_view("content/v_transactions", $data);
	}

	public function updateStock(){
		$product_id = isset($_POST["id"]) ? $_POST["id"]:"";
		$stock 		= isset($_POST["stock"]) ? $_POST["stock"]:"";
		$size		= isset($_POST["size"]) ? $_POST["size"]:"";
		$id_order	= isset($_POST["id_order"]) ? $_POST["id_order"]:"";

		$update = $this->ur_product_detail->update(array("stock"=>$stock), array("product_code"=>$product_id, "size"=>$size));
		$update = $this->ur_order_detail->update(array("status"=>"Done"), array("ID"=>$id_order));
		// echo $this->db->last_query();
		if($update === true){
			echo json_encode(array("status"=>"success", "message" => "Stock for product :".$product_id." has been changed"));
		}else{
			echo json_encode(array("status"=>"error", "message" => "Stock for product :".$product_id." can't change. Try Again"));
		}
	}

	public function changeStatus(){
		$id = isset($_POST["id"]) ? $_POST["id"]:"";
		$date_now = date("Y-m-d h:i:s");

		$update = $this->ur_order_detail->update(array("status"=>"Production", "date_modified"=> "".$date_now.""), array("ID"=> $id));

		if($update == true){
			echo json_encode(array("status"=>"success", "message" => "Status for product :".$id." has been changed"));
		}else{
			echo json_encode(array("status"=>"error", "message" => "Status for product :".$id." can't change. Try Again"));
		}
	}
}

/* End of file Transaction.php */
/* Location: ./application/controllers/Transaction.php */