<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends CI_Controller {
	protected $table;

	public function __construct() {
        parent::__construct();
        $this->table = "product_stock";
        $this->load->helper("array");
        $this->load->helper("az_core");
        az_check_login();
    }

    public function out() {
    	$this->index("out");
    }

	public function index($type = ""){
		$this->load->library('AZApp');
		$azapp = $this->azapp;

		$this->load->helper("az_azkasir");

		$crud = $azapp->add_crud();

		$column = array('#', azlang('Date'), azlang('Barcode'), azlang('Product Name'), azlang('Detail'), azlang('Total (Stock)', azlang('Action')));
		$crud->set_column($column);
		$crud->set_width("10px, , , , , 120px, 120px");
		$crud->set_th_class("no-sort, , , , , , no-sort");
		$crud->set_id($this->table);
		$crud->set_filter_placeholder(azlang('Barcode'));
		$crud->set_url("app_url+'stock/get'");
		$crud->set_url_save("app_url+'stock/save'");
		$crud->set_url_edit("app_url+'stock/edit'");
		if ($type == "out") {
			$crud->set_url("app_url+'stock/get/?t=out'");
			$crud->set_url_save("app_url+'stock/save/out'");
			$crud->set_url_edit("app_url+'stock/edit/out'");
		}

		$crud->set_url_delete("app_url+'stock/delete'");
		$crud->set_form('form');
		
		$this->load->library('L_product');
		$lproduct = $this->l_product;
		$data_product['data_product'] = $lproduct->render();
		$data_product['stock_type'] = $type;
		$data_product['supplier'] = az_select_supplier('supplier');
		$v_modal = $this->load->view('stock/v_stock', $data_product, true);
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang('Stock in'));
		if ($type == "out") {
			$crud->set_modal_title(azlang('Stock out'));
		}

		$v_js = $this->load->view('stock/v_js_stock', $data_product, true);
		$v_js = str_replace('<script>', '', $v_js);
		$azapp->add_js($v_js);

		$v_view = $crud->render();
		$v_modal = $crud->generate_modal();
		$v_view .= $v_modal;
		$azapp->add_content($v_view);

		$data_header['title'] = azlang('STOCK');
		$data_header['subtitle'] = azlang('STOCK IN');
		if ($type == "out") {
			$data_header['subtitle'] = azlang('STOCK OUT');
		}
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library("AZAppCRUD");
		$crud = $this->azappcrud;

		$get_data = $_GET;
		$stock_type = azarr($get_data, "t");

    	$crud->set_select("id".$this->table.", stock_date, barcode, name, detail, total");
    	$crud->set_select_align(", , , , center");
    	$crud->set_select_number("4");
    	$crud->add_join("product");

    	if ($stock_type == "out") {
    		$crud->add_where("type = 'stok_keluar'");
    		$crud->add_where("detail != 'penjualan'");
    	}
    	else {
    		$crud->add_where("type = 'stok_masuk'");
    	}

    	$crud->set_filter('barcode');
    	$crud->set_table($this->table);
    	$crud->set_sorting('stock_date, barcode, name, detail, total');
    	$crud->set_id($this->table);
    	$crud->set_order_by('stock_date~desc');
    	$crud->set_custom_style("custom_style");

		echo $crud->get_table();
	}

	public static function custom_style($column, $value, $data) {
		if ($column == 'detail') {
			$detail2 = azarr($data, 'detail2');
			if ($value == 'lain' && strlen($detail2) > 0) {
				return "Lain (".$detail2.")";
			}
			return ucwords(str_replace("_", " ", $value));
		}
		if ($column == 'stock_date') {
			if (strlen($value) > 0) {
				return Date("d-m-Y H:i:s", strtotime($value));
			}
		}

		$type = azarr($data, "detail");
		if ($type == "penjualan" && $column == "action") {
			return "";
		}

		return $value;
	}

	public function edit() {
		$id = $this->input->post("id");
		$this->db->select("id".$this->table.", DATE_FORMAT(stock_date, '%d-%m-%Y %H:%i:%s') as stock_date, product_stock.idproduct, barcode, product.name as product_name, product.stock as product_stock_now, detail, detail2, total");
		$this->db->join("product", "product.idproduct = product_stock.idproduct");
		$this->db->where("id".$this->table, $id);

		$rdata = $this->db->get($this->table)->result_array();
		echo json_encode($rdata);
	}

	public function save($type = ""){
		$data = array();
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('idproduct', azlang('Product Name'), 'required|trim|numeric');
		$this->form_validation->set_rules('detail', azlang('Detail'), 'required|trim');
		$this->form_validation->set_rules('stock_date', azlang('Stock Date'), 'required|trim');
		$this->form_validation->set_rules('total', azlang('Total Stock'), 'required|trim');
		$this->form_validation->set_rules('detail2', azlang('Other Detail'), 'trim|max_length[20]');

		$data_post = $this->input->post();
		$err_code = "";
		$err_message = "";

		$total_stock = azarr($data_post, "total");
		$total_stock = str_replace(".", "", $total_stock);

		if ($err_code == 0) {
			if($this->form_validation->run() == TRUE){
				if (!is_numeric($total_stock)) {
					$err_code++;
					$err_message = azlang('Invalid Stock Total');
				}
				if ($err_code == 0) {
					if ($total_stock < 1) {
						$err_code++;
						$err_message = azlang('Stock must more than 0');
					}
				}
				if ($err_code == 0) {
					if (az_check_date(azarr($data_post, 'stock_date')) == FALSE) {
						$err_code++;
						$err_message = azlang('Wrong date format');
					}
				}
			}
			else {
				$err_code++;
			}
		}

		if ($err_code == 0) {
			$idpost = $data_post['id'.$this->table];

			$stock_type = "stok_masuk";
			if ($type == "out") {
				$stock_type = "stok_keluar";
			}

			$data_save = array(
				"idproduct" => azarr($data_post, "idproduct"),
				"stock_date" => Date("Y-m-d H:i:s", strtotime(azarr($data_post, "stock_date"))),
				"type" => $stock_type,
				"detail" => azarr($data_post, "detail"),
				"detail2" => azarr($data_post, "detail2"),
				"total" => $total_stock,
				"updated" => Date("Y-m-d H:i:s"),
				"updatedby" => $this->session->userdata("username")
			);

			$idsupplier = azarr($data_post, "idsupplier");
			if (strlen($idsupplier) > 0) {
				$data_save["idsupplier"] = $idsupplier;
			}

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
}