<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Global_Controller extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('global_model');
        $this->load->model('admin_model');
    }

    public function get_project_by_client_id($client_id)
    {
        $HTML = null;
        $client_project_info = $this->db->where(array('client_id' => $client_id))->get('tbl_project')->result();
        if (!empty($client_project_info)) {
            $HTML .= "<option value='" . 0 . "'>" . lang('none') . "</option>";
            foreach ($client_project_info as $v_client_project) {
                $HTML .= "<option value='" . $v_client_project->project_id . "'>" . $v_client_project->project_name . "</option>";
            }
        }
        echo $HTML;
    }

    public function get_milestone_by_project_id($project_id)
    {
        $milestone_info = $this->db->where(array('project_id' => $project_id))->get('tbl_milestones')->result();
        $HTML = null;
        if (!empty($milestone_info)) {
            foreach ($milestone_info as $v_milestone) {
                $HTML .= "<option value='" . $v_milestone->milestones_id . "'>" . $v_milestone->milestone_name . "</option>";
            }
        }
        echo $HTML;
    }

    public function get_related_moduleName_by_value($val)
    {
        if ($val == 'project') {
            $all_project_info = $this->admin_model->get_permission('tbl_project');
            $HTML = null;
            if ($all_project_info) {
                $HTML .= '<div class="col-sm-5"><select onchange="get_milestone_by_id(this.value)" name="' . $val . '_id" id="related_to"  class="form-control select_box" >';
                foreach ($all_project_info as $v_project) {
                    $HTML .= "<option value='" . $v_project->project_id . "'>" . $v_project->project_name . "</option>";
                }
                $HTML .= '</select></div>';

            }
            echo $HTML;
        } elseif ($val == 'opportunities') {
            $HTML = null;
            $all_opp_info = $this->admin_model->get_permission('tbl_opportunities');
            if ($all_opp_info) {

                $HTML .= '<div class="col-sm-5"><select name="' . $val . '_id" id="related_to"  class="form-control select_box">';
                foreach ($all_opp_info as $v_opp) {
                    $HTML .= "<option value='" . $v_opp->opportunities_id . "'>" . $v_opp->opportunity_name . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
        } elseif ($val == 'leads') {
            $all_leads_info = $this->admin_model->get_permission('tbl_leads');
            $HTML = null;
            if ($all_leads_info) {

                $HTML .= '<div class="col-sm-5"><select name="' . $val . '_id" id="related_to"  class="form-control select_box">';
                foreach ($all_leads_info as $v_leads) {
                    $HTML .= "<option value='" . $v_leads->leads_id . "'>" . $v_leads->lead_name . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
        } elseif ($val == 'bug') {
            $all_bugs_info = $this->admin_model->get_permission('tbl_bug');
            $HTML = null;
            if ($all_bugs_info) {

                $HTML .= '<div class="col-sm-5"><select name="' . $val . '_id" id="related_to"  class="form-control select_box">';
                foreach ($all_bugs_info as $v_bugs) {
                    $HTML .= "<option value='" . $v_bugs->bug_id . "'>" . $v_bugs->bug_title . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
        } elseif ($val == 'goal') {
            $all_goal_info = $this->admin_model->get_permission('tbl_goal_tracking');
            $HTML = null;
            if ($all_goal_info) {

                $HTML .= '<div class="col-sm-5"><select name="' . $val . '_tracking_id" id="related_to"  class="form-control select_box">';
                foreach ($all_goal_info as $v_goal) {
                    $HTML .= "<option value='" . $v_goal->goal_tracking_id . "'>" . $v_goal->subject . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
        }
    }

    public function check_current_password($val)
    {
        $password = $this->hash($val);
        $check_dupliaction_id = $this->admin_model->check_by(array('password' => $password), 'tbl_users');

        if (empty($check_dupliaction_id)) {
            $result = '<small style="padding-left:10px;color:red;font-size:10px">' . lang("password_does_not_match") . '<small>';
        } else {
            $result = NULL;
        }
        echo $result;
    }

    public function check_match_password($new, $confirm)
    {
        if ($new != $confirm) {
            $result = '<small style="padding-left:10px;color:red;font-size:10px">' . lang("password_does_not_match") . '<small>';
        } else {
            $result = NULL;
        }
        echo $result;
    }

    public function check_duplicate_emp_id($val)
    {
        if (!empty($val)) {
            $check_dupliaction_id = $this->admin_model->check_by(array('employment_id' => $val), 'tbl_account_details');
            if (!empty($check_dupliaction_id)) {
                $result = '<strong style="padding-left:10px;color:red;">' . lang("employee_id_exist") . '<strong>';
            } else {
                $result = NULL;
            }
            echo $result;
        }
    }

    public function check_existing_user_name($user_name, $user_id = null)
    {
        $check_user_name = $this->admin_model->check_user_name($user_name, $user_id);
        if (!empty($check_user_name)) {
            $result = '<strong style="padding-left:10px;color:red;">' . lang("name_already_exist") . '<strong>';
        } else {
            $result = NULL;
        }
        echo $result;
    }

    public function get_item_name_by_id($stock_sub_category_id)
    {
        $HTML = NULL;
        $this->admin_model->_table_name = 'tbl_stock';
        $this->admin_model->_order_by = 'stock_sub_category_id';
        $stock_info = $this->admin_model->get_by(array('stock_sub_category_id' => $stock_sub_category_id, 'total_stock >=' => '1'), FALSE);
        if (!empty($stock_info)) {
            foreach ($stock_info as $v_stock_info) {
                $HTML .= "<option value='" . $v_stock_info->stock_id . "'>" . $v_stock_info->item_name . "</option>";
            }
        }
        echo $HTML;
    }

    public function check_available_leave($user_id, $start_date = NULL, $end_date = NULL, $leave_category_id = NULL)
    {
        if (!empty($leave_category_id) && !empty($start_date)) {
            $total_leave = $this->global_model->check_by(array('leave_category_id' => $leave_category_id), 'tbl_leave_category');
            $leave_total = $total_leave->leave_quota;

            $token_leave = $this->db->where(array('user_id' => $user_id, 'leave_category_id' => $leave_category_id, 'application_status' => '2'))->get('tbl_leave_application')->result();

            $total_token = 0;
            if (!empty($token_leave)) {
                $ge_days = 0;
                $m_days = 0;
                foreach ($token_leave as $v_leave) {
                    $month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($v_leave->leave_start_date)), date('Y', strtotime($v_leave->leave_start_date)));
                    $datetime1 = new DateTime($v_leave->leave_start_date);

                    $datetime2 = new DateTime($v_leave->leave_end_date);
                    $difference = $datetime1->diff($datetime2);
                    if ($difference->m != 0) {
                        $m_days += $month;
                    } else {
                        $m_days = 0;
                    }
                    $ge_days += $difference->d + 1;
                    $total_token = $m_days + $ge_days;
                }
            }
            if (!empty($total_token)) {
                $total_token = $total_token;
            } else {
                $total_token = 0;
            }
            $input_ge_days = 0;
            $input_m_days = 0;
            if (!empty($start_date)) {
                $input_month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($start_date)), date('Y', strtotime($end_date)));

                $input_datetime1 = new DateTime($start_date);
                $input_datetime2 = new DateTime($end_date);
                $input_difference = $input_datetime1->diff($input_datetime2);
                if ($input_difference->m != 0) {
                    $input_m_days += $input_month;
                } else {
                    $input_m_days = 0;
                }
                $input_ge_days += $input_difference->d + 1;
                $input_total_token = $input_m_days + $input_ge_days;
            }
            $taken_with_input = $total_token + $input_total_token;
            $left_leave = $leave_total - $total_token;
            if ($leave_total < $taken_with_input) {
                echo "You already took  $total_token $total_leave->leave_category You can apply maximum for $left_leave more";
            }
        } else {
            echo lang('all_required_fill');
        }
    }

    public function get_employee_by_designations_id($designation_id)
    {
        $HTML = NULL;
        $this->admin_model->_table_name = 'tbl_account_details';
        $this->admin_model->_order_by = 'designations_id';
        $employee_info = $this->admin_model->get_by(array('designations_id' => $designation_id), FALSE);
        if (!empty($employee_info)) {
            foreach ($employee_info as $v_employee_info) {
                $HTML .= "<option value='" . $v_employee_info->user_id . "'>" . $v_employee_info->fullname . "</option>";
            }
        }
        echo $HTML;
    }

    public function check_advance_amount($amount, $user_id = null)
    {
        $result = $this->global_model->get_advance_amount($user_id);
        if (!empty($result)) {
            if ($result < $amount) {
                echo lang('exced_basic_salary');
            } else {
                echo null;
            }
        } else {
            echo lang('you_can_not_apply');
        }
    }

    public function hash($string)
    {
        return hash('sha512', $string . config_item('encryption_key'));
    }

}
