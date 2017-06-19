<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('items_model');

        $this->load->helper('ckeditor');
        $this->data['ckeditor'] = array(
            'id' => 'ck_editor',
            'path' => 'asset/js/ckeditor',
            'config' => array(
                'toolbar' => "Full",
                'width' => "99.8%",
                'height' => "400px"
            )
        );
    }

    public function items_list($id = NULL) {
        $data['title'] = lang('all_items');
        if (!empty($id)) {
            $data['active'] = 2;
            $data['items_info'] = $this->items_model->check_by(array('saved_items_id' => $id), 'tbl_saved_items');
        } else {
            $data['active'] = 1;
        }
        $data['subview'] = $this->load->view('admin/items/items_list', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function saved_items($id = NULL) {
        $this->items_model->_table_name = 'tbl_saved_items';
        $this->items_model->_primary_key = 'saved_items_id';

        $data = $this->items_model->array_from_post(array('item_name', 'item_desc', 'unit_cost', 'quantity', 'item_tax_rate'));

        // update root category
        $where = array('item_name' => $data['item_name']);
        // duplicate value check in DB
        if (!empty($id)) { // if id exist in db update data
            $saved_items_id = array('saved_items_id !=' => $id);
        } else { // if id is not exist then set id as null
            $saved_items_id = null;
        }

        // check whether this input data already exist or not
        $check_items = $this->items_model->check_update('tbl_saved_items', $where, $saved_items_id);
        if (!empty($check_items)) { // if input data already exist show error alert
            // massage for user
            $type = 'error';
            $msg = "<strong style='color:#000'>" . $data['item_name'] . '</strong>  ' . lang('already_exist');
        } else { // save and update query                        
            $sub_total = $data['unit_cost'] * $data['quantity'];
            $data['item_tax_total'] = ($data['item_tax_rate'] / 100) * $sub_total;
            $data['total_cost'] = $sub_total + $data['item_tax_total'];
            $return_id = $this->items_model->save($data, $id);
            if (!empty($id)) {
                $id = $id;
                $action = 'activity_update_items';
                $msg = lang('update_items');
            } else {
                $id = $return_id;
                $action = 'activity_save_items';
                $msg = lang('save_items');
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'items',
                'module_field_id' => $id,
                'activity' => $action,
                'icon' => 'fa-circle-o',
                'value1' => $data['item_name']
            );
            $this->items_model->_table_name = 'tbl_activities';
            $this->items_model->_primary_key = 'activities_id';
            $this->items_model->save($activity);
            // messages for user
            $type = "success";
        }
        $message = $msg;
        set_message($type, $message);
        redirect('admin/items/items_list');
    }

    public function delete_items($id) {
        $items_info = $this->items_model->check_by(array('saved_items_id' => $id), 'tbl_saved_items');
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'items',
            'module_field_id' => $id,
            'activity' => 'activity_items_deleted',
            'icon' => 'fa-circle-o',
            'value1' => $items_info->item_name
        );
        $this->items_model->_table_name = 'tbl_activities';
        $this->items_model->_primary_key = 'activities_id';
        $this->items_model->save($activity);
        $this->items_model->_table_name = 'tbl_saved_items';
        $this->items_model->_primary_key = 'saved_items_id';
        $this->items_model->delete($id);
        $type = 'success';
        $message = lang('items_deleted');
        set_message($type, $message);
        redirect('admin/items/items_list');
    }

}
