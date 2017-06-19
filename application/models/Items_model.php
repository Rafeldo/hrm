<?php

/**
 * Description of Project_Model
 *
 * @author NaYeM
 */
class Items_Model extends MY_Model
{

    public $_table_name;
    public $_order_by;
    public $_primary_key;

    function calculate_milestone_progress($milestones_id)
    {
        $all_milestone_tasks = $this->db->where('milestones_id', $milestones_id)->get('tbl_task')->num_rows();
        $complete_milestone_tasks = $this->db->where(
            array('task_progress' => '100',
                'milestones_id' => $milestones_id
            ))->get('tbl_task')->num_rows();
        if ($all_milestone_tasks > 0) {
            return round(($complete_milestone_tasks / $all_milestone_tasks) * 100);
        } else {
            return 0;
        }
    }

    function calculate_project($project_value, $project_id)
    {
        switch ($project_value) {
            case 'project_cost':
                return $this->total_project_cost($project_id);
                break;
            case 'project_hours':
                return $this->total_project_hours($project_id, true);
                break;
        }
    }

    function total_project_cost($project_id)
    {

        $project_hours = $this->total_project_hours($project_id);
        $fix_rate = $this->get_any_field('tbl_project', array('project_id' => $project_id), 'fixed_rate');
        $hourly_rate = $this->get_any_field('tbl_project', array('project_id' => $project_id), 'hourly_rate');
        if ($fix_rate == 'No') {
            return $project_hours * $hourly_rate;
        } else {
            return $this->get_any_field('tbl_project', array('project_id' => $project_id), 'project_cost');
        }
    }

    function total_project_hours($project_id, $second = null)
    {
        $with_tasks = $this->get_any_field('tbl_project', array('project_id' => $project_id), 'with_tasks');
        if ($with_tasks == 'yes') {
            $all_tasks = $this->db->where('project_id', $project_id)->get('tbl_task')->result();
        }
        $task_time = 0;
        if (!empty($all_tasks)) {
            foreach ($all_tasks as $v_tasks) {
                $task_time += $this->task_spent_time_by_id($v_tasks->task_id);
            }
        }
        $project_time = $this->calculate_total_task_time($project_id);
        if (!empty($second)) {
            $logged_time = $task_time + $project_time;
        } else {
            $logged_time = ($task_time + $project_time) / 3600;
        }

        return $logged_time;

    }

    function calculate_total_task_time($project_id)
    {
        $total_time = "SELECT start_time,end_time,project_id,
		end_time - start_time time_spent FROM tbl_tasks_timer WHERE project_id = '$project_id'";
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

    function project_hours($project_id)
    {
        $task_time = $this->get_sum('tbl_tasks', 'logged_time', array('project' => $project_id));
        $project_time = $this->get_sum('tbl_project', 'time_logged', array('project_id' => $project_id));
        $logged_time = ($task_time + $project_time) / 3600;
        return $logged_time;
    }


}
