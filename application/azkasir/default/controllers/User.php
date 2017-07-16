<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	protected $table;

	public function __construct() {
        parent::__construct();
        $this->load->helper("array");
        $this->load->helper("az_core");
        az_check_login();
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;

		$crud = $azapp->add_crud();
		$column = array('#', azlang('Username'), azlang('Name'), azlang('Action'));
		$crud->set_column($column);
		$crud->set_width("10px, '', '', 120px");
		$crud->set_th_class("no-sort, , , no-sort");
		$crud->set_id("user");
		$crud->set_default_url(true);
		$crud->set_form('form');

		$v_modal = $this->load->view('user/v_user', '', true);
		$crud->set_modal($v_modal);
		$crud->set_modal_title("User");

		$v_view = $crud->render();
		$v_modal = $crud->generate_modal();
		$v_view .= $v_modal;
		$azapp->add_content($v_view);

		$data_header['title'] = azlang('USER');
		$data_header['subtitle'] = "";
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library("AZAppCRUD");
		$crud = $this->azappcrud;

    	$crud->set_select("iduser, username, name");
    	$crud->set_table("user");
    	$crud->add_where("username != 'administrator'");
    	$crud->add_where("username != 'azost'");
    	$crud->set_sorting('username, name');
    	$crud->set_id("user");
    	$crud->set_filter("nama");
    	$crud->set_order_by('username');
    	
		echo $crud->get_table();
	}

	public function edit() {
		$id = $this->input->post("id");
		$this->db->select("iduser, username, name");
		$this->db->where("iduser", $id);
		$rdata = $this->db->get("user")->result_array();
		echo json_encode($rdata);
	}

	public function save(){
		$data = array();
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules("username", azlang('Username'), "required|trim|max_length[20]");
		$this->form_validation->set_rules("password", azlang('Password'), "trim|max_length[20]");
		$this->form_validation->set_rules("name", azlang('Name'), "required|trim|max_length[30]");

		$data_post = $this->input->post();
		$err_code = 0;
		$err_message = "";
		$idpost = azarr($data_post, 'iduser');
		
		if (strlen($idpost) == 0) {
			if (strlen(azarr($data_post, "password")) == 0) {
				$err_code++;
				$err_message = azlang('Password required');
			}
		}

		if ($err_code == 0) {
			if($this->form_validation->run() == TRUE){

				$data_save = array(
					"username" => azarr($data_post, "username"),
					"name" => azarr($data_post, "name"),
					"updated" => Date("Y-m-d H:i:s"),
					"updatedby" => $this->session->userdata("username")
				);

				if($idpost == ""){
					$data_save["password"] = md5(azarr($data_post, "password"));
					$data_save["created"] = Date("Y-m-d H:i:s");
					$data_save["createdby"] = $this->session->userdata("username");
					if(!$this->db->insert("user", $data_save)){
						$err = $this->db->error();
						$err_code = $err["code"];
						$err_message = $err["message"];
					}
					if (strlen($err_code) == 0) {
						$iduser = $this->db->insert_id();
						$this->load->library("AZUser");
						$this->azuser->add_user($iduser, "kasir");
					}
				}
				else {
					if (strlen(azarr($data_post, "password")) > 0) {
						$data_save["password"] = md5(azarr($data_post, "password"));
					}
					$this->db->where("iduser", $idpost);
					if (!$this->db->update("user", $data_save)) {
						$err = $this->db->error();
						$err_code = $err["code"];
						$err_message = $err["message"];
					}
				}	
			}
		}

		$data["sMessage"] = validation_errors().$err_message;
		echo json_encode($data);
	}

	public function delete() {
		$id = $this->input->post("id");

		if (is_array($id)) {
			$id_in = implode(", ", $id);
			$this->db->where("iduser"." in (".$id_in.")");
		}
		else {
			$this->db->where("iduser", $id);
		}
		$this->db->delete("user");

		$data["err_code"] = $err_code;
		$data["err_message"] = $err_message;
		echo json_encode($data);
	}

	public function ubah_password() {
		$this->load->library("AZApp");
		$azapp = $this->azapp;

		$view = $this->load->view("user/v_ubah_password", "", true);
		$azapp->add_content($view);

		$data["title"] = azlang('CHANGE PASSWORD');
		$data["subtitle"] = "";
		$azapp->set_data_header($data);
		echo $azapp->render();
	}

	public function proses_ubah_password() {
		$iduser = $this->session->userdata("iduser");
		$post_data = $this->input->post();

		$password_lama = azarr($post_data, "password_lama");
		$password_baru = azarr($post_data, "password_baru");
		$password_ulang = azarr($post_data, "password_ulang");

		$err_code = 0;
		$err_message = "";

		if (strlen($password_lama) == 0) {
			$err_code++;
			$err_message = azlang('Old password required');
		}
		else if (strlen($password_baru) == 0){
			$err_code++;
			$err_message = azlang('New password required');
		}
		else if (strlen($password_ulang) == 0) {
			$err_code++;
			$err_message = azlang('Confirm password required');
		}
		else if ($password_baru != $password_ulang) {
			$err_code++;
			$err_message = azlang('Confirm password not valid');
		}
		else {
			$this->db->where("iduser", $iduser);
			$this->db->where("password", md5($password_lama));
			$rdata = $this->db->get("user");
			if ($rdata->num_rows() == 0) {
				$err_code++;
				$err_message = azlang('Wrong old password');
			}
		}

		if ($err_code == 0) {
			$this->db->where("iduser", $iduser);
			$this->db->update("user", array("password" => md5($password_baru)));
		}

		$data = array();
		$data["sMessage"] = $err_message;
		echo json_encode($data);
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
		$this->db->select("iduser as id, name as text");
		$this->db->where("username != 'administrator'");
		$data = $this->db->get("user", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->like("name", $q);
		}
		$this->db->where("username != 'administrator'");
		$cdata = $this->db->get("user");
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