<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_list extends CI_Controller {
	protected $table;

	public function __construct() {
        parent::__construct();
        $this->table = 'transaction_group';
        $this->load->helper('array');
        $this->load->helper('az_separator');
        $this->load->helper("az_core");
        az_check_login();
		$this->load->library('encrypt');
		$this->load->helper("az_security");
    }

	public function index(){
		$this->load->library('AZApp');
		$this->load->helper('az_azkasir');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();

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
		
		$customer = az_select_customer('customer', 'element-top-filter', array("data-id" => $this->encrypt->encode("transaction_group.idcustomer")));
		$kasir = az_select_kasir('kasir', 'element-top-filter', array("data-id" => $this->encrypt->encode("transaction_group.iduser")));

		$data_filter["datetime1"] = $datetime1;
		$data_filter["datetime2"] = $datetime2;
		$data_filter["customer"] = $customer;
		$data_filter["kasir"] = $kasir;

		$v_filter = $this->load->view("transaction_list/v_transaction_list_top_filter", $data_filter, true);

		if ($this->session->userdata("user_type") == "administrator") {
			// $crud->set_column("No, Tanggal, Nota, Nama Barang, Harga, Diskon, Grand Total, Pembeli, Kasir, Aksi");
			$column = array('#', azlang('Date'), azlang('Nota'), azlang('Product Name'), azlang('Price'), azlang('Discount'), azlang('Grand Total'), azlang('Customer'), azlang('Cashier'), azlang('Action'));
			$crud->set_column($column);
			$crud->set_width("10px, 70px, 100px, , 60px, 60px, 80px, 70px, 70px, 170px");
			$crud->set_th_class("no-sort, , ,no-sort, , , , , , no-sort");
		}
		else {
			// $crud->set_column("No, Tanggal, Nota, Nama Barang, Harga, Diskon, Grand Total, Pembeli, Aksi");
			$column = array('#', azlang('Date'), azlang('Nota'), azlang('Product Name'), azlang('Price'), azlang('Discount'), azlang('Grand Total'), azlang('Customer'), azlang('Action'));
			$crud->set_column($column);
			$crud->set_width("10px, 70px, 100px, , 60px, 60px, 80px, 70px, 170px");
			$crud->set_th_class("no-sort, , ,no-sort, , , , ,");	
		}


		$crud->set_id("transaction_list");
		$crud->set_default_url(true);
		$crud->set_form('form');
		$crud->set_single_filter(false);
		$crud->set_btn_add(false);

		$crud->set_top_filter($v_filter);
		$v_view = $crud->render();
		$azapp->add_content($v_view);
		$azapp->add_content("
			<form id='edit_transaction' class='' method='POST' action='".app_url()."transaction'>
				<input type='hidden' id='idtransaction_group' name='idtransaction_group'/>
			</form>
		");

		$azapp->add_js_ready("
			jQuery('.btn-add-transaction_list').click(function() {
				location.href = app_url+'transaction';
			});

			jQuery('body').on('click', '.btn-edit-transaction-list', function() {
				jQuery('#idtransaction_group').val(jQuery(this).attr('data-id'));
				jQuery('#edit_transaction').submit();
			});
		");

		$data_header['title'] = azlang('REPORT');
		$data_header['subtitle'] = azlang('SALE REPORT');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library("AZAppCRUD");
		$crud = $this->azappcrud;
    	$crud->set_select("transaction_group.idtransaction_group, '' as xxx, DATE_FORMAT(transaction_group.transaction_date, '%d-%m-%Y  %H:%i:%s') as transaction_date, transaction_group.code, transaction_group.total_cash, transaction_group.total_sell_price, transaction_group.total_discount, transaction_group.total_final_price, transaction_group.idcustomer, customer.name customer_name, transaction_group.iduser, user.name");
    	$crud->set_select_table("transaction_group.idtransaction_group, transaction_group.transaction_date, transaction_group.code, xxx, transaction_group.total_sell_price, transaction_group.total_discount, transaction_group.total_final_price, customer_name, user.name");

    	$crud->add_join("user", "left");
    	$crud->add_join("customer", "left");
    	$crud->set_table("transaction_group");
    	$crud->add_where("transaction_group.status != 'PROCESS'");
    	if ($this->session->userdata("user_type") != "administrator") {
    		$crud->add_where("transaction_group.iduser = '".$this->session->userdata("iduser")."'");

    		$crud->set_select("transaction_group.idtransaction_group, '' as xxx, DATE_FORMAT(transaction_group.transaction_date, '%d-%m-%Y  %H:%i:%s') as transaction_date, transaction_group.code, transaction_group.total_cash, transaction_group.total_sell_price, transaction_group.total_discount, transaction_group.total_final_price, transaction_group.idcustomer, customer.name customer_name, transaction_group.iduser");
    		$crud->set_select_table("transaction_group.idtransaction_group, transaction_group.transaction_date, transaction_group.code, xxx, transaction_group.total_sell_price, transaction_group.total_discount, transaction_group.total_final_price, customer_name");    	
    	}
    	$crud->set_sorting('transaction_group.transaction_date, transaction_group.code, transaction_group.total_sell_price, transaction_group.total_discount, transaction_group.total_final_price, customer.name');
    	$crud->set_id("transaction_list");
    	$crud->set_custom_style("custom_style");
    	$crud->set_select_align(",,, right, right, right, left");
    	$crud->set_select_number("3, 4, 5");
    	$crud->set_order_by("transaction_group.transaction_date desc");
    	
		echo $crud->get_table();
	}

	public function custom_style($column, $value, $data) {
		$idtransaction_group = azarr($data, "idtransaction_group");
		if ($column == 'xxx') {
			$this->db->where("idtransaction_group", $idtransaction_group);
			$this->db->join("product", "product.idproduct = transaction.idproduct");
			$rdata = $this->db->get("transaction");
			$div = "<table class='full-width' style='font-size:12px;pointer-events:none;'>";
			foreach ($rdata->result() as $value) {
				$div .= "<tr>";
				$div .= "<td style='width:10px;padding-bottom:3px;border-bottom:1px solid #ccc;'><label class='label label-info'>".$value->qty."</label></td>";
				$div .= "<td style='padding-left:5px;border-bottom:1px solid #ccc;'>".$value->name."</td>";
				$div .= "<td style='padding-left:15px;text-align:right;border-bottom:1px solid #ccc;'>".az_thousand_separator($value->final_price)."</td>";
				$div .= "</tr>";
			}
			$div .= "</table>";

			return $div;
		}

		if ($column == "action") {
			preg_match("/.*(<button.*btn-delete.*button>)/", $value, $btn_delete);
			$del = azarr($btn_delete, 1);
			$edit = '<button class="btn btn-default btn-xs btn-edit-transaction-list" data-id="'.$idtransaction_group.'"><span class="glyphicon glyphicon-pencil"></span> Edit</button>';

			$print = '<a href="'.app_url().'transaction_list/print_nota/?id='.az_encode_url($idtransaction_group).'" target="_blank"><button class="btn btn-info btn-xs btn-print-transaction-list" data-id="'.$idtransaction_group.'"><span class="glyphicon glyphicon-print"></span> Cetak</button></a>';

			return $print.$edit.$del;
		}

		return "<div style='font-size:12px;'>".$value."</div>";
	}



	public function delete() {
		$id = $this->input->post("id");

		if (is_array($id)) {
			$this->db->where_in("idtransaction_group", $id);
		}
		else {
			$this->db->where("idtransaction_group", $id);
		}		
		$this->db->delete("product_stock");

		if (is_array($id)) {
			$this->db->where_in("idtransaction_group", $id);
		}
		else {
			$this->db->where("idtransaction_group", $id);
		}
		$this->db->delete("transaction_group");

		echo json_encode("SUCCESS");
	}

	public function print_nota() {
		$id = $this->input->get('id');
		$id = az_decode_url($id);
		$code = $this->input->get('code');
		$code = az_decode_url($code);
		
		if (strlen($code) > 0) {
			$this->db->where("code", $code);		
		}	
		else {
			$this->db->where("idtransaction_group", $id);
		}

		$this->db->join("user", "user.iduser = transaction_group.iduser", "left");
		$rdata = $this->db->get("transaction_group");

		if ($rdata->num_rows() > 0) {
			$data['data'] = $rdata->row();
			$this->db->join("product", "product.idproduct = transaction.idproduct");
			$this->db->where("idtransaction_group", $rdata->row()->idtransaction_group);
			$data['transaction'] = $this->db->get("transaction");
			$this->load->view("transaction_list/v_print", $data);
		}

	}
}