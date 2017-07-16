<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {
	protected $table;

	public function __construct() {
        parent::__construct();
        $this->table = "product";
        $this->load->helper("array");
        $this->load->helper("az_core");
        az_check_login();
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
        $crud = $azapp->add_crud();

		$column = array('#', azlang('Barcode'), azlang('Name'), azlang('Unit'), azlang('Category'), azlang('Price'), azlang('Stock'), azlang('Action'));
		$crud->set_column($column);
		$crud->set_width("10px, , , 80px, , 70px, 30px, 120px");
		$crud->set_th_class("no-sort, , , , , , no-sort");
		$crud->set_id($this->table);
		$crud->set_url('app_url+"'.$this->table.'/get"');
		$crud->set_url_edit('app_url+"'.$this->table.'/edit"');
		$crud->set_url_delete('app_url+"'.$this->table.'/delete"');
		$crud->set_url_save('app_url+"'.$this->table.'/save"');
		$crud->set_form('form');
		$crud->set_single_filter(false);
		
		$this->db->order_by("name");
		$rdata_unit = $this->db->get("product_unit");
		$this->db->order_by("name");
		$rdata_category = $this->db->get("product_category");

		$data['data_unit'] = $rdata_unit;
		// $data['data_supplier'] = $rdata_supplier;
		$data['data_category'] = $rdata_category;

		$arr_unit = array();
		foreach ($rdata_unit->result() as $key => $value) {
			$arr_unit[$value->idproduct_unit] = $value->name;
		}
		
		$arr_category = array();
		foreach ($rdata_category->result() as $key => $value) {
			$arr_category[$value->idproduct_category] = $value->name;
		}
	
		$sf_barcode = az_add_filter_text('f_'.$this->encrypt->encode('product.barcode'));
		$sf_name = az_add_filter_text('f_'.$this->encrypt->encode('product.name'));
		$sf_price = az_add_filter_text('f_'.$this->encrypt->encode('product.price'));
		$sf_stock = az_add_filter_text('f_'.$this->encrypt->encode('product.stock'));
		$sf_unit = az_add_filter_select('f_'.$this->encrypt->encode('product.idproduct_unit'), $arr_unit); 
		$sf_category = az_add_filter_select('f_'.$this->encrypt->encode('product.idproduct_category'), $arr_category); 
		
		$special_filter = array($sf_barcode, $sf_name, $sf_unit, $sf_category, $sf_price, $sf_stock);
		$crud->set_special_filter($special_filter);

		$v_modal = $this->load->view($this->table.'/v_'.$this->table, $data, true);
		$crud->set_modal($v_modal);
		$crud->set_modal_title("Produk");

		$v_view = $crud->render();
		$v_modal = $crud->generate_modal();
		$v_view .= $v_modal;
		$azapp->add_content($v_view);

		$data_header['title'] = azlang('PRODUCT');
		$data_header['subtitle'] = azlang('DATA PRODUCT');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$azapp = $this->azapp;
        $crud = $azapp->add_crud();

    	$crud->set_select('product.id'.$this->table.', product.barcode, product.name as product_name, product_category.name as product_category_name, product_unit.name as product_unit_name, product.price, product.stock');
    	$crud->set_select_table('product.barcode, product_name, product_unit_name, product_category_name, product.price, product.stock');
    	$crud->add_join("product_category", "left");
    	$crud->add_join("product_unit", "left");
    	$crud->set_order_by("product_name");
    	$crud->set_select_align(", , center, , right, center");
    	$crud->set_select_number("4, 5");
    	$crud->set_table($this->table);
    	$crud->set_sorting("product.barcode, product.name, product_unit_name, product_category_name, price, stock");
    	$crud->set_id($this->table);

		echo $crud->get_table();
	}

	public function get_info() {
		$this->load->library('AZApp');
		$azapp = $this->azapp;
        $crud = $azapp->add_crud();

		$crud->set_edit(false);
		$crud->set_delete(false);
		$crud->set_custom_btn('btn_select');

    	$gtable = $this->table;
    	$crud->set_select('product.id'.$this->table.', product.barcode, product.name, product_unit.name as product_unit_name, product.price, product.stock');
    	$crud->set_select_table("barcode, name, product_unit_name, price, stock");
    	$crud->add_join("product_unit", "left");
    	$crud->set_order_by("product.name");
    	$crud->set_select_align(", , center, right, center");
    	$crud->set_select_number("3, 4");
    	$crud->set_filter('name');
    	$crud->set_table($this->table);
    	$crud->set_sorting('barcode, name, product_unit_name, price, stock');
    	$crud->set_id($this->table);

		echo $crud->get_table();
	}

	public function get_info_customer() {
		$this->load->library('AZApp');
		$azapp = $this->azapp;
        $crud = $azapp->add_crud();

		$crud->set_edit(false);
		$crud->set_delete(false);
		$crud->set_custom_btn('btn_select');

    	$gtable = $this->table;
    	$crud->set_select("product.id".$this->table.", product.barcode, product.name, product_unit.name as product_unit_name, product.price, '' as customer_price, product.stock");
    	$crud->set_select_table("barcode, name, product_unit_name, price, customer_price, stock");
    	$crud->add_join("product_unit", "left");
    	$crud->set_order_by("product.name");
    	$crud->set_select_align(", , center, right, right, center");
    	$crud->set_select_number("3, 4, 5");
    	$crud->set_filter('name');
    	$crud->set_table($this->table);
    	$crud->set_sorting('barcode, name, product_unit_name, price, stock');
    	$crud->set_id($this->table);
    	$crud->set_custom_style('custom_style_price');

		echo $crud->get_table();
	}

	public function custom_style_price($column, $value, $data) {
		if ($column == 'customer_price') {
			$data_declare = "
				<script>
					var selected_customer = jQuery('#idcustomer').val() || 0;
				</script>
			";
			return $data_declare;
		}
		return $value;
	}

	public function btn_select($data) {
		$this->load->helper("array");
		$idproduct = htmlentities(azarr($data, "idproduct"));
		$barcode = htmlentities(azarr($data, "barcode"));
		$name = htmlentities(azarr($data, "name"));
		$price = htmlentities(azarr($data, "price"));
		$stock = htmlentities(azarr($data, "stock"));

		$btn = '<button type="button" class="btn btn-info btn-xs btn-select-product" data-dismiss="modal" data-idproduct='.$idproduct.' data-barcode= '.$barcode.' data-name="'.$name.'" data-price="'.$price.'" data-stock="'.$stock.'"><span class="glyphicon glyphicon-arrow-down"></span> Pilih</button>';
		return $btn;
	}

	public function edit() {
		$id = $this->input->post("id");
		$this->db->select("product.id".$this->table.", product.idproduct_unit, idproduct_category, barcode, product.name, product.description, price, stock");
		$this->db->where("id".$this->table, $id);

		$rdata = $this->db->get($this->table)->result_array();
		echo json_encode($rdata);
	}

	public function save(){
		$data = array();
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$validation = 'barcode, name, price';
		$validation_text = 'Barcode/Kode Barang, Nama Produk, Harga';

		$this->form_validation->set_rules('barcode', azlang('Barcode'), 'required|trim|max_length[20]');
		$this->form_validation->set_rules('idproduct_category', azlang('Category'), 'trim|numeric');
		$this->form_validation->set_rules('name', azlang('Product Name'), 'required|trim|max_length[30]');
		$this->form_validation->set_rules('idproduct_unit', azlang('Unit'), 'trim|numeric');
		$this->form_validation->set_rules('price', azlang('Price'), 'required|trim|max_length[15]');
		$this->form_validation->set_rules('description', azlang('Description'), 'trim|max_length[100]');

		$data_post = $this->input->post();

		$err_code = '';
		$err_message = '';

		$str_price = azarr($data_post, "price");
		$str_price = str_replace(".", "", $str_price);

		if($this->form_validation->run() == TRUE){
			$idpost = azarr($data_post, 'id'.$this->table);
			$idproduct_unit = azarr($data_post, "idproduct_unit");
			$idproduct_category = azarr($data_post, "idproduct_category");
			if (strlen($idproduct_unit) == 0) {
				$idproduct_unit = NULL;
			}
			if (strlen($idproduct_category) == 0) {
				$idproduct_category = NULL;
			}
			
			if (!is_numeric($str_price)) {
				$err_code++;
				$err_message = azlang('Price not valid');
			}

			if ($err_code == 0) {
				$data_save = array(
					"name" => azarr($data_post, "name"),
					"idproduct_unit" => $idproduct_unit,
					"idproduct_category" => $idproduct_category,
					"barcode" => azarr($data_post, "barcode"),
					"price" => $str_price,
					"description" => azarr($data_post, "description"),
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
		}

		if ($err_code == "1062") {
			$err_message = 	azlang('Barcode already used, please user another barcode');
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

		echo json_encode("SUCCESS");
	}

	public function generate_barcode() {
		$this->load->helper("az_config");
		$prefix = az_get_config('prefix_barcode');

		$this->db->where("substring(barcode, 5) REGEXP '^-?[0-9]+$'");
		$this->db->where("substring(barcode, 1, 4) = '".$prefix."-'");
		$this->db->order_by("substring(barcode, 5) + 0 desc");
		$data = $this->db->get("product", 1);

		if ($data->num_rows() == 0) {
			$return = $prefix."-001";
		}
		else {
			$data_barcode = $data->row()->barcode;
			$data_barcode = str_replace($prefix."-", "", $data_barcode);
			$data_barcode = $data_barcode + 1;
			$data_barcode = sprintf($prefix."-%03d", $data_barcode);
			$return = $data_barcode;
		}
		echo json_encode(array("return" => $return));
	}


	public function get_single_product() {
		$post = $this->input->post();
		$data = array();

		$barcode = azarr($post, "barcode");

		$this->db->where("barcode", $barcode);
		$data_db = $this->db->get("product");

		if ($data_db->num_rows() > 0) {
			$data["sMessage"] = "";
			$data["result"] = $data_db->result_array();
		}
		else {
			$data["sMessage"] = "Data tidak ditemukan";
		}

		echo json_encode($data);
	}
}