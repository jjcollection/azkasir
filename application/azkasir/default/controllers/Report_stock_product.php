<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_stock_product extends CI_Controller {
	protected $table;
	public static $balance = 0;

	public function __construct() {
        parent::__construct();
        $this->table = "product_stock";
        $this->load->helper("array");
        $this->load->helper("az_core");
        $this->load->library("encrypt");
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

		$crud->set_column("No, Tanggal, Detail, Stok Masuk, Stok Keluar, Stok");
		$crud->set_width("10px, 150px, ,80px, 80px, 80px");
		$crud->set_th_class("no-sort, no-sort, no-sort, no-sort, no-sort, no-sort");
		$crud->set_id($this->table);
		$crud->set_default_url(true);
		$crud->set_url("app_url+'report_stock_product/get'");
		$crud->set_single_filter(false);
		$crud->set_btn_add(false);

		$crud->set_form('form');

		$this->load->library('L_product');
		$lproduct = $this->l_product;
		$lproduct->add_class("element-top-filter");
		$lproduct->add_attr("data-id", $this->encrypt->encode("barcode"));
		$data_product['data_product'] = $lproduct->render();

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
		
    	$stock_date = $this->encrypt->encode('product_stock.stock_date');
    	$data_product['stock_date'] = $stock_date;
    	$data_product['idproduct_stock'] = $this->encrypt->encode("product_stock.idproduct_stock");
		
		$data_product['datetime1'] = $datetime1;
		$data_product['datetime2'] = $datetime2;
		$data_product['stock_type'] = $type;

		$v_view = $this->load->view('report_stock_product/v_report_stock_product_top_filter', $data_product, true);

		$crud->set_top_filter($v_view);
		
		$azapp->add_content($crud->render());

		$data_header['title'] = "LAPORAN";
		$data_header['subtitle'] = "LAPORAN STOK MASUK";
		if ($type == "out") {
			$data_header['subtitle'] = "LAPORAN STOK KELUAR";
		}
		$azapp->set_data_header($data_header);

		$azapp->add_js('
			jQuery("#btn_top_filter_product_stock").click(function() {
				get_single_product();
			});

			jQuery("#barcode").on("keyup", function(e) {
				jQuery("#l_product_name").text("");
		        if (e.keyCode == 13) {
		            var dtable = jQuery("#product_stock").dataTable({
					    bRetrieve: true
					});
					dtable.fnDraw();
		        }
		    });
		');

		echo $azapp->render();
	}

	public function get() {
		$this->load->library("AZAppCRUD");
		$crud = $this->azappcrud;
		$get_data = $this->input->get();
		$request = $_REQUEST;
    	$crud->set_select("id".$this->table.", stock_date, detail, if(type = 'stok_masuk', total, '') as stok_masuk, if(type = 'stok_keluar', total, '') as stok_keluar, '' as balance");
    	$crud->set_select_table("id".$this->table.", stock_date, detail, stok_masuk, stok_keluar, balance");
    	$crud->set_select_align(", , right, right, right");
    	$crud->set_select_number("2, 3");
    	$crud->add_join("product");

    	$top_filter = azarr($request, 'topfilter');
    	$arr_tf = array();
    	foreach ($top_filter as $key => $value) {
    		$arr_tf[$this->encrypt->decode($key)] = $value;
    	}
    	$f_barcode = azarr($arr_tf, "barcode");

    	if (strlen($f_barcode) == 0) {
    		$crud->add_where("idproduct_stock = 'a'");
    	}

    	$crud->set_filter('barcode');
    	$crud->set_table($this->table);
    	$crud->set_id($this->table);
    	$crud->set_order_by('stock_date');
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

		if ($column == 'balance') {
			$stock_in = azarr($data, 'stok_masuk');
			$stock_out = azarr($data, 'stok_keluar');
			$new_balance = self::$balance + $stock_in - $stock_out;
            self::$balance = $new_balance;

            return az_thousand_separator($new_balance);
		}

		return $value;
	}
}