<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_stock extends CI_Controller {
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

		// $crud->set_column("No, Tanggal, Barcode, Nama Produk, Detail, Jumlah (Stok), Supplier");
		$column = array('#', azlang('Date'), azlang('Barcode'), azlang('Product Name'), azlang('Detail'), azlang('Total (Stock)'), azlang('Supplier'));
		$crud->set_column($column);
		$crud->set_width("10px, 150px, , , , 120px, ");
		$crud->set_th_class("no-sort, , , , , ,");
		if ($type == "out") {
			$column = array('#', azlang('Date'), azlang('Barcode'), azlang('Product Name'), azlang('Detail'), azlang('Total (Stock)'));
			$crud->set_column($column);
			$crud->set_width("10px, 150px, , , , 120px");
			$crud->set_th_class("no-sort, , , , ,");
		}
		$crud->set_id($this->table);
		$crud->set_default_url(true);
		$crud->set_url("app_url+'report_stock/get'");
		if ($type == "out") {
			$crud->set_url("app_url+'report_stock/get/?t=out'");
		}

		$crud->set_single_filter(false);
		$crud->set_btn_add(false);

		$crud->set_form('form');
		
		$this->load->library('L_product');
		$lproduct = $this->l_product;
		$data_product['data_product'] = $lproduct->render();
		$data_product['supplier'] = az_select_supplier('supplier', "element-top-filter", array("data-id" => $this->encrypt->encode("supplier.idsupplier")));

		$datetime1 = $azapp->add_datetime();
		$datetime1->set_id("transaction_date_1");
		$datetime1->set_name("transaction_date_1");
		$datetime1->set_value(Date("d-m-Y"));
		$datetime1->set_format("DD-MM-YYYY");
		$datetime1->add_class("con-element-top-filter");
		$datetime1 = $datetime1->render();

		$datetime2 = $azapp->add_datetime();
		$datetime2->set_id("transaction_date_2");
		$datetime2->set_name("transaction_date_2");
		$datetime2->set_value(Date("d-m-Y"));
		$datetime2->set_format("DD-MM-YYYY");
		$datetime2->add_class("con-element-top-filter");
		$datetime2 = $datetime2->render();

		
		$this->load->library("encrypt");
    	$stock_date = $this->encrypt->encode('product_stock.stock_date');
    	$product_barcode = $this->encrypt->encode('product.barcode');
    	$product_detail = $this->encrypt->encode('product_stock.detail');
    	$product_name = $this->encrypt->encode('product.name');
    	$iduser = $this->encrypt->encode('product_stock.iduser');
    	$data_product['stock_date'] = $stock_date;
    	$data_product['product_barcode'] = $product_barcode;
    	$data_product['product_detail'] = $product_detail;
    	$data_product['product_name'] = $product_name;
		
		$data_product['datetime1'] = $datetime1;
		$data_product['datetime2'] = $datetime2;
		$kasir = az_select_kasir('kasir', 'element-top-filter', array("data-id" => $iduser));
		$data_product['stock_type'] = $type;

		$v_view = $this->load->view('report_stock/v_report_stock_top_filter', $data_product, true);

		$crud->set_top_filter($v_view);
		
		$azapp->add_content($crud->render());

		$data_header['title'] = azlang('REPORT');
		$data_header['subtitle'] = azlang('REPORT STOCK IN');
		if ($type == "out") {
			$data_header['subtitle'] = azlang('REPORT STOCK OUT');
		}
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library("AZAppCRUD");
		$crud = $this->azappcrud;

		$get_data = $_GET;
		$stock_type = azarr($get_data, "t");

    	$crud->set_select_align(", , , , center");
    	$crud->set_select_number("4");
    	$crud->add_join("product");

    	if ($stock_type == "out") {
	    	$crud->set_select("id".$this->table.", stock_date, barcode, name, detail, total");
    		$crud->add_where("type = 'stok_keluar'");
    	}
    	else {
    		$crud->add_where("type = 'stok_masuk'");
    		$crud->set_select("id".$this->table.", stock_date, barcode, product.name as product_name, detail, total, supplier.name as supplier_name");
    		$crud->set_select_table("id".$this->table.", stock_date, barcode, product_name, detail, total, supplier_name");
    		$crud->add_join("supplier", "left");
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
}