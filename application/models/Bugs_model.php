<?php

class Bugs_Model extends MY_Model {

    public $_table_name;
    public $_order_by;
    public $_primary_key;

    function task_spent_time_by_id($task_id) {
        $total_time = "SELECT start_time,end_time,end_time - start_time time_spent 
						FROM tbl_tasks_timer WHERE task_id = '$task_id'";
        $result = $this->db->query($total_time)->result();
        $time_spent = array();
        foreach ($result as $time) {
            $time_spent[] = $time->time_spent;
        }
        if (is_array($time_spent)) {
            return array_sum($time_spent);
        } else {
            return 0;
        }
    }

    function get_time_spent_result($seconds) {
        $minutes = $seconds / 60;
        $hours = $minutes / 60;
        if ($minutes >= 60) {
            return round($hours, 2) . ' ' . lang('hours');
        } elseif ($seconds > 60) {
            return round($minutes, 2) . ' ' . lang('minutes');
        } else {
            return $seconds . ' ' . lang('seconds');
        }
    }

}
