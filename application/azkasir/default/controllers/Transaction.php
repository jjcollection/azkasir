<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {
	protected $table;

	public function __construct() {
        parent::__construct();
        $this->table = 'transaction';
        $this->load->helper("array");
        $this->load->helper("az_core");
        az_check_login();
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$this->load->helper("az_core");

		$post = $this->input->post();
		$idtransaction_group = azarr($post, 'idtransaction_group');
		$transaction_date = "";
		$total_final_price = "0";
		$code = "";
		$idcustomer = "";
		$total_cash = "";
		$total_discount = 0;
		$customer_name = "";
		if (strlen($idtransaction_group) > 0) {
			$this->db->where("transaction_group.idtransaction_group", $idtransaction_group);
			if ($this->session->userdata("user_type") != "administrator") {
				$this->db->where("transaction_group.iduser", $this->session->userdata("iduser"));
			}
			$this->db->join("customer", "customer.idcustomer = transaction_group.idcustomer", "left");
			$rdata_transaction = $this->db->get("transaction_group");

			if ($rdata_transaction->num_rows() > 0) {
				$row = $rdata_transaction->row();
				$transaction_date = $row->transaction_date;
				$transaction_date = Date("d-m-Y H:i:s", strtotime($transaction_date));
				$total_final_price = $row->total_final_price;
				$code = $row->code;
				$idcustomer = $row->idcustomer;
				$total_cash = $row->total_cash;
				$total_discount = $row->total_discount;
				$customer_name = $row->name;
			}
		}
				
		$this->load->library('AZDatetime');
		$datetime = $this->azdatetime;
		$datetime->set_id("transaction_date");
		$datetime->set_name("transaction_date");
		$datetime->add_class('x-hidden');
		if (strlen($transaction_date) == 0) {
			$datetime->set_value(Date("d-m-Y H:i:s"));
		}
		else {
			$datetime->set_value($transaction_date);
		}

		$data['transaction_date'] = $datetime->render();

		$this->load->library('L_product');
		$lproduct = $this->l_product;
		$data['product'] = $lproduct->render();

		$data['transaction_price'] = az_thousand_separator($total_final_price);

		$this->load->helper("az_config");
		$prefix = az_get_config('prefix_nota');
		$this->load->helper('az_core');
		
		if (strlen($code) > 0) {
			$transaction_group_code = $code;
		}
		else {
			$stop_do = 0;
			do {
				$random = az_generate_random(6);
				$transaction_group_code = $prefix.Date("ymd").$random;
				$this->db->where("code", $transaction_group_code);
				$rcode = $this->db->get("transaction_group");
				if ($rcode->num_rows() == 0) {
					$stop_do = 1;
				}
			} while ($stop_do < 1);
		}

		// $v_view .= "<input type='hidden' id='transaction_group_code_hd' value='".$transaction_group_code."'/>";
		$data["transaction_group_code"] = $transaction_group_code; 
		$data["idtransaction_group"] = $idtransaction_group; 
		$data["total_cash"] = $total_cash;
		$data["total_discount"] = $total_discount;

		$v_view = $this->load->view($this->table.'/v_'.$this->table, $data, true);

		$crud = $azapp->add_crud('transaction_crud');

		// $crud->set_column("No, Nama, Harga, Qty, Diskon, Total, Aksi");
		$column = array('#', azlang('Name'), azlang('Price'), azlang('Qty'), azlang('Discount'), azlang('Total'), azlang('Action'));
		$crud->set_column($column);
		$crud->set_width("10px, , 120px, 50px, 120px, 120px, 120px");
		$crud->set_th_class("no-sort, no-sort, no-sort, no-sort, no-sort, no-sort, no-sort");
		$crud->set_id($this->table);
		$crud->set_default_url(true);
		$crud->set_url("app_url+'transaction/get/?tgc=".$code."'");
		

		$callback = '
			jQuery(".transaction-price").html(response.final_price);
		';

		$crud->set_callback_save($callback);
		$crud->set_callback_delete($callback);
		$crud->set_form('form');
		$v_modal = $this->load->view($this->table.'/v_'.$this->table.'_table', '', true);
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang('Sales Transaction'));

		$crud->set_single_filter(false);
		$crud->set_btn_add(false);
		$crud->set_limit_entries(false);
		$crud->set_tpaginate('false');
		$crud->set_tinfo('false');

		$v_view .= $crud->render();
		$v_modal = $crud->generate_modal();
		$v_view .= $v_modal;

		$this->db->order_by("name");
		$data_payment["customer"] = $this->db->get("customer");
		$modal_pay = $azapp->add_modal();

		$select2 = $azapp->add_select2();
		$select2->set_id("customer");
		$select2->set_placeholder(azlang('Choose Customer'));
		$select2->set_url("customer/get_data");
		if (strlen($idcustomer) > 0) {
			$select2->add_list($idcustomer, $customer_name);
		}
		$data_payment["select_customer"] = $select2->render();
		$data_payment['transaction_group_id'] = $transaction_group_code;

		$modal_payment = $this->load->view($this->table.'/v_'.$this->table.'_payment', $data_payment, true);
		$modal_pay->set_id("payment");
		$arr_action = array(
			"save" => azlang('Payment'),
			"save-print" => azlang('Payment and Print'),
			// "save-charge" => "Simpan (Hutang)"
		);
		$modal_pay->set_action_modal($arr_action);
		$modal_pay->set_modal($modal_payment);
		$v_view .= $modal_pay->render();


		$azapp->add_content($v_view);

		$data_header['title'] = azlang('TRANSACTION');
		$data_header['subtitle'] = azlang('SALES TRANSACTION');
		$azapp->set_data_header($data_header);

    	//delete transaction group
		$this->db->where("status", "PROCESS");
		$this->db->where("iduser", $this->session->userdata("iduser"));
		$this->db->where("session", $this->session->userdata("trans_session"));
		$this->db->delete("transaction_group");

		$this->db->where("DATE_ADD(created, interval 1 hour) < now()");
		$this->db->where("status", 'PROCESS');
		$this->db->delete("transaction_group");

		echo $azapp->render();
	}

	public function get() {
		$this->load->library("AZAppCRUD");
		$code = azarr($_GET, "tgc");
		$crud = $this->azappcrud;
    	$gtable = $this->table;
    	$crud->set_select('transaction.id'.$this->table.', product.name, transaction.sell_price, transaction.qty, transaction.discount, final_price');
    	$crud->add_join('product');
    	$crud->add_join('transaction_group');
    	if (strlen($code) > 0) {
    		$crud->add_where("transaction_group.code = '".$code."'");
	    	if ($this->session->userdata("user_type") != "administrator") {
	    		$crud->add_where("transaction_group.iduser = '".$this->session->userdata("iduser")."'");
	    	}
    	}
    	else {
    		$crud->add_where("transaction_group.status = 'PROCESS'");
    		$crud->add_where("transaction_group.iduser = '".$this->session->userdata("iduser")."'");
    		$crud->add_where("transaction_group.session = '".$this->session->userdata("trans_session")."'");
    	}


    	$crud->set_select_align(", right, right, right, right, right");
    	$crud->set_select_number("1, 2, 3, 4");
    	$crud->set_filter('name');
    	$crud->set_table($gtable);
    	$crud->set_id($this->table);

		echo $crud->get_table();
	}

	public function edit() {
		$id = $this->input->post("id");

		$this->db->select("id".$this->table." as tr_idtransaction, product.barcode as tr_barcode, product.name as tr_name, sell_price as tr_sell_price, discount as tr_discount, qty as tr_qty, final_price as tr_final_price");
		$this->db->join("product", "product.idproduct = transaction.idproduct");
		$this->db->join("transaction_group", "transaction_group.idtransaction_group = transaction.idtransaction_group");
		$this->db->where("id".$this->table, $id);
		if ($this->session->userdata("user_type") != "administrator") {
			$this->db->where("transaction_group.iduser", $this->session->userdata("iduser"));
		}

		$rdata = $this->db->get($this->table)->result_array();
		echo json_encode($rdata);
	}

	public function add_transaction() {
		$data = array();
		$data["sMessage"] = "";
		
		$data_post = $this->input->post();
		$err_code = "";
		$err_message = "";
	
		$this->load->helper("array");

		$idtransaction_group = azarr($data_post, "idtransaction_group");
		$barcode = azarr($data_post, "barcode");
		$qty = azarr($data_post, "qty");
		$idcustomer = azarr($data_post, "idcustomer");
		if (strlen($idcustomer) == 0) {
			$idcustomer = NULL;
		}
		$discount = azarr($data_post, "discount", "0");
		$transaction_date = azarr($data_post, "transaction_date");
		$transaction_date = Date("Y-m-d H:i:s", strtotime($transaction_date));

		if (strlen($qty) == 0) {
			$qty = 1;
		}

		$this->db->where("barcode", $barcode);
		$data_product = $this->db->get("product");

		if ($data_product->num_rows() == 0) {
			$err_code++;
			$err_message = azlang('Product not found');
		}

		if ($err_code == 0) {
			if (strlen($idtransaction_group) == 0) {
				$insert_tg = array(
					"idcustomer" => $idcustomer,
					"iduser" => $this->session->userdata("iduser"),
					"transaction_date" => $transaction_date,
					"status" => "PROCESS",
					"session" => $this->session->userdata("trans_session"),
					"created" => Date("Y-m-d H:i:s"),
					"createdby" => $this->session->userdata("username"),
					"updated" => Date("Y-m-d H:i:s"),
					"updatedby" => $this->session->userdata("username"),
				);
				
				$this->db->insert("transaction_group", $insert_tg);
				$idtransaction_group = $this->db->insert_id();
			}


			$idproduct = $data_product->row()->idproduct;
			$sell_price = $data_product->row()->price;
			$final_price = ($sell_price * $qty) - $discount;

			$this->db->where("idtransaction_group", $idtransaction_group);
			$this->db->where("idproduct", $idproduct);
			$check_same = $this->db->get("transaction");

			if ($check_same->num_rows() == 0) {
				$data_save = array(
					"idtransaction_group" => $idtransaction_group,
					"idproduct" => $idproduct,
					"qty" => $qty,
					"discount" => $discount,
					"sell_price" => $sell_price,
					"final_price" => $final_price,
					"status" => "PROCESS",
					"updated" => Date("Y-m-d H:i:s"),
					"updatedby" => $this->session->userdata("username")
				);

				$check_stock = $this->check_stock($idproduct, $qty);
				if ($check_stock) {
					$data_save["created"] = Date("Y-m-d H:i:s");
					$data_save["createdby"] = $this->session->userdata("username");
					if(!$this->db->insert($this->table, $data_save)){
						$err = $this->db->error();
						$err_code = $err["code"];
						$err_message = $err["message"];
					}
				}
				else {
					$err_code = 9;
					$err_message = azlang('Stock not enough'); 
				}
			}
			else {
				$new_qty = $check_same->row()->qty + $qty;
				$new_final_price = ($check_same->row()->sell_price * $new_qty) - $check_same->row()->discount;
				$data_update = array(
					"qty" => $new_qty,
					"final_price" => $new_final_price,
					"updated" => Date("Y-m-d H:i:s"),
					"updatedby" => $this->session->userdata("username")
				);	

				$check_stock = $this->check_stock($check_same->row()->idproduct, $new_qty);
				if ($check_stock) {
					$this->db->where("idtransaction", $check_same->row()->idtransaction);
					if (!$this->db->update($this->table, $data_update)) {
						$err = $this->db->error();
						$err_code = $err["code"];
						$err_message = $err["message"];
					}
				}
				else {
					$err_code = 9;
					$err_message = azlang('Stock not enough'); 
				}
			}

			$this->update_transaction_group($idtransaction_group);
		}

		$data["final_price"] = $this->update_price($idtransaction_group);
		$data["idtransaction_group"] = $idtransaction_group;
		$data["sMessage"] = $err_message;
		echo json_encode($data);
	}


	public function check_stock($idproduct, $stock_in) {
		$this->db->where('idproduct', $idproduct);
		$data = $this->db->get('product');

		if ($data->num_rows() > 0) {
			$stock = $data->row()->stock;
			if ($stock < $stock_in) {
				return false;
			}
		}
		return true;
	}


	public function hold_transaction() {
		$data = array();
		$data["sMessage"] = "";
		
		$data_post = $this->input->post();
		$err_code = "";
		$err_message = "";
	
		$this->load->helper("array");
		$idtransaction_group = azarr($data_post, "idtransaction_group");

		if (strlen($idtransaction_group) > 0) {

			$this->db->where("idtransaction_group", $idtransaction_group);
			$data_transaction = $this->db->get("transaction");
			$total_price = 0;
			foreach ($data_transaction->result() as $value) {
				$total_price = $total_price + $value->final_price;
			}

			$data_hold = array(
				"total_sell_price" => $total_price,
				"total_final_price" => $total_price,
				"status" => "PENDING",
				"created" => Date("Y-m-d H:i:s"),
				"createdby" => $this->session->userdata("username"),
				"updated" => Date("Y-m-d H:i:s"),
				"updatedby" => $this->session->userdata("username"),
			);
			
			$this->db->where("idtransaction_group", $idtransaction_group);
			$this->db->update("transaction_group", $data_hold);
		}

		$data["sMessage"] = $err_message;
		echo json_encode($data);
	}

	public function update_price($idtransaction_group) {
		$this->db->where("idtransaction_group", $idtransaction_group);
		$this->db->select("sum(final_price) as final");
		$show_price = $this->db->get("transaction");
		$final = 0;
		if ($show_price->num_rows() > 0) {
			$final = $show_price->row()->final;
		}

		$this->load->helper("az_separator");

		return az_thousand_separator($final);
	}

	public function save() {
		$data = array();
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$validation = 'tr_sell_price, tr_discount, tr_qty';
		$validation_text = 'Harga, Diskon, Qty';

		$i = 0;
		$xvalidation = explode(", ", $validation);
		$xvalidation_text = explode(", ", $validation_text);
		foreach($xvalidation as $value){
			$this->form_validation->set_rules($value, $xvalidation_text[$i], 'required|xss_clean|trim');
			$i++;
		}		

		$this->form_validation->set_rules('tr_sell_price', azlang('Price'), 'required|trim|max_length[15]');
		$this->form_validation->set_rules('tr_discount', azlang('Discount'), 'required|trim|max_length[15]');
		$this->form_validation->set_rules('tr_qty', azlang('Qty'), 'required|trim|max_length[5]');

		$data_post = $this->input->post();
		$err_code = "";
		$err_message = "";

		if($this->form_validation->run() == TRUE){
			$this->load->helper("array");
			$idpost = azarr($data_post, "tr_idtransaction");
			$tr_sell_price = azarr($data_post, "tr_sell_price");
			$tr_discount = azarr($data_post, "tr_discount");
			$tr_qty = azarr($data_post, "tr_qty");
			$tr_final = azarr($data_post, "tr_final_price");

			$this->load->helper("az_separator");
			$tr_sell_price = az_remove_separator($tr_sell_price);
			$tr_discount = az_remove_separator($tr_discount);
			$tr_qty = az_remove_separator($tr_qty);
			$tr_final = az_remove_separator($tr_final);

			$data_save = array(
				"sell_price" => $tr_sell_price,
				"discount" => $tr_discount,
				"qty" => $tr_qty,
				"final_price" => $tr_final,
				"updated" => Date("Y-m-d H:i:s"),
				"updatedby" => $this->session->userdata("username")
			);

			$this->db->where("id".$this->table, $idpost);
			if (!$this->db->update($this->table, $data_save)) {
				$err = $this->db->error();
				$err_code = $err["code"];
				$err_message = $err["message"];
			}
		}

		$this->db->where("idtransaction", $idpost);
		$idtransaction_group = $this->db->get("transaction")->row()->idtransaction_group;
		$data["final_price"] = $this->update_price($idtransaction_group);
		$data["sMessage"] = validation_errors().$err_message;
		$this->update_transaction_group($idtransaction_group);
		echo json_encode($data);
	}

	public function delete() {
		$id = $this->input->post("id");
		
		$id_first = $id;
		if (is_array($id)) {
			$id_first = azarr($id, "0");
		}

		$this->db->where("idtransaction", $id_first);
		$row = $this->db->get("transaction")->row();
		$idtransaction_group = $row->idtransaction_group;
		
		$this->db->where("idtransaction_group", $idtransaction_group);
		$iduser = $this->db->get("transaction_group")->row()->iduser;
		$err_code = 0;
		if ($this->session->userdata("user_type") != "administrator") {
			if ($iduser != $this->session->userdata("iduser")) {
				$err_code++;
			}
		}

		$data["sMessage"] = "ERROR";
		if ($err_code == 0) {
			if (is_array($id)) {
				$this->db->where_in("id".$this->table, $id);
			}
			else {
				$this->db->where("id".$this->table, $id);
			}

			$this->db->delete($this->table);
			$data["sMessage"] = "SUCCESS";
		
			$data["final_price"] = $this->update_price($idtransaction_group);
			$this->update_transaction_group($idtransaction_group);
		}
		echo json_encode($data);
	}

	public function save_payment($payment_type = '') {
	    $data = array();
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('transaction_date', azlang('Transaction Date'), 'required|trim|max_length[30]');
		$this->form_validation->set_rules('total_cash', azlang('Cash Total'), 'required|trim|max_length[20]');
		$this->form_validation->set_rules('total_discount', azlang('Discount Total'), 'trim|max_length[20]');

		$data_post = $this->input->post();
		$err_code = "";
		$err_message = "";

		if($this->form_validation->run() == TRUE){
			$this->load->helper("array");
			$transaction_date = azarr($data_post, "transaction_date");
			$idcustomer = azarr($data_post, "idcustomer");
			$transaction_group_code = azarr($data_post, "transaction_group_code");
			$total_cash = azarr($data_post, "total_cash");
			$transaction_total = azarr($data_post, "transaction_total");
			$idtransaction_group = azarr($data_post, "idtransaction_group");
			$total_discount = azarr($data_post, "total_discount");
			$total_change_price = azarr($data_post, "total_change_price");
			$grand_total = azarr($data_post, "grand_total");

			$this->load->helper("az_separator");
			$total_cash = az_remove_separator($total_cash);
			$transaction_total = az_remove_separator($transaction_total);
			$total_discount = az_remove_separator($total_discount);
			$total_change_price = az_remove_separator($total_change_price);
			$grand_total = az_remove_separator($grand_total);

			$transaction_date = Date("Y-m-d H:i:s", strtotime($transaction_date));
			if (strlen($idcustomer) == 0) {
				$idcustomer = NULL;
			}

			$data_save = array(
				"transaction_date" => $transaction_date,
				"idcustomer" => $idcustomer,
				"code" => $transaction_group_code,
				"total_cash" => $total_cash,
				"total_sell_price" => $transaction_total,
				"total_discount" => $total_discount,
				"total_change" => $total_change_price,
				"total_final_price" => $grand_total,
				"iduser" => $this->session->userdata("iduser"),
				"payment_type" => $payment_type,
				"status" => "OK",
				"updated" => Date("Y-m-d H:i:s"),
				"updatedby" => $this->session->userdata("username")
			);
			
			$this->db->where("idtransaction_group", $idtransaction_group);
			if (!$this->db->update("transaction_group", $data_save)) {
				$err = $this->db->error();
				$err_code = $err["code"];
				$err_message = $err["message"];
			}

			$this->db->where("idtransaction_group", $idtransaction_group);
			$this->db->update("transaction", array("status" => "OK"));

			$this->update_transaction_group($idtransaction_group);
		}

		$data["sMessage"] = validation_errors().$err_message;
		echo json_encode($data);
	}

	private function update_transaction_group($id) {
		$this->db->where("idtransaction_group", $id);
		$this->db->select("sum(final_price) as final_price");
		$rtransaction = $this->db->get("transaction");
		$new_price = 0;
		$new_final_price = 0;

		$this->db->where("idtransaction_group", $id);
		$rdata = $this->db->get("transaction_group");

		$discount = 0;
		if ($rdata->num_rows() > 0) {
			$discount = $rdata->row()->total_discount;
		}

		if ($rtransaction->num_rows() > 0) {
			$new_price = $rtransaction->row()->final_price;
			$new_final_price = $new_price - $discount;
		}
		$data_new = array(
			"total_sell_price" => $new_price,
			"total_final_price" => $new_final_price,
			"updated" => Date("Y-m-d H:i:s"),
			"updatedby" => $this->session->userdata("username")
		);

		$this->db->where("idtransaction_group", $id);
		$this->db->update("transaction_group", $data_new);

		$this->db->where("idtransaction_group", $id);
		$this->db->where("status", "OK");
		$check_status = $this->db->get("transaction_group");

		if ($check_status->num_rows() > 0) {
			$this->db->where("idtransaction_group", $id);
			$data_transaction = $this->db->get("transaction");
			$this->db->where("idtransaction_group", $id);
			$this->db->delete("product_stock");
			foreach ($data_transaction->result() as $value) {
				$data_stock = array(
					"idproduct" => $value->idproduct,
					"idtransaction_group" => $id,
					"stock_date" => Date("Y-m-d H:i:s"),
					"type" => "stok_keluar",
					"detail" => "penjualan",
					"total" => $value->qty,
					"created" => Date("Y-m-d H:i:s"),
					"createdby" => $this->session->userdata("username"),
					"updated" => Date("Y-m-d H:i:s"),
					"updatedby" => $this->session->userdata("username")
				);

				$this->db->insert("product_stock", $data_stock);
			}
		}

	}
}