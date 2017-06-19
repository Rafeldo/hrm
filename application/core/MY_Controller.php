<?php

session_start();

/**
 * Description of MY_Controller
 *
 * @author Nayeem
 */
class MY_Controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('login_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->model('admin_model');
        $this->load->model('global_model');
        $this->load->helper('language');


        $system_lang = $this->admin_model->get_lang();

        $this->config->set_item('language', $system_lang);
        $files = $this->admin_model->all_files();
        if (!empty($system_lang)) {
            foreach ($files as $file => $altpath) {
                $shortfile = str_replace("_lang.php", "", $file);
                $this->lang->load($shortfile, $system_lang);
            }
        } else {
            foreach ($files as $file => $altpath) {
                $shortfile = str_replace("_lang.php", "", $file);
                $this->lang->load($shortfile, 'english');
            }
        }

        //echo $uriSegment = $this->uri->uri_string();
        $uri1 = $this->uri->segment(1);
        $uri2 = $this->uri->segment(2);
        $uri3 = $this->uri->segment(3);
        if ($uri3) {
            $uri3 = '/' . $uri3;
        }
        $uriSegment = $uri1 . '/' . $uri2 . $uri3;
        $menu_uri['menu_active_id'] = $this->admin_model->select_menu_by_uri($uriSegment);
        $menu_uri['menu_active_id'] == false || $this->session->set_userdata($menu_uri);

        $this->admin_model->_table_name = "tbl_config"; //table name
        $this->admin_model->_order_by = "config_key";
        $config_data = $this->admin_model->get();
        foreach ($config_data as $v_config_info) {
            $this->config->set_item($v_config_info->config_key, $v_config_info->value);
        }
        date_default_timezone_set(config_item('timezone'));

    }

}
