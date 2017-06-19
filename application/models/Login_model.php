<?php

class Login_Model extends MY_Model
{

    public $_table_name;
    protected $_order_by;
    public $_primary_key;
    public $rules = array(
        'user_name' => array(
            'field' => 'user_name',
            'label' => 'User Name',
            'rules' => 'trim|required|xss_clean'
        ),
        'password' => array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required'
        )
    );

    public function login()
    {
        //check user type
        $this->_table_name = 'tbl_users';
        $this->_order_by = 'user_id';

        $admin = $this->get_by(array(
            'username' => $this->input->post('user_name'),
            'password' => $this->hash($this->input->post('password')),
        ), TRUE);
        if (!empty($admin) && $admin->activated == 1 && $admin->banned == 0) {
            $user_info = $this->check_by(array('user_id' => $admin->user_id), 'tbl_account_details');
            $this->set_action(array('user_id' => $admin->user_id), array('online_status' => '1'), 'tbl_users');

            if ($admin->role_id != '2') {

                $data = array(
                    'user_name' => $admin->username,
                    'email' => $admin->email,
                    'name' => $user_info->fullname,
                    'photo' => $user_info->avatar,
                    'designations_id' => $user_info->designations_id,
                    'user_id' => $admin->user_id,
                    'last_login' => $admin->last_login,
                    'loggedin' => TRUE,
                    'user_type' => $admin->role_id,
                    'user_flag' => 1,
                    'url' => 'admin/dashboard',
                );
                $this->session->set_userdata($data);
            } else {
                $data = array(
                    'user_name' => $admin->username,
                    'email' => $admin->email,
                    'name' => $user_info->fullname,
                    'photo' => $user_info->avatar,
                    'client_id' => $user_info->company,
                    'user_id' => $admin->user_id,
                    'last_login' => $admin->last_login,
                    'loggedin' => TRUE,
                    'user_type' => $admin->role_id,
                    'user_flag' => 2,
                    'url' => 'client/dashboard',
                );
                $this->session->set_userdata($data);
            }
        }
    }

    public function activate_user($user_id, $activation_key, $activate_by_email = TRUE)
    {
        $this->purge_na($this->config->item('email_activation_expire', 'login'));
        if ((strlen($user_id) > 0) AND (strlen($activation_key) > 0)) {
            return $this->activated_user($user_id, $activation_key, $activate_by_email);
        }
        return FALSE;
    }

    function purge_na()
    {
        $expire_period = 172800;
        $this->db->where('activated', 0);
        $this->db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
        $this->db->delete('tbl_users');
    }

    function activated_user($user_id, $activation_key, $activate_by_email)
    {

        $this->db->select('1', FALSE);
        $this->db->where('user_id', $user_id);
        if ($activate_by_email) {
            $this->db->where('new_email_key', $activation_key);
        } else {
            $this->db->where('new_password_key', $activation_key);
        }
        $this->db->where('activated', 0);
        $query = $this->db->get('tbl_users');

        if ($query->num_rows() == 1) {
            $this->db->set('activated', 1);
            $this->db->set('new_email_key', NULL);
            $this->db->where('user_id', $user_id);
            $this->db->update('tbl_users');
            return TRUE;
        }
        return FALSE;
    }

    function get_user_details($login)
    {
        $this->db->where('LOWER(username)=', strtolower($login));
        $this->db->or_where('LOWER(email)=', strtolower($login));

        $query = $this->db->get('tbl_users');
        if ($query->num_rows() == 1)
            return $query->row();
        return NULL;
    }

    function set_password_key($user_id, $new_pass_key)
    {
        $this->db->set('new_password_key', $new_pass_key);
        $this->db->set('new_password_requested', date('Y-m-d H:i:s'));
        $this->db->where('user_id', $user_id);
        $this->db->update('tbl_users');
        return $this->db->affected_rows() > 0;
    }

    function get_user_by_id($user_id, $activated)
    {
        $this->db->where('id', $user_id);
        $this->db->where('activated', $activated ? 1 : 0);

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1)
            return $query->row();
        return NULL;
    }

    function can_reset_password($user_id, $new_pass_key)
    {
        $expire_period = 900;
        $this->db->select('1', FALSE);
        $this->db->where('user_id', $user_id);
        $this->db->where('new_password_key', $new_pass_key);
        $this->db->where('UNIX_TIMESTAMP(new_password_requested) >', time() - $expire_period);
        $query = $this->db->get('tbl_users');
        return $query->num_rows() == 1;
    }

    function get_reset_password($user_id, $new_pass_key)
    {
        $expire_period = 900;
        $this->db->set('password', $this->hash('123456'));
        $this->db->set('new_password_key', NULL);
        $this->db->set('new_password_requested', NULL);
        $this->db->where('user_id', $user_id);
        $this->db->where('new_password_key', $new_pass_key);
        $this->db->where('UNIX_TIMESTAMP(new_password_requested) >=', time() - $expire_period);
        $this->db->update('tbl_users');
        return $this->db->affected_rows() > 0;
    }

    function activate_new_email($user_id, $new_email_key)
    {
        $this->db->set('email', 'new_email', FALSE);
        $this->db->set('new_email', NULL);
        $this->db->set('new_email_key', NULL);
        $this->db->where('user_id', $user_id);
        $this->db->where('new_email_key', $new_email_key);
        $this->db->update('tbl_users');
        return $this->db->affected_rows() > 0;
    }

    public function logout()
    {
        $this->clock_out();
        $this->tasks_timer_stop();
        $this->set_action(array('user_id' => $this->session->userdata('user_id')), array('online_status' => '0', 'last_login' => date('Y-m-d H:i:s')), 'tbl_users');
        $this->session->sess_destroy();
    }

    function clock_out()
    {
        $a_where = array('user_id' => $this->session->userdata('user_id'), 'attendance_status' => '1');
        $all_attendance = $this->db->where($a_where)->get('tbl_attendance')->result();
        if (!empty($all_attendance)) {
            foreach ($all_attendance as $v_attendance) {
                $where = array('attendance_id' => $v_attendance->attendance_id, 'clockout_time' => null);
                $all_clock_out = $this->db->where($where)->get('tbl_clock')->row();
            }
        }
        if (!empty($all_clock_out)) {

            $data['clockout_time'] = $this->input->post('clock_time', TRUE);
            $data['clocking_status'] = 0;

            $this->_table_name = "tbl_clock"; // table name
            $this->_primary_key = "clock_id"; // $id
            $this->save($data, $all_clock_out->clock_id);
        }
        return true;
    }

    function tasks_timer_stop()
    {
        $user_id = $this->session->userdata('user_id');
        $all_task_info = $this->db->where('timer_started_by', $user_id)->get('tbl_task')->result();
        if (!empty($all_task_info)) {
            foreach ($all_task_info as $task_start) {
                $task_logged_time = $this->task_spent_time_by_id($task_start->task_id);
                $time_logged = (time() - $task_start->start_time) + $task_logged_time; //time already logged

                $data = array(
                    'timer_status' => 'off',
                    'logged_time' => $time_logged,
                    'start_time' => ''
                );
                // Update into tbl_task
                $this->_table_name = "tbl_task"; //table name
                $this->_primary_key = "task_id";
                $this->save($data, $task_start->task_id);
                // save into tbl_task_timer
                $t_data = array(
                    'task_id' => $task_start->task_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'start_time' => $task_start->start_time,
                    'end_time' => time()
                );
                // insert into tbl_task_timer
                $this->_table_name = "tbl_tasks_timer"; //table name
                $this->_primary_key = "tasks_timer_id";
                $this->save($t_data);
                return true;
            }
        }
    }



    public function loggedin()
    {
        return (bool)$this->session->userdata('loggedin');
    }

    public function hash($string)
    {
        return hash('sha512', $string . config_item('encryption_key'));
    }

}
