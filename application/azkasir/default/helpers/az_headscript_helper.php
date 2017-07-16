<?php
defined('BASEPATH') OR exit('No direct script access allowed');   
    if(!function_exists('put_headers')){
        function put_headers()
        {
            $str = '';
            $ci = &get_instance();
            $ci->config->load("menu");
            $ci->config->load("js_css");
            
            $header_css = $ci->config->item('header_css');
            $header_css_app = $ci->config->item('header_css_app');
            $header_js  = $ci->config->item('header_js');

     
            foreach($header_css AS $item){
                $str .= '<link rel="stylesheet" href="'.base_url().'assets/plugins/'.$item.'" type="text/css" />'."\n";
            }
     
            foreach($header_js AS $item){
                $str .= '<script type="text/javascript" src="'.base_url().'assets/plugins/'.$item.'"></script>'."\n";
            }

            foreach($header_css_app AS $item){
                $str .= '<link rel="stylesheet" href="'.base_url().'application/azkasir/default/assets/plugins/'.$item.'" type="text/css" />'."\n";
            }
     
            return $str;
        }
    }

    if(!function_exists('generate_menu')){
        function generate_menu($active_menu = '', $active_submenu = '', $submenu = '') {
            $ci = &get_instance();
            $arr_menu = $ci->config->item('menu');
            $admin_menu = array("PENGGUNA", "SETTING");
            $div_submenu = '';
            if (strlen($submenu) > 0) {
                $arr_menu = $ci->config->item('menu');
                $arr_menu = $arr_menu[$submenu]['submenu'];
                $div_submenu = '-submenu';
            }
            $return = "";
            foreach ($arr_menu as $key => $menu) {
                if ($ci->session->userdata("user_type") != "administrator") {
                    if (in_array($menu['name'], $admin_menu)) {
                        continue;
                    }
                }
                $active = "";
                if ($menu['name'] == $active_menu) {
                    $active = "active ";
                }
                $active2 = "";
                if ($menu['name'] == $active_submenu) {
                    $active2 = " active ";
                }
                $return .= '<li>
                    <a href="'.app_url().$menu['url'].'">
                        <div class="'.$active.$active2.'menu-container'.$div_submenu.'">';

                if (strlen($menu['icon']) > 0) {
                    $return .= '<div>
                                <img src="'.base_url().'application/azkasir/default/assets/images/icon/'.$menu['icon'].'"/>
                            </div>';
                }

                $return .= '<div>
                                '.$menu['name'].'
                            </div>
                        </div>
                    </a>';
                
                if (count($menu['submenu']) > 0) {
                    $return .= '<ul><div class="submenu-caret"></div><div class="submenu-box">';
                    $return .= generate_menu($active_menu, $active_submenu, $key);
                    $return .= '</div></ul>';
                }
                $return .= '</li>';
            }

            return $return;
        }
    }