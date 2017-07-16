<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_unit extends CI_Controller {
	protected $table;

	public function __construct() {
        parent::__construct();
        $this->table = "product_unit";
        $this->load->helper("az_core");
        az_check_login();
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;

		$this->load->library('AZAppCRUD');
		$crud = $this->azappcrud;

		
		$column = array('#', azlang('Unit'), azlang('Action'));
		$crud->set_column($column);
		$crud->set_width("10px, , 120px");
		$crud->set_th_class("no-sort, , no-sort");
		$crud->set_id($this->table);
		$crud->set_default_url(true);
		$crud->set_form('form');
		$v_modal = $this->load->view($this->table.'/v_'.$this->table, '', true);
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang('Product Unit'));

		$v_view = $crud->render();
		$v_modal = $crud->generate_modal();
		$v_view .= $v_modal;
		$azapp->add_content($v_view);

		$data_header['title'] = azlang('PRODUCT');
		$data_header['subtitle'] = azlang('PRODUCT UNIT');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library("AZAppCRUD");
		$crud = $this->azappcrud;

    	$crud->set_select("id".$this->table.", name");
    	$crud->set_filter('name');
    	$crud->set_table($this->table);
    	$crud->set_sorting('name');
    	$crud->set_id($this->table);
    	$crud->set_order_by('name');

		echo $crud->get_table();
	}

	public function edit() {
		$id = $this->input->post("id");
		$this->db->select("id".$this->table.", name");
		$this->db->where("id".$this->table, $id);

		$rdata = $this->db->get($this->table)->result_array();
		echo json_encode($rdata);
	}

	public function save(){
		$data = array();
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
	
		$this->form_validation->set_rules('name', azlang('Unit'), 'required|trim|max_length[20]');	

		$data_post = $this->input->post();
		$err_code = "";
		$err_message = "";

		if($this->form_validation->run() == TRUE){
			$idpost = $data_post['id'.$this->table];

			$data_save = array(
				"name" => $data_post["name"],
				"updated" => Date("Y-m-d H:i:s"),
				"updatedby" => $this->session->userdata("username")
			);

			if($idpost == ""){
				if(!$this->db->insert($this->table, $data_save)){
					$err = $this->db->error();
					$err_code = $err["code"];
					$err_message = $err["message"];
				}
			}
			else {
				$this->db->where("id".$this->table, $idpost);
				if (!$this->db->update($this->table, $data_save)) {
					$err = $this->db->error();
					$err_code = $err["code"];
					$err_message = $err["message"];
				}
			}
		}

		$data["sMessage"] = validation_errors().$err_message;
		echo json_encode($data);
	}

	public function delete() {
		$id = $this->input->post("id");

		if (is_array($id)) {
			$this->db->where_in("id".$this->table, $id);
		}
		else {
			$this->db->where("id".$this->table, $id);
		}

		$this->db->delete($this->table);

		echo json_encode(array("SUCCESS"));
	}
}