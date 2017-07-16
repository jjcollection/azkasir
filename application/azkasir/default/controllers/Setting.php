<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {
	protected $table;

	public function __construct() {
        parent::__construct();
        $this->table = 'supplier';
        $this->load->helper("az_core");
        az_check_login("administrator");
        $this->load->helper("array");
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		
		$data_header['title'] = "SETTING";
		$data_header['subtitle'] = "";
		$azapp->set_data_header($data_header);

		$data = array();
		$this->load->helper("az_config");
		$data['prefix_nota'] = az_get_config('prefix_nota');
		$data['prefix_barcode'] = az_get_config('prefix_barcode');
		$data['store_name'] = az_get_config('store_name');
		$data['store_description'] = az_get_config('store_description');
		$data['store_phone'] = az_get_config('store_phone');
		$image = $azapp->add_image();
		$image->set_id('logo');
		$image->set_image_width("140px");
		$image->set_image_height("50px");
		$image->set_image_url(base_url().'application/azkasir/default/assets/images/logo.png');
		$data["image"] = $image->render();

		$content = $this->load->view("setting/v_setting", $data, true);
		$azapp->add_content($content);

		echo $azapp->render();
	}

	
	public function save(){
		$data = array();
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('store_name', azlang('Store Name'), 'required|trim|max_length[20]');
		$this->form_validation->set_rules('store_description', azlang('Store Description'), 'required|trim|max_length[70]');
		$this->form_validation->set_rules('prefix_nota', azlang('Prefix Nota'), 'required|trim|max_length[4]');
		$this->form_validation->set_rules('prefix_barcode', azlang('Prefix Barcode'), 'required|trim|max_length[3]');
		$this->form_validation->set_rules('store_phone', azlang('Phone'), 'required|trim|max_length[50]');

		$data_post = $this->input->post();
		$err_code = 0;
		$err_message = '';
                
        if ($err_code == 0) {
			if($this->form_validation->run() == TRUE){
				foreach ($data_post as $key => $value) {
					$this->db->where("key", $key);
					if (!$this->db->update("config", array("value" => $value))) {
						$err = $this->db->error();
						$err_code = $err["code"];
						$err_message = $err["message"];
					}
				}

				if(isset($_FILES['logo']['tmp_name'])){
					$config['upload_path'] = '../../../application/azkasir/default/assets/images/';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['overwrite'] = true;
					$config['max_size']	= '300';
					$config['max_width']  = '1000';
					$config['file_name'] = 'logo.png';
					
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('logo')){
						$err_message = $this->upload->display_errors();
					}
					else {
						$data = array('upload_data' => $this->upload->data());
					}
				}
			}
        }
		$data["sMessage"] = validation_errors().$err_message;
		echo json_encode($data);
	}

	
}