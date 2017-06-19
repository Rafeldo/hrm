<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admistrator
 *
 * @author pc mart ltd
 */
class Dashboard extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->model('invoice_model');
        $this->load->model('estimates_model');
    }

    public function index($action = NULL)
    {
        $data['title'] = config_item('company_name');
        $data['page'] = lang('dashboard');

        //total expense count
        $this->admin_model->_table_name = "tbl_transactions"; //table name
        $this->admin_model->_order_by = "transactions_id"; // order by 
        $total_income_expense = $this->admin_model->get(); // get result
        $today_income_expense = $this->admin_model->get_by(array('date' => date('Y-m-d'))); // get result

        $today_income = 0;
        $today_expense = 0;
        foreach ($today_income_expense as $t_income_expense) {
            if ($t_income_expense->type == 'Income') {
                $today_income += $t_income_expense->amount;

            } elseif ($t_income_expense->type == 'Expense') {
                $today_expense += $t_income_expense->amount;
            }
        }
        $data['today_income'] = $today_income;

        $data['today_expense'] = $today_expense;

        $total_income = 0;
        $total_expense = 0;
        foreach ($total_income_expense as $v_income_expense) {
            if ($v_income_expense->type == 'Income') {
                $total_income += $v_income_expense->amount;

            } elseif ($v_income_expense->type == 'Expense') {
                $total_expense += $v_income_expense->amount;
            }
        }
        $data['total_income'] = $total_income;

        $data['total_expense'] = $total_expense;

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->admin_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $data['invoce_total'] = $this->invoice_totals_per_currency();
        if (!empty($action) && $action == 'payments') {
            $data['yearly'] = $this->input->post('yearly', TRUE);
        } else {
            $data['yearly'] = date('Y'); // get current year
        }
        if (!empty($action) && $action == 'Income') {
            $data['Income'] = $this->input->post('Income', TRUE);
        } else {
            $data['Income'] = date('Y'); // get current year
        }
        if ($this->input->post('year', TRUE)) { // if input year 
            $data['year'] = $this->input->post('year', TRUE);
        } else { // else current year
            $data['year'] = date('Y'); // get current year
        }
        // get all expense list by year and month
        $data['all_income'] = $this->get_transactions_list($data['Income'], 'Income');

        $data['all_expense'] = $this->get_transactions_list($data['year'], 'Expense');

        $data['yearly_overview'] = $this->get_yearly_overview($data['yearly']);

        if ($this->input->post('month', TRUE)) { // if input year 
            $data['month'] = $this->input->post('month', TRUE);
        } else { // else current year
            $data['month'] = date('Y-m'); // get current year
        }
        $data['income_expense'] = $this->get_income_expense($data['month']);

        $data['subview'] = $this->load->view('admin/main_content', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }


    function invoice_totals_per_currency()
    {
        $invoices_info = $this->db->where('inv_deleted', 'No')->get('tbl_invoices')->result();
        $paid = $due = array();
        $currency = 'USD';
        $symbol = array();
        foreach ($invoices_info as $v_invoices) {
            if (!isset($paid[$v_invoices->currency])) {
                $paid[$v_invoices->currency] = 0;
            }
            if (!isset($due[$v_invoices->currency])) {
                $due[$v_invoices->currency] = 0;
            }
            $paid[$v_invoices->currency] += $this->invoice_model->get_invoice_paid_amount($v_invoices->invoices_id);
            $due[$v_invoices->currency] += $this->invoice_model->get_invoice_due_amount($v_invoices->invoices_id);
            $currency = $this->admin_model->check_by(array('code' => $v_invoices->currency), 'tbl_currencies');
            $symbol[$v_invoices->currency] = $currency->symbol;
        }
        return array("paid" => $paid, "due" => $due, "symbol" => $symbol);
    }

    public function get_yearly_overview($year)
    {// this function is to create get monthy recap report
        for ($i = 1; $i <= 12; $i++) { // query for months
            if ($i >= 1 && $i <= 9) { // if i<=9 concate with Mysql.becuase on Mysql query fast in two digit like 01.
                $month = '0' . $i;
            } else {
                $month = $i;
            }
            $yearly_report[$i] = $this->admin_model->calculate_amount($year, $month); // get all report by start date and in date 
        }
        return $yearly_report; // return the result
    }

    public function get_income_expense($month)
    {// this function is to create get monthy recap report
        //m = date('m', strtotime($month));
        $m = date('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if ($m >= 1 && $m <= 9) { // if i<=9 concate with Mysql.becuase on Mysql query fast in two digit like 01.
            $start_date = $year . "-" . '0' . $m . '-' . '01';
            $end_date = $year . "-" . '0' . $m . '-' . '31';
        } else {
            $start_date = $year . "-" . $m . '-' . '01';
            $end_date = $year . "-" . $m . '-' . '31';
        }
        $get_expense_list = $this->admin_model->get_transactions_list_by_month($start_date, $end_date); // get all report by start date and in date 

        return $get_expense_list; // return the result
    }

    public function get_transactions_list($year, $type)
    {// this function is to create get monthy recap report
        for ($i = 1; $i <= 12; $i++) { // query for months
            if ($i >= 1 && $i <= 9) { // if i<=9 concate with Mysql.becuase on Mysql query fast in two digit like 01.
                $start_date = $year . "-" . '0' . $i . '-' . '01';
                $end_date = $year . "-" . '0' . $i . '-' . '31';
            } else {
                $start_date = $year . "-" . $i . '-' . '01';
                $end_date = $year . "-" . $i . '-' . '31';
            }
            $get_expense_list[$i] = $this->admin_model->get_transactions_list_by_date($type, $start_date, $end_date); // get all report by start date and in date
        }
        return $get_expense_list; // return the result
    }

    public function set_language($lang)
    {
        $this->session->set_userdata('lang', $lang);
        redirect($_SERVER["HTTP_REFERER"]);
    }
    public function set_clocking($id = NULL) {

// sate into attendance table
        $adata['user_id'] = $this->session->userdata('user_id');
        $clocktime = $this->input->post('clocktime', TRUE);

        if ($clocktime == 1) {
            $adata['date_in'] = $this->input->post('clock_date', TRUE);
        } else {
            $adata['date_out'] = $this->input->post('clock_date', TRUE);
        }
        if (!empty($adata['date_in'])) {
            // chck existing date is here or not
            $check_date = $this->admin_model->check_by(array('user_id' => $adata['user_id'], 'date_in' => $adata['date_in']), 'tbl_attendance');
        }
        if (!empty($check_date)) { // if exis do not save date and return id
            if ($check_date->attendance_status != '1') {
                $udata['attendance_status'] = 1;
                $this->admin_model->_table_name = "tbl_attendance"; // table name
                $this->admin_model->_primary_key = "attendance_id"; // $id
                $this->admin_model->save($udata, $check_date->attendance_id);
            }
            $data['attendance_id'] = $check_date->attendance_id;

        } else { // else save data into tbl attendance
            // get attendance id by clock id into tbl clock
            // if attendance id exist that means he/she clock in
            // return the id
            // and update the day out time
            $check_existing_data = $this->admin_model->check_by(array('clock_id' => $id), 'tbl_clock');

            $this->admin_model->_table_name = "tbl_attendance"; // table name
            $this->admin_model->_primary_key = "attendance_id"; // $id
            if (!empty($check_existing_data)) {
                $this->admin_model->save($adata, $check_existing_data->attendance_id);
            } else {
                $adata['attendance_status'] = 1;
                //save data into attendance table
                $data['attendance_id'] = $this->admin_model->save($adata);
            }
        }
        // save data into clock table
        if ($clocktime == 1) {
            $data['clockin_time'] = $this->input->post('clock_time', TRUE);
        } else {
            $data['clockout_time'] = $this->input->post('clock_time', TRUE);

            $data['comments'] = $this->input->post('comments', TRUE);
        }

        //save data in database
        $this->admin_model->_table_name = "tbl_clock"; // table name
        $this->admin_model->_primary_key = "clock_id"; // $id
        if (!empty($id)) {
            $data['clocking_status'] = 0;
            $this->admin_model->save($data, $id);
        } else {
            $data['clocking_status'] = 1;
            $this->admin_model->save($data);
        }
        redirect($_SERVER["HTTP_REFERER"]);
    }

}
