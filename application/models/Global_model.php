<?php

class Global_Model extends MY_Model
{
    public $_table_name;
    public $_order_by;
    public $_primary_key;

    public function get_public_holidays($yymm)
    {
        $this->db->select('tbl_holiday.*', FALSE);
        $this->db->from('tbl_holiday');
        $this->db->like('start_date', $yymm);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function get_holidays()
    {
        $this->db->select('tbl_working_days.day_id,tbl_working_days.flag', FALSE);
        $this->db->select('tbl_days.day', FALSE);
        $this->db->from('tbl_working_days');
        $this->db->join('tbl_days', 'tbl_days.day_id = tbl_working_days.day_id', 'left');
        $this->db->where('flag', 0);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function select_user_roll($designations_id)
    {
        $this->db->select('tbl_user_role.*', FALSE);
        $this->db->select('tbl_menu.link, tbl_menu.label', FALSE);
        $this->db->from('tbl_user_role');
        $this->db->join('tbl_menu', 'tbl_user_role.menu_id = tbl_menu.menu_id', 'left');
        $this->db->where('tbl_user_role.designations_id', $designations_id);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function check_uri($uri)
    {
        $this->db->select('tbl_user_role.*', FALSE);
        $this->db->select('tbl_menu.link, tbl_menu.label', FALSE);
        $this->db->from('tbl_user_role');
        $this->db->join('tbl_menu', 'tbl_user_role.menu_id = tbl_menu.menu_id', 'left');
        $this->db->where('tbl_user_role.designations_id', $this->session->userdata('designations_id'));
        $this->db->where('tbl_menu.link', $uri);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function get_holiday_list_by_date($start_date, $end_date)
    {
        $this->db->select('tbl_holiday.*', FALSE);
        $this->db->from('tbl_holiday');
        $this->db->where('start_date >=', $start_date);
        $this->db->where('end_date <=', $end_date);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function get_advance_amount($user_id)
    {
        $emp_payroll = $this->db->where('user_id', $user_id)->get('tbl_employee_payroll')->row();
        if (!empty($emp_payroll)) {
            if (!empty($emp_payroll->salary_template_id)) {
                $emp_salary = $this->db->where('salary_template_id', $emp_payroll->salary_template_id)->get('tbl_salary_template')->row();
                $basic_salary = $emp_salary->basic_salary;
            }
            if (!empty($emp_payroll->hourly_rate_id)) {
                $hourly_salary = $this->db->where('hourly_rate_id', $emp_payroll->hourly_rate_id)->get('tbl_hourly_rate')->row();
                $basic_salary = $hourly_salary->hourly_rate * 10;
            }
        }
        if (!empty($basic_salary)) {
            return $basic_salary;
        } else {
            return null;
        }
    }

    public function get_total_attendace_by_date($start_date, $end_date, $user_id, $flag = null)
    {
        $this->db->select('tbl_attendance.*', FALSE);
        $this->db->from('tbl_attendance');
        $this->db->where('user_id', $user_id);
        $this->db->where('date_in >=', $start_date);
        $this->db->where('date_in <=', $end_date);
        if (!empty($flag) && $flag == 'absent') {
            $this->db->where('attendance_status', '0');
        } elseif (!empty($flag) && $flag == 'leave') {
            $this->db->where('attendance_status', '3');
        } else {
            $this->db->where('attendance_status', '1');
        }

        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function get_total_working_hours($id)
    {
        $total_hh = 0;
        $total_mm = 0;

        $clock_time = $this->get_attendance_info($id);

        foreach ($clock_time as $mytime) {
            if (!empty($mytime)) {
                // calculate the start timestamp
                $startdatetime = strtotime($mytime->date_in . " " . $mytime->clockin_time);
                // calculate the end timestamp
                $enddatetime = strtotime($mytime->date_out . " " . $mytime->clockout_time);
                // calulate the difference in seconds
                $difference = $enddatetime - $startdatetime;
                // hours is the whole number of the division between seconds and SECONDS_PER_HOUR
                $hoursDiff = $difference / SECONDS_PER_HOUR;
                $total_hh += round($hoursDiff);
                // and the minutes is the remainder
                $minutesDiffRemainder = $difference % SECONDS_PER_HOUR / 60;
                $total_mm += round($minutesDiffRemainder) % 60;

                // output the result
                $total['minute'] = round($total_mm);
                $total['hour'] = round($total_hh);

            }
        }
        if (!empty($total)) {
            $total = $total;
        } else {
            $total['minute'] = 0;
            $total['hour'] = 0;
        }
        return $total;
    }

    public function get_attendance_info($id)
    {

        $this->db->select('tbl_clock.*', FALSE);
        $this->db->select('tbl_attendance.*', FALSE);
        $this->db->from('tbl_clock');
        $this->db->join('tbl_attendance', 'tbl_attendance.attendance_id = tbl_clock.attendance_id', 'left');
        $this->db->where('tbl_clock.attendance_id', $id);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

}
