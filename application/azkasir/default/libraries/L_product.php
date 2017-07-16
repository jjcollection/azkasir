<?php
/**
 * AZApp
 * @author	M. Isman Subakti
 * @copyright	07-04-2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');
// require_once("../../../system/libraries/AZ.php");
if (is_file('../../../system/libraries/AZ.php')){
    require_once("../../../system/libraries/AZ.php");
}
else {
    require_once("system/libraries/AZ.php");
}

class L_product extends CI_AZ{
	protected $table = "product";
	protected $render_type = '';

	public function __construct() {
        $this->ci =& get_instance();
	}

	public function set_render_type($data) {
		return $this->render_type = $data;
	}

	public function render() {
		$class = "";
		if (strlen($this->class) > 0) {
			$class = $this->class;
		}
		$attr = "";
		if (count($this->attr) > 0) {
			foreach ($this->attr as $key => $value) {
				$attr .= $key."='".$value."' ";
			}
		}

		$data ='
			<input class="form-control '.$class.'" '.$attr.' type="text" name="barcode" id="barcode" placeholder="'.azlang('Barcode').'" tabindex="2"/>
	        <button tabindex="3" type="button" class="product-search">
	            <i class="fa fa-search"></i>
	        </button>
        ';
        $this->ci->load->library('encrypt');
        $this->ci->load->library('AZApp');
        $crud = $this->ci->azapp->add_crud('product_crud');
		
		$sf_barcode = az_add_filter_text('f_'.$this->ci->encrypt->encode('product.barcode'));
		$sf_name = az_add_filter_text('f_'.$this->ci->encrypt->encode('product.name'));

		if (strlen($this->render_type) == 0) {
			$special_filter = array($sf_barcode, $sf_name, '', '', '');
			$crud->set_column("No, Barcode, Nama, Satuan, Harga, Stok, Aksi");
			$crud->set_width("10px, , , 80px, , 50px, 80px");
			$crud->set_th_class("no-sort, , , , , , no-sort");
			$crud->set_url('app_url+"product/get_info"');
		}
		else {
			$special_filter = array($sf_barcode, $sf_name, '', '', '', '');
			$crud->set_column("No, Barcode, Nama, Satuan, Harga Dasar, Harga Pelanggan, Stok, Aksi");
			$crud->set_width("10px, , , 80px, , 50px, 50px, 80px");
			$crud->set_th_class("no-sort, , , , , , , no-sort");
			$crud->set_url('app_url+"product/get_info_customer"');	
		}

		$crud->set_id($this->table);
		$crud->set_default_url(true);
		$crud->set_btn_add(false);
		$crud->set_id($this->table);
		$crud->set_special_filter($special_filter);
		$crud->set_single_filter(false);

		$table = $crud->render();

		$crud->set_btn_save_modal(false);
		$crud->set_modal($table);
		$crud->set_modal_title("Produk");

		$modal = $crud->generate_modal();

		$js = '
			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery(".product-search").click(function() {
						show_modal("product");
						jQuery(".az-modal .modal-title-text").text("Pilih Product");
					});

			        jQuery("body").on("click", ".btn-select-product", function() {
			            var barcode = jQuery(this).attr("data-barcode");
			            jQuery("#barcode").val(barcode);
			            jQuery("#total").focus();
			            jQuery("#idproduct").val(jQuery(this).attr("data-idproduct"));
			            jQuery("#product_name").val(jQuery(this).attr("data-name"));
			            jQuery("#l_product_name").text(jQuery(this).attr("data-name"));
			            jQuery(".l-product-price").val(thousand_separator(jQuery(this).attr("data-price")));
			        });


				});
		       
		        function get_single_product() {
		        	show_loading();
		            jQuery.ajax({
		                url: app_url+"product/get_single_product",
		                data: {
		                    "barcode" : jQuery("#barcode").val(),
		                },
		                dataType: "JSON",
		                type: "POST",
		                success: function(response){
		                	hide_loading();
		                    if (response.sMessage != "") {
		                        bootbox.alert({
		                        	title: "Error",
		                        	message: response.sMessage
		                        });
		                    }
		                    else {
		                        jQuery("#idproduct").val(response.result[0].idproduct);
		                        jQuery("#product_name").val(response.result[0].name);
		                        jQuery("#l_product_name").text(response.result[0].name);
		                        jQuery(".l-product-price").val(thousand_separator(response.result[0].price));
		                        jQuery("#total").focus();
		                    }
		                },
		                error:function(response){
		                    console.log(response);
		                }
		            });
		        }
			</script>
		';

		return $data.$modal.$js;		
	}
}