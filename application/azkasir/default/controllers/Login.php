<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct() {
        parent::__construct();
    }

	public function index(){
		if (strlen($this->session->userdata("iduser")) > 0) {
			redirect(app_url()."home");
		}
		$this->load->view("v_login");
	}

	public function process() {
		$this->load->helper("array");
		$this->load->helper("az_core");
		$username = azarr($_POST, "username");
		$password = azarr($_POST, "password");

		$this->db->where("username", $username);
		$this->db->where("password", md5($password));
		$data = $this->db->get("user");

		if ($data->num_rows() > 0) {
			$data_username = $data->row()->username;
			$data_id = $data->row()->iduser;
			$data_user_type = az_get_user_type($data_id);
			$data_nama_user = $data->row()->name;

			$this->session->set_userdata("username", $data_username);
			$this->session->set_userdata("iduser", $data_id);
			$this->session->set_userdata("user_type", $data_user_type);
			$this->session->set_userdata("name", $data_nama_user);

			$this->load->helper("string");
			$str = random_string("alnum", 16);
			$random_str = $this->input->ip_address()."-".$str;
			$this->session->set_userdata("trans_session", $random_str);

			redirect(app_url()."home");
		}		
		else {
			$this->session->set_flashdata("error_login", azlang('Wrong Username/Password'));
			redirect(app_url()."login");
		}	
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect(app_url());
	}

}