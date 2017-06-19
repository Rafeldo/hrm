<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stock extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('stock_model'); // load categol_model
    }

    public function stock_category($id = null, $sub_id = null)
    {

        $data['title'] = lang('all') . ' ' . lang('stock_category');
        $data['active'] = 1;
        if ($id) { // retrive data from db by id
            $data['active'] = 2;
            // get all department by id
            $data['category_info'] = $this->db->where('stock_category_id', $id)->get('tbl_stock_category')->row();

            $data['sub_category_info'] = $this->db->where('stock_sub_category_id', $sub_id)->get('tbl_stock_sub_category')->row();

            if (empty($data['sub_category_info'])) {
                $type = "error";
                $message = lang('no_record_found');
                set_message($type, $message);
                redirect('admin/department/department_list');
            }
        }
        $this->stock_model->_table_name = "tbl_stock_category"; //table name
        $this->stock_model->_order_by = "stock_category_id";
        $data['stock_category_info'] = $this->stock_model->get();
        // get all department info and designation info
        foreach ($data['stock_category_info'] as $v_stock_category_info) {
            $data['all_stock_category_info'][] = $this->stock_model->get_stock_category_info_by_id($v_stock_category_info->stock_category_id);
        }

        $data['subview'] = $this->load->view('admin/stock/stock_category_list', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function save_stock_category($id = NULL)
    {

        $this->stock_model->_table_name = "tbl_stock_category"; // table name
        $this->stock_model->_primary_key = "stock_category_id"; // $id

        $where = $this->stock_model->array_from_post(array('stock_category_id')); //input post

        $data['stock_category'] = $this->input->post('stock_category', true);
        // check department by department_name
        // if not empty return this id else save
        $check_category = $this->stock_model->check_by($where, 'tbl_stock_category');
        $check_input_category = $this->stock_model->check_by($data, 'tbl_stock_category');
        if (!empty($check_category)) {
            $stock_category_id = $check_category->stock_category_id;
            $actvt = $check_category->stock_category;
        } elseif (!empty($check_input_category)) {
            $stock_category_id = $check_input_category->stock_category_id;
            $actvt = $check_input_category->stock_category;
        } else {
            $data['stock_category'] = $this->input->post('stock_category', true);
            $actvt = $data['stock_category'];
            if (!empty($data['stock_category'])) {
                $stock_category_id = $this->stock_model->save($data);
            } else {
                $stock_category_id = $this->stock_model->save($data, $id);
            }
        }
        // input data
        $sub_data['stock_sub_category'] = $this->input->post('stock_sub_category', TRUE);
        $sub_data['stock_category_id'] = $stock_category_id;

        $this->stock_model->_table_name = "tbl_stock_sub_category"; // table name
        $this->stock_model->_primary_key = "stock_sub_category_id"; // $id
        // update input data stock_sub_category_id
        $stock_sub_category_id = $this->input->post('stock_sub_category_id', TRUE);
        if (!empty($stock_sub_category_id)) { // if stock_sub_category_id is not empty then update else save
            $this->stock_model->save($sub_data, $stock_sub_category_id);
        } else {
            $this->stock_model->save($sub_data);
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'stock',
            'module_field_id' => $id,
            'activity' => ('activity_added_stock_category'),
            'value1' => $actvt
        );
        $this->stock_model->_table_name = 'tbl_activities';
        $this->stock_model->_primary_key = 'activities_id';
        $this->stock_model->save($activity);

        $type = "success";
        $message = lang('category_added_successfully');
        set_message($type, $message);
        redirect('admin/stock/stock_category');
    }

    public function edit_stock_category($id)
    {

        $post = $this->stock_model->array_from_post(array('stock_category')); //input post

        if (!empty($post['stock_category'])) {

            $this->stock_model->_table_name = "tbl_stock_category"; // table name
            $this->stock_model->_primary_key = "stock_category_id"; // $id
            $this->stock_model->save($post, $id);

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'stock',
                'module_field_id' => $id,
                'activity' => ('activity_update_stock_category'),
                'value1' => $post['stock_category']
            );
            $this->stock_model->_table_name = 'tbl_activities';
            $this->stock_model->_primary_key = 'activities_id';
            $this->stock_model->save($activity);

            $type = "success";
            $message = lang('category_added_successfully');
            set_message($type, $message);
            redirect('admin/stock/stock_category'); //redirect page
        }

        $data['title'] = lang('edit') . ' ' . lang('stock_category');
        $data['stock_category_info'] = $this->db->where('stock_category_id', $id)->get('tbl_stock_category')->row();

        $data['modal_subview'] = $this->load->view('admin/stock/edit_stock_category', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }

    public function delete_stock_category($id)
    {

        $where = array('stock_category_id' => $id);

        //get sub category id by stock_category_id
        // check into stock sub category table
        // if data exist do not delete the stock category
        // else delete the stock category
        $get_stock_sub_category_id = $this->stock_model->check_by($where, 'tbl_stock_sub_category');

        $or_where = array('stock_sub_category_id' => $get_stock_sub_category_id->stock_sub_category_id);
        $get_existing_id = $this->stock_model->check_by($or_where, 'tbl_stock');
        if (!empty($get_existing_id)) {
            $type = "error";
            $message = lang('category_already_exist');
        } else {
            // delete all department by id
            $this->stock_model->_table_name = "tbl_stock_category"; // table name
            $this->stock_model->_primary_key = "stock_category_id"; // $id
            $this->stock_model->delete($id);

            // delete all designation by  department id
            $this->stock_model->_table_name = "tbl_stock_sub_category"; // table name
            $this->stock_model->delete_multiple($where);
            $type = "success";
            $message = lang('category_deleted');
        }
        set_message($type, $message);
        redirect('admin/stock/stock_category');
    }

    public function delete_stock_sub_category($id)
    {
        // check into stock_sub_category table by id
        // if data exist do not delete the stock_category
        // else delete the stock_category

        $where = array('stock_sub_category_id' => $id);
        $get_existing_id = $this->stock_model->check_by($where, 'tbl_stock');
        if (!empty($get_existing_id)) {
            $type = "error";
            $message = lang('sub_category_already_exist');
        } else {
            // delete all stock_sub_category by id
            $this->stock_model->_table_name = "tbl_stock_sub_category"; // table name
            $this->stock_model->_primary_key = "stock_sub_category_id"; // $id
            $this->stock_model->delete($id);
            $type = "success";
            $message = lang('sub_category_deleted');
        }
        set_message($type, $message);
        redirect('admin/stock/stock_category');
    }

    public function stock_list($id = null)
    {
        $data['title'] = lang('all') . ' ' . lang('stock');
        if ($id) { // retrive data from db by id
            $data['active'] = 2;
            $data['stock_info'] = $this->stock_model->get_stock_info_by_id($id);

            if (empty($data['stock_info'])) {
                $type = "error";
                $message = lang('no_record_found');
                set_message($type, $message);
                redirect('admin/stock/stock_category');
            }
        } else {
            $data['active'] = 1;
        }
        // retrive all data from department table
        $this->stock_model->_table_name = "tbl_stock_category"; //table name
        $this->stock_model->_order_by = "stock_category_id";
        $all_cate_info = $this->stock_model->get();
        // get all category info and designation info
        foreach ($all_cate_info as $v_cate_info) {
            $data['all_category_info'][$v_cate_info->stock_category] = $this->stock_model->get_sub_category_by_id($v_cate_info->stock_category_id);
        }

        $all_stock_info = $this->stock_model->get_all_stock_info();
        foreach ($all_stock_info as $v_stock_info) {
            $data['all_stock_info'][$v_stock_info->stock_category][$v_stock_info->stock_sub_category] = $this->stock_model->get_all_stock_info($v_stock_info->stock_sub_category_id);
        }
        $data['subview'] = $this->load->view('admin/stock/stock_list', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function save_stock($id = NULL)
    {


        $this->stock_model->_table_name = "tbl_stock"; // table name
        $this->stock_model->_primary_key = "stock_id"; // $id

        $data = $this->stock_model->array_from_post(array('stock_sub_category_id', 'item_name')); //input post
        $where = array('stock_sub_category_id' => $data['stock_sub_category_id'], 'item_name' => $data['item_name']);
        // check sub category and item by their id
        // if not empty return this id else save
        $check_sub_category_and_item = $this->stock_model->check_by($where, 'tbl_stock');

        if (!empty($check_sub_category_and_item)) {
            $stock_id = $check_sub_category_and_item->stock_id;
            $actv = 'activity_update_stock';
        } else {
            $stock_id = $this->stock_model->save($data, $id);
            $actv = 'activity_added_stock';
        }

        // input data
        $this->stock_model->_table_name = "tbl_item_history"; // table name
        $this->stock_model->_primary_key = "item_history_id"; // $id
        // input data and save to tbl_item history
        $item_data = $this->stock_model->array_from_post(array('inventory', 'purchase_date')); //input post
        // get input item_history_id
        $item_history_id = $this->input->post('item_history_id', TRUE);
        $item_data['stock_id'] = $stock_id;

        if (!empty($item_history_id)) {
            $this->stock_model->save($item_data, $item_history_id);
        } else {
            $this->stock_model->save($item_data);
        }
        // get total stock by stock id
        $this->stock_model->_order_by = "stock_id"; // $id
        $get_all_stock_by_id = $this->stock_model->get_by(array('stock_id' => $stock_id), FALSE);

        $total_inventory = 0;
        foreach ($get_all_stock_by_id as $v_stock_id) {
            $total_inventory += $v_stock_id->inventory;
        }

        $this->stock_model->_table_name = "tbl_stock"; // table name
        $this->stock_model->_primary_key = "stock_id"; // $id

        $udata['total_stock'] = $total_inventory;
        $this->stock_model->save($udata, $stock_id);

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'stock',
            'module_field_id' => $id,
            'activity' => $actv,
            'value1' => $data['item_name']
        );
        $this->stock_model->_table_name = 'tbl_activities';
        $this->stock_model->_primary_key = 'activities_id';
        $this->stock_model->save($activity);

        $type = "success";
        $message = lang('stock_successfully_added');
        set_message($type, $message);
        redirect('admin/stock/stock_list');
    }

    public function delete_stock($stock_id)
    {
        // check into tbl_assign_stock by stock id
        // if exist the id then do not delete else delete this history
        $check_existing_stock_id = $this->db->where('stock_id', $stock_id)->get('tbl_assign_item')->row();
        if (!empty($check_existing_stock_id)) {
            $type = "error";
            $message = lang('stock_already_exist');
        } else {
            // count total stock id exist or not
            // if stock id is one then delete into tbl_stock
            // else do not delete
            $stock_info = $this->db->where('stock_id', $stock_id)->get('tbl_stock')->row();

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'stock',
                'module_field_id' => $stock_id,
                'activity' => 'activity_delete_stock',
                'value1' => $stock_info->item_name
            );
            $this->stock_model->_table_name = 'tbl_activities';
            $this->stock_model->_primary_key = 'activities_id';
            $this->stock_model->save($activity);


            $this->stock_model->_table_name = "tbl_stock"; //table name
            $this->stock_model->_primary_key = "stock_id";
            $this->stock_model->delete($stock_id);

            $type = "success";
            $message = lang('stock_deleted');
        }
        set_message($type, $message);
        redirect('admin/stock/stock_list');
    }

    public function stock_history($sub_category_id = NULL)
    {
        $data['title'] = "Stock History";

        // retrive all data from department table
        $this->stock_model->_table_name = "tbl_stock_category"; //table name
        $this->stock_model->_order_by = "stock_category_id";
        $all_cate_info = $this->stock_model->get();

        // get all category info and designation info
        foreach ($all_cate_info as $v_cate_info) {
            $data['all_category_info'][$v_cate_info->stock_category] = $this->stock_model->get_sub_category_by_id($v_cate_info->stock_category_id);
        }
        $flag = $this->input->post('flag');
        if (!empty($flag) || !empty($sub_category_id)) {
            $data['flag'] = 1;
            if (!empty($sub_category_id)) {
                $data['sub_category_id'] = $sub_category_id;
            } else {
                $data['sub_category_id'] = $this->input->post('stock_sub_category_id');
            }
            //get stock id by sub category id

            $get_stock_id = $this->stock_model->get_all_stock_info($data['sub_category_id']);

            //get item history by stock id
            foreach ($get_stock_id as $v_stock_id) {
                $data['item_history_info'][$v_stock_id->stock_sub_category][$v_stock_id->item_name] = $this->stock_model->get_item_history_by_id($v_stock_id->stock_id);
            }
        }
        $data['subview'] = $this->load->view('admin/stock/stock_history', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function delete_stock_history($stock_id, $item_history_id, $sub_category_id)
    {
        // check into tbl_assign_stock by stock id
        // if exist the id then do not delete else delete this history
        $this->stock_model->_table_name = "tbl_assign_item"; //table name
        $this->stock_model->_order_by = "stock_id";
        $check_existing_stock_id = $this->stock_model->get_by(array('stock_id' => $stock_id), FALSE);
        if (!empty($check_existing_stock_id)) {
            $type = "error";
            $message = lang('stock_already_exist');
        } else {
            // count total stock id exist or not  
            // if stock id is one then delete into tbl_stock 
            // else do not delete 
            $this->stock_model->_table_name = "tbl_item_history"; //table name
            $this->stock_model->_order_by = "stock_id";
            $count_stock_id = $this->stock_model->get_by(array('stock_id' => $stock_id), FALSE);
            if (count($count_stock_id) == 1) {
                $this->stock_model->_table_name = "tbl_stock"; //table name
                $this->stock_model->_primary_key = "stock_id";
                $this->stock_model->delete($stock_id);
            }

            $this->stock_model->_table_name = "tbl_item_history"; //table name
            $this->stock_model->_primary_key = "item_history_id";
            $this->stock_model->delete($item_history_id);

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'stock',
                'module_field_id' => $stock_id,
                'activity' => 'activity_delete_item_history',
                'value1' => $count_stock_id->item_name
            );
            $this->stock_model->_table_name = 'tbl_activities';
            $this->stock_model->_primary_key = 'activities_id';
            $this->stock_model->save($activity);


            $type = "success";
            $message = lang('stock_history_deleted');
        }
        set_message($type, $message);
        redirect('admin/stock/stock_history/' . $sub_category_id);
    }

    public function assign_stock($id = NULL)
    {
        $data['title'] = lang('assign_stock');

        if (!empty($id)) {
            $data['active'] = 2;
            // get assign_item id by assign_item_id
            $data['assign_item'] = $this->db->where('assign_item_id', $id)->get('tbl_assign_item')->row();
            // get sub category id by stock id
            $data['stock_info'] = $this->db->where('stock_id', $data['assign_item']->stock_id)->get('tbl_stock')->row();
            // get item name by stock stock_sub_category_id
            $data['sub_category_info'] = $this->db->where('stock_sub_category_id', $data['sub_category_info']->stock_sub_category_id)->get('tbl_stock_sub_category')->row();

        } else {
            $data['active'] = 1;
        }
        // get all employee
        $data['all_employee'] = $this->stock_model->get_all_employee();

        // retrive all data from tbl_stock_category table
        $this->stock_model->_table_name = "tbl_stock_category"; //table name
        $this->stock_model->_order_by = "stock_category_id";
        $all_cate_info = $this->stock_model->get();
        // get all category info and get_sub_category info
        foreach ($all_cate_info as $v_cate_info) {
            $data['all_category_info'][$v_cate_info->stock_category] = $this->stock_model->get_sub_category_by_id($v_cate_info->stock_category_id);
        }

        $data['subview'] = $this->load->view('admin/stock/assign_stock', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function set_assign_stock($id = NULL)
    {
        // input data
        $data = $this->stock_model->array_from_post(array('stock_id', 'user_id', 'assign_inventory', 'assign_date')); //input post
        // check enough stock  by assign inventory
        // if total stock is greterthan then assign 
        // else show error message        
        $check_enough_stock = $this->stock_model->check_by(array('stock_id' => $data['stock_id'], 'total_stock >=' => $data['assign_inventory']), 'tbl_stock');
        $total_stock = $this->stock_model->check_by(array('stock_id' => $data['stock_id']), 'tbl_stock');
        if (empty($check_enough_stock)) {

            $type = "error";
            $message = lang('exceed_stock') . "<strong style='color:#000'> " . $total_stock->total_stock . " </strong> ";
        } else {

            $this->stock_model->_table_name = 'tbl_assign_item';
            $this->stock_model->_primary_key = 'assign_item_id';
            $this->stock_model->save($data, $id);

            // get reduce inventory by id
            $this->stock_model->reduce_inventory($data['stock_id'], $data['assign_inventory']);

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'stock',
                'module_field_id' => $data['stock_id'],
                'activity' => 'activity_assign_stock',
                'value1' => $total_stock->item_name
            );
            $this->stock_model->_table_name = 'tbl_activities';
            $this->stock_model->_primary_key = 'activities_id';
            $this->stock_model->save($activity);

            $type = "success";
            $message = lang('assign_success');
        }
        set_message($type, $message);
        redirect('admin/stock/assign_stock');
    }

    public function assign_stock_report($emp_id = NULL)
    {
        $data['title'] = lang('assign_stock_history');

        // input data 
        $flag = $this->input->post('flag', TRUE);
        if (!empty($flag) || !empty($emp_id)) {
            $data['flag'] = 1;
            if (!empty($emp_id)) {
                $user_id = $emp_id;
            } else {
                $user_id = $this->input->post('user_id', TRUE);
            }
            // get employee info by id
            $data['employee_info'] = $this->stock_model->check_by(array('user_id' => $user_id), 'tbl_account_details');

            // assign stock list by employee id
            $assign_stock_list = $this->stock_model->get_assign_stock_list($user_id);

            foreach ($assign_stock_list as $v_assign_stock) {
                $data['assign_list'][$v_assign_stock->stock_sub_category] = $this->stock_model->get_assign_stock_list($v_assign_stock->user_id, $v_assign_stock->stock_sub_category_id);
            }

        }
        // get all employee
        $data['all_employee'] = $this->stock_model->get_all_employee();

        $data['subview'] = $this->load->view('admin/stock/assign_stock_report', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function delete_assign_stock($id)
    {
        // get stock id by assign_item_id
        $get_stock_info = $this->stock_model->check_by(array('assign_item_id' => $id), 'tbl_assign_item');

        $total_stock = $this->stock_model->check_by(array('stock_id' => $get_stock_info->stock_id), 'tbl_stock');

        // get return inventory by id
        $this->stock_model->return_inevntory($get_stock_info->stock_id, $get_stock_info->assign_inventory);

        $this->stock_model->_table_name = 'tbl_assign_item';
        $this->stock_model->_primary_key = 'assign_item_id';
        $this->stock_model->delete($id);

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'stock',
            'module_field_id' => $id,
            'activity' => 'activity_delete_assign_stock',
            'value1' => $total_stock->item_name
        );
        $this->stock_model->_table_name = 'tbl_activities';
        $this->stock_model->_primary_key = 'activities_id';
        $this->stock_model->save($activity);

        $type = "success";
        $message = lang('assign_stock_deleted');
        set_message($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function assign_stock_pdf($user_id)
    {

        // get employee info by id
        $data['employee'] = $this->stock_model->check_by(array('user_id' => $user_id), 'tbl_account_details');
        // assign stock list by employee id
        $assign_stock_list = $this->stock_model->get_assign_stock_list($user_id);
        foreach ($assign_stock_list as $v_assign_stock) {
            $data['assign_list'][$v_assign_stock->stock_sub_category] = $this->stock_model->get_assign_stock_list($v_assign_stock->user_id, $v_assign_stock->stock_sub_category_id);
        }
        $this->load->helper('dompdf');
        $viewfile = $this->load->view('admin/stock/assign_stock_pdf', $data, TRUE);
        pdf_create($viewfile, lang('assign_stock_list_for') . ' -' . $data['employee']->fullname . '(' . $data['employee']->employment_id . ')');
    }

    public function report()
    {

        $data['title'] = lang('assign_stock') . ' ' . lang('report');
        
        $flag = $this->input->post('flag', TRUE);
        if (!empty($flag)) {
            $date = $this->stock_model->array_from_post(array('start_date', 'end_date')); //input post                     
            $data['date'] = $date;
            $get_assign_data = $this->stock_model->get_assign_data_by_date($date);
            if (!empty($get_assign_data)) {
                foreach ($get_assign_data as $value) {
                    $data['assign_report'][$value->stock_sub_category . ' > ' . $value->item_name] = $this->stock_model->get_assign_data_by_date($date, $value->stock_id);
                }
            }
        }
        $data['subview'] = $this->load->view('admin/stock/stock_report', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function assign_report_pdf($start_date, $end_date)
    {
        $data['title'] = "Stock Report";
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $get_assign_data = $this->stock_model->get_assign_data_by_date($data);
        foreach ($get_assign_data as $value) {
            $data['assign_report'][$value->stock_sub_category . ' > ' . $value->item_name] = $this->stock_model->get_assign_data_by_date($data, $value->stock_id);
        }
        $this->load->helper('dompdf');
        $viewfile = $this->load->view('admin/stock/stock_report_pdf', $data, TRUE);
        pdf_create($viewfile, lang('assign_stock') . ' ' . lang('report') . strftime(config_item('date_format'), strtotime($start_date)) . ' To ' . strftime(config_item('date_format'), strtotime($end_date)));
    }

}
