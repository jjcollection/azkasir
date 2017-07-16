<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tes extends CI_Controller {
	public function __construct() {
        parent::__construct();
    }
	public function index(){
		$this->load->library('AZAppTable');
		$table = $this->azapptable;

		$arr_column = array('tes', 'tos', 'tas', 'tis');
		$arr_width = array('10px', '', '', '120px');
		$arr_th_class = array("no-sort", "", "no-sort", "no-sort");
		$table->set_column($arr_column);
		$table->set_width($arr_width);
		$table->set_th_class($arr_th_class);

		echo $table->render();
	}

	public function tes() {
		$this->load->helper("az_config");
		echo az_get_config('prefix_barcode');
	}

	public function az() {
		$this->load->library('AZApp');
		$azapp = $this->azapp;

		$crud = $azapp->add_crud();
		$arr_column = array('No', 'Nama', 'Alamat', 'Telepon', 'Keterangan', 'Aksi');
		$arr_width = array('10px', '', '', '', '', '120px');
		$arr_th_class = array("no-sort", "", '', '', 'no-sort', "no-sort", "no-sort");
		$crud->set_column($arr_column);
		$crud->set_width($arr_width);
		$crud->set_th_class($arr_th_class);

		$data_header['title'] = "SUPPLIER";
		$data_header['subtitle'] = "";
		$azapp->set_data_header($data_header);
		
		$azapp->add_content($crud->render());

		echo $azapp->render();
	}


	public function insert() {
		for ($i=1; $i <= 5000; $i++) { 
			$data_save = array(
				"idproduct" => 2,
				"stock_date" => Date("Y-m-d H:i:s"),
				"type" => 'stok_masuk',
				"detail" => 'stok_baru',
				"total" => $i,
				"created" => Date("Y-m-d H:i:s"),
				"updated" => Date("Y-m-d H:i:s"),
				"createdby" => "system",
				"updatedby" => "system",
			);
			$this->db->insert("product_stock", $data_save);

			// echo "oke ".$i;
			// echo "<br>";
		}
		echo "ok";
	}

	public function config() {
		// $data = $this->load->file(base_url()."application/azkasir/default/config/menu.php", true);
		$this->config->load("menu");
		print_r($this->config->item("themenu"));
	}

	public function enc() {
		$this->load->library("encrypt");
		echo $this->encrypt->decode("b2RyrsfCuhiEDRH8IOqjZJSVW33ib9Z9BpxvvK0zHNxth24RmtC7uE9ikprntNzqQFKGRAnypdCabJfg18zlLA==");
		echo "<br>";
		echo $this->encrypt->decode("n6ldF+aw7uqsIgy20Kjmhkx7Zoci/CnuT1KKFn2RgJ1tfHrW2dD3jTdl7O+KB+C+iilxW72FJ9j9wTjToj2beg==");
		echo "<br>";
		echo $this->encrypt->decode("10w4pROYnRyNDlBMJVh9P9SvECSfdKgwf82dvCHR5mexA18TbvRSCR7AY2SCeeu2UZ3lXvuBtyB3Cz3ljl6uOg==");
	}

	public function ip() {
		print_r($this->session->userdata());
	}

	public function ser() {
		echo 'apppat '.APPPATH;
		echo '<br>';
		echo 'basepat '.BASEPATH;
		echo '<br>';
		echo 'dire '.$_SERVER["SERVER_NAME"];;
	}
}