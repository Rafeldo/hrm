<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class report extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('report_model');
        $this->load->model('invoice_model');
        $this->load->model('estimates_model');

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

    public function account_statement()
    {
        $data['title'] = lang('account_statement');
        $data['account_id'] = $this->input->post('account_id', TRUE);
        if (!empty($data['account_id'])) {
            $data['report'] = TRUE;
            $data['start_date'] = $this->input->post('start_date', TRUE);
            $data['end_date'] = $this->input->post('end_date', TRUE);
            $data['transaction_type'] = $this->input->post('transaction_type', TRUE);
            $data['all_transaction_info'] = $this->get_account_statement($data['account_id'], $data['start_date'], $data['end_date'], $data['transaction_type']);
        }
        $data['subview'] = $this->load->view('admin/report/account_statement', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    function get_account_statement($account_id, $start_date, $end_date, $transaction_type)
    {
        if ($transaction_type == 'all_transactions') {
            $where = array('account_id' => $account_id, 'date >=' => $start_date, 'date <=' => $end_date);
        } elseif ($transaction_type == 'debit') {
            $where = array('account_id' => $account_id, 'date >=' => $start_date, 'date <=' => $end_date, 'credit' => $transaction_type);
        } else {
            $where = array('account_id' => $account_id, 'date >=' => $start_date, 'date <=' => $end_date, 'debit' => $transaction_type);
        }
        $this->report_model->_table_name = "tbl_transactions"; //table name
        $this->report_model->_order_by = "transactions_id";
        return $this->report_model->get_by($where, FALSE);
    }

    public function account_statement_pdf($account_id, $start_date, $end_date, $transaction_type)
    {

        $data['all_transaction_info'] = $this->get_account_statement($account_id, $start_date, $end_date, $transaction_type);
        $data['title'] = lang('account_statement');
        $this->load->helper('dompdf');
        $viewfile = $this->load->view('admin/report/account_statement_pdf', $data, TRUE);
        pdf_create($viewfile, lang('account_statement') . ' From:' . $start_date . ' To:', $end_date);
    }

    public function income_report()
    {
        $data['title'] = lang('income_report');
        $data['transactions_report'] = $this->get_transactions_report();
        $data['subview'] = $this->load->view('admin/report/income_report', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function income_report_pdf()
    {
        $data['title'] = lang('income_report');
        $this->load->helper('dompdf');
        $viewfile = $this->load->view('admin/report/income_report_pdf', $data, TRUE);
        pdf_create($viewfile, lang('income_report'));
    }

    public function get_transactions_report()
    {// this function is to create get monthy recap report
        $m = date('n');
        $year = date('Y');
        $num = cal_days_in_month(CAL_GREGORIAN, $m, $year);
        for ($i = 1; $i <= $num; $i++) {
            if ($m >= 1 && $m <= 9) { // if i<=9 concate with Mysql.becuase on Mysql query fast in two digit like 01.
                $date = $year . "-" . '0' . $m;
            } else {
                $date = $year . "-" . $m;
            }
            $date = $date . '-' . $i;
            $transaction_report[$i] = $this->db->where('date', $date)->order_by('transactions_id', 'DESC')->get('tbl_transactions')->result();
        }
        return $transaction_report; // return the result
    }

    public function expense_report()
    {
        $data['title'] = lang('expense_report');
        $data['transactions_report'] = $this->get_transactions_report();
        $data['subview'] = $this->load->view('admin/report/expense_report', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function expense_report_pdf()
    {
        $data['title'] = lang('expense_report');
        $this->load->helper('dompdf');
        $viewfile = $this->load->view('admin/report/expense_report_pdf', $data, TRUE);
        pdf_create($viewfile, lang('expense_report'));
    }

    public function income_expense()
    {
        $data['title'] = lang('income_expense');
        $data['transactions_report'] = $this->get_transactions_report();
        $data['subview'] = $this->load->view('admin/report/income_expense', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function income_expense_pdf()
    {
        $data['title'] = lang('income_expense');
        $this->load->helper('dompdf');
        $viewfile = $this->load->view('admin/report/income_expense_pdf', $data, TRUE);
        pdf_create($viewfile, lang('income_expense'));
    }

    public function date_wise_report()
    {
        $data['title'] = lang('date_wise_report');
        $data['start_date'] = $this->input->post('start_date', TRUE);
        $data['end_date'] = $this->input->post('end_date', TRUE);
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            $data['report'] = TRUE;
            $data['all_transaction_info'] = $this->db->where('date >=', $data['start_date'], 'date >=', $data['end_date'])->get('tbl_transactions')->result();
        }
        $data['subview'] = $this->load->view('admin/report/date_wise_report', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function date_wise_report_pdf($start_date, $end_date)
    {
        $data['title'] = lang('date_wise_report');
        $this->load->helper('dompdf');
        $data['all_transaction_info'] = $this->db->where('date >=', $start_date, 'date <=', $end_date)->get('tbl_transactions')->result();
        $viewfile = $this->load->view('admin/report/date_wise_report_pdf', $data, TRUE);
        pdf_create($viewfile, lang('date_wise_report'));
    }

    public function report_by_month()
    {
        $data['title'] = lang('report_by_month');
        $data['current_month'] = date('m');

        if ($this->input->post('year', TRUE)) { // if input year 
            $data['year'] = $this->input->post('year', TRUE);
        } else { // else current year
            $data['year'] = date('Y'); // get current year
        }
        // get all expense list by year and month
        $data['report_by_month'] = $this->get_report_by_month($data['year']);

        $data['subview'] = $this->load->view('admin/report/report_by_month', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function get_report_by_month($year, $month = NULL)
    {// this function is to create get monthy recap report
        if (!empty($month)) {
            if ($month >= 1 && $month <= 9) { // if i<=9 concate with Mysql.becuase on Mysql query fast in two digit like 01.
                $start_date = $year . "-" . '0' . $month . '-' . '01';
                $end_date = $year . "-" . '0' . $month . '-' . '31';
            } else {
                $start_date = $year . "-" . $month . '-' . '01';
                $end_date = $year . "-" . $month . '-' . '31';
            }
            $get_expense_list = $this->report_model->get_report_by_date($start_date, $end_date); // get all report by start date and in date 
        } else {
            for ($i = 1; $i <= 12; $i++) { // query for months
                if ($i >= 1 && $i <= 9) { // if i<=9 concate with Mysql.becuase on Mysql query fast in two digit like 01.
                    $start_date = $year . "-" . '0' . $i . '-' . '01';
                    $end_date = $year . "-" . '0' . $i . '-' . '31';
                } else {
                    $start_date = $year . "-" . $i . '-' . '01';
                    $end_date = $year . "-" . $i . '-' . '31';
                }
                $get_expense_list[$i] = $this->report_model->get_report_by_date($start_date, $end_date); // get all report by start date and in date 
            }
        }
        return $get_expense_list; // return the result
    }

    public function report_by_month_pdf($year, $month)
    {
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'report/report_by_month',
            'module_field_id' => $year,
            'activity' => lang('activity_report_by_month_pdf'),
            'icon' => 'fa-laptop',
            'value1' => $year,
            'value2' => $month
        );
        $this->report_model->_table_name = 'tbl_activities';
        $this->report_model->_primary_key = 'activities_id';
        $this->report_model->save($activity);

        $data['report_list'] = $this->get_report_by_month($year, $month);
        $month_name = date('F', strtotime($year . '-' . $month)); // get full name of month by date query                
        $data['monthyaer'] = $month_name . '  ' . $year;
        $this->load->helper('dompdf');
        $viewfile = $this->load->view('admin/report/report_by_month_pdf', $data, TRUE);
        pdf_create($viewfile, lang('report_by_month') . '- ' . $data['monthyaer']);
    }

    public function all_income()
    {
        $data['title'] = lang('all_income');
        $data['subview'] = $this->load->view('admin/report/all_income', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function all_expense()
    {
        $data['title'] = lang('all_expense');
        $data['subview'] = $this->load->view('admin/report/all_expense', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function all_transaction()
    {
        $data['title'] = lang('all_transaction');
        $data['transactions_report'] = $this->get_transactions_report();
        $data['subview'] = $this->load->view('admin/report/all_transaction', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }



    function get_tasks_by_user($assign_user, $tasks = null)
    {
        $tasks_info = $this->report_model->get_permission('tbl_task');
        if (!empty($tasks_info)):foreach ($tasks_info as $v_tasks):
            if (!empty($tasks)) {
                if ($v_tasks->permission == 'all') {
                    $permission[$v_tasks->permission][$v_tasks->task_status][] = $v_tasks->task_status;
                } else {
                    $get_permission = json_decode($v_tasks->permission);
                    if (!empty($get_permission)) {
                        foreach ($get_permission as $id => $v_permission) {
                            if (!empty($assign_user)) {
                                foreach ($assign_user as $v_user) {
                                    if ($v_user->user_id == $id) {
                                        $permission[$v_user->user_id][$v_tasks->task_status][] = $v_tasks->task_status;
                                    }
                                }
                            }

                        }
                    }
                }
            }


        endforeach;
        endif;
        if (empty($permission)) {
            $permission = array();
        }
        return $permission;
    }


    public function get_project_report_by_month($tickets = null)
    {// this function is to create get monthy recap report

        for ($i = 1; $i <= 12; $i++) { // query for months
            if ($i >= 1 && $i <= 9) { // if i<=9 concate with Mysql.becuase on Mysql query fast in two digit like 01.
                $start_date = date('Y') . "-" . '0' . $i . '-' . '01';
                $end_date = date('Y') . "-" . '0' . $i . '-' . '31';
            } else {
                $start_date = date('Y') . "-" . $i . '-' . '01';
                $end_date = date('Y') . "-" . $i . '-' . '31';
            }
            if (!empty($tickets)) {
                $where = array('created >=' => $start_date . ' 00:00:00', 'created <=' => $end_date . ' 23:59:59');
                $get_result[$i] = $this->db->where($where)->get('tbl_tickets')->result();; // get all report by start date and in date
            }
        }

        return $get_result; // return the result
    }
    public function tasks_report()
    {
        $data['title'] = lang('project_report');
        // get permission user by menu id
        $permission_user = $this->report_model->all_permission_user('54');
        // get all admin user
        $admin_user = $this->db->where('role_id', 1)->get('tbl_users')->result();
        // if not exist data show empty array.
        if (!empty($permission_user)) {
            $permission_user = $permission_user;
        } else {
            $permission_user = array();
        }
        if (!empty($admin_user)) {
            $admin_user = $admin_user;
        } else {
            $admin_user = array();
        }
        $data['assign_user'] = array_merge($admin_user, $permission_user);

        $data['all_tasks'] = $this->report_model->get_permission('tbl_task');
        $data['user_tasks'] = $this->get_tasks_by_user($data['assign_user'], true);
        $data['subview'] = $this->load->view('admin/report/tasks_report', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }


    public function tickets_report()
    {
        $data['title'] = lang('tickets_report');
        // get permission user by menu id
        $permission_user = $this->report_model->all_permission_user('7');
        // get all admin user
        $admin_user = $this->db->where('role_id', 1)->get('tbl_users')->result();
        // if not exist data show empty array.
        if (!empty($permission_user)) {
            $permission_user = $permission_user;
        } else {
            $permission_user = array();
        }
        if (!empty($admin_user)) {
            $admin_user = $admin_user;
        } else {
            $admin_user = array();
        }
        $data['assign_user'] = array_merge($admin_user, $permission_user);
        $data['user_tickets'] = $this->get_tickets_by_user($data['assign_user']);

        $data['yearly_report'] = $this->get_project_report_by_month(true);

        $data['subview'] = $this->load->view('admin/report/tickets_report', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    function get_tickets_by_user($assign_user)
    {
        $all_ticktes = $this->report_model->get_permission('tbl_tickets');
        if (!empty($all_ticktes)):foreach ($all_ticktes as $v_ticktes):
            if ($v_ticktes->permission == 'all') {
                $permission[$v_ticktes->permission][$v_ticktes->status][] = $v_ticktes->status;
            } else {
                $get_permission = json_decode($v_ticktes->permission);
                if (!empty($get_permission)) {
                    foreach ($get_permission as $id => $v_permission) {
                        if (!empty($assign_user)) {
                            foreach ($assign_user as $v_user) {
                                if ($v_user->user_id == $id) {
                                    $permission[$v_user->user_id][$v_ticktes->status][] = $v_ticktes->status;
                                }
                            }
                        }

                    }
                }
            }
        endforeach;
        endif;
        if (empty($permission)) {
            $permission = array();
        }

        return $permission;
    }

    public function client_report()
    {
        $data['title'] = lang('client_report');
        $data['all_client_info'] = $this->db->get('tbl_client')->result();

        $data['subview'] = $this->load->view('admin/report/client_report', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }


}
