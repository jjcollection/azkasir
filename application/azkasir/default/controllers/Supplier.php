<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {
	protected $table;

	public function __construct() {
        parent::__construct();
        $this->table = 'supplier';
        $this->load->helper("az_core");
        az_check_login();
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		
		$column = array('#', azlang('Name'), azlang('Address'), azlang('Phone'), azlang('Description'), azlang('Action'));
		$crud->set_column($column);
		$crud->set_width("10px, , , , , 120px");
		$crud->set_th_class("no-sort, , , no-sort, no-sort, no-sort");
		$crud->set_id($this->table);
		$crud->set_default_url(true);
		$crud->set_form('form');
		
		$v_modal = $this->load->view($this->table.'/v_'.$this->table, '', true);
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Supplier"));

		$v_view = $crud->render();
		$v_modal = $crud->generate_modal();
		$v_view .= $v_modal;
		$azapp->add_content($v_view);

		$data_header['title'] = azlang("SUPPLIER");
		$data_header['subtitle'] = "";
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library("AZAppCRUD");
		$crud = $this->azappcrud;

		$gselect = array('id'.$this->table, 'name', 'address', 'phone', 'description');
    	$gtable = $this->table;
    	
    	$crud->set_select("id".$this->table.", name, address, phone, description");
    	$crud->set_filter('name');
    	$crud->set_table($gtable);
    	$crud->set_sorting("name, address");
    	$crud->set_id($this->table);
    	$crud->set_order_by('name');

		echo $crud->get_table();
	}

	public function edit() {
		$id = $this->input->post("id");
		$this->db->select("id".$this->table.", name, address, phone, description");
		$this->db->where("id".$this->table, $id);

		$rdata = $this->db->get($this->table)->result_array();
		echo json_encode($rdata);
	}

	public function save(){
		$data = array();
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('name', azlang('Name'), 'required|trim|max_length[30]');
		$this->form_validation->set_rules('address', azlang('Address'), 'trim|max_length[100]');
		$this->form_validation->set_rules('phone', azlang('Phone'), 'trim|max_length[20]');
		$this->form_validation->set_rules('description', azlang('Description'), 'trim|max_length[300]');

		$data_post = $this->input->post();
		$err_code = "";
		$err_message = "";

		if($this->form_validation->run() == TRUE){
			$idpost = $data_post['id'.$this->table];

			$data_save = array(
				"name" => $data_post["name"],
				"address" => $data_post["address"],
				"phone" => $data_post["phone"],
				"description" => $data_post["description"],
				"updated" => Date("Y-m-d H:i:s"),
				"updatedby" => $this->session->userdata("username")
			);

			if($idpost == ""){
				$data_save["created"] = Date("Y-m-d H:i:s");
				$data_save["createdby"] = $this->session->userdata("username");
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

	public function get_data(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");

		$offset = ($page - 1) * $limit;

		$this->db->order_by("name");
		if (strlen($q) > 0) {
			$this->db->like("name", $q);
		}
		$this->db->select("idsupplier as id, name as text");

		$data = $this->db->get("supplier", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->like("name", $q);
		}
		$cdata = $this->db->get("supplier");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}
}