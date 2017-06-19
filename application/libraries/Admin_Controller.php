<?php

session_start();

/**
 * Description of Admin_Controller
 *
 * @author pc mart ltd
 */
class Admin_Controller extends MY_Controller
{

    function __construct()
    {


        parent::__construct();
        $this->load->model('global_model');
        $this->load->model('admin_model');

        $type = $this->session->userdata('user_type');
        if ($type == 1) {
            $this->admin_model->_table_name = "tbl_menu"; //table name
            $this->admin_model->_order_by = "menu_id";
            $_SESSION["user_roll"] = $this->admin_model->get();
        } else {
            $designations_id = $this->session->userdata('designations_id');
            $_SESSION["user_roll"] = $this->global_model->select_user_roll($designations_id);
        }
        $user_flag = $this->session->userdata('user_flag');
        if (!empty($user_flag)) {
            if ($user_flag != '1') {
                $url = $this->session->userdata('url');
                redirect($url);
            }
        } else {
            redirect('locked');
        }
    }

}
