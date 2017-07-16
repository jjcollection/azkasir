<?php
defined('BASEPATH') OR exit('No direct script access allowed');   

    if(!function_exists('az_select_customer')){
        function az_select_customer($id = '', $class = '', $attr = array()) {
            $ci = &get_instance();

            $ci->load->library("AZApp");
            $azapp = $ci->azapp;
            $select2 = $azapp->add_select2();
            $select2->set_id($id);
            $select2->set_placeholder(azlang('Select Customer'));
            $select2->set_url("customer/get_data");


            if (strlen($class)) {
                $select2->add_class($class);
            }

            foreach ($attr as $key => $value) {
                $select2->add_attr($key, $value);
            }

            return $select2->render();
        }
    }

    if(!function_exists('az_select_kasir')){
        function az_select_kasir($id = '', $class = '', $attr = array()) {
            $ci = &get_instance();

            $ci->load->library("AZApp");
            $azapp = $ci->azapp;
            $select2 = $azapp->add_select2();
            $select2->set_id($id);
            $select2->set_placeholder(azlang('Select Cashier'));
            $select2->set_url("user/get_data");

            if (strlen($class)) {
                $select2->add_class($class);
            }

            foreach ($attr as $key => $value) {
                $select2->add_attr($key, $value);
            }

            return $select2->render();
        }
    }

    if(!function_exists('az_select_supplier')){
        function az_select_supplier($id = '', $class = '', $attr = array()) {
            $ci = &get_instance();

            $ci->load->library("AZApp");
            $azapp = $ci->azapp;
            $select2 = $azapp->add_select2();
            $select2->set_id($id);
            $select2->set_placeholder("Pilih Supplier");
            $select2->set_url("supplier/get_data");

            if (strlen($class)) {
                $select2->add_class($class);
            }

            foreach ($attr as $key => $value) {
                $select2->add_attr($key, $value);
            }

            return $select2->render();
        }
    }
