<?php
defined('BASEPATH') OR exit('No direct script access allowed');   

    if(!function_exists('az_get_stock_description')){
        function az_get_stock_description($type = '', $custom = array()) {
            if ($type == '') {
	            $data = array(
	            	"penambahan_stok" => "Penambahan Stok",
                    // "stok_baru" => "Stok Baru",
	            	// "retur_penjualan" => "Retur Penjualan",
	            	"hilang" => "Hilang",
	            	"rusak" => "Rusak",
	            	"kadaluarsa" => "Kadaluarsa",
	            	"lain" => "Lain"
	            );
            }
            if ($type == 'stok_masuk') {
            	$data = array(
	            	"penambahan_stok" => "Penambahan Stok",
                    // "stok_baru" => "Stok Baru",
                    // "retur_penjualan" => "Retur Penjualan",
	            	"lain" => "Lain"
            	);
            }
            if ($type == 'stok_keluar') {
            	$data = array(
	            	"hilang" => "Hilang",
	            	"rusak" => "Rusak",
	            	"kadaluarsa" => "Kadaluarsa",
	            	"lain" => "Lain"
	            );	
            }
            if (count($custom) > 0) {
            	foreach ($custom as $key => $value) {
            		$data[$key] = $value;
            	}
            }


            return $data;
        }
    }
