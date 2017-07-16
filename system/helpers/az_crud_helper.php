<?php
defined('BASEPATH') OR exit('No direct script access allowed');   

    if(!function_exists('az_add_filter_text')){
        function az_add_filter_text($data ='') {
            $value = "<input type='text' class='form-control full-width form-filter' id='".$data."' name='".$data."' data-filter='".$data."'/>";         
            return $value;
        }
    }

    if(!function_exists('az_add_filter_select')){
        function az_add_filter_select($id = "", $data = array(), $class = "", $attr = array()) {
            $attr_data = "";
            foreach ($attr as $key => $value) {
                $attr_data .= $key." = '".$value."' ";
            }

            $value = "<select class='form-control full-width form-filter select ".$class."' id='".$id."' name='".$id."' ".$attr_data." data-filter='".$id."'>";
            $val = '<option value="">SEMUA</option>';
            foreach ($data as $key => $values) {
                $val .= "<option value='".htmlspecialchars($key)."'>".htmlspecialchars($values)."</option>";
            }
            $value .= $val;
            $value .= "</select>";

            return $value;
        }
    }