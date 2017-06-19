<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * Main Base Model MY_Model
 * Author: Nayeem 
 */

class MY_Model extends CI_Model
{

    protected $_table_name = '';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = '';
    public $rules = array();
    protected $_timestamps = FALSE;

    function __construct()
    {
        parent::__construct();
    }

    // CURD FUNCTION

    public function array_from_post($fields)
    {

        $data = array();
        foreach ($fields as $field) {
            $data[$field] = $this->input->post($field);
        }
        return $data;
    }

    public function get($id = NULL, $single = FALSE)
    {

        if ($id != NULL) {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->where($this->_primary_key, $id);
            $method = 'row';
        } elseif ($single == TRUE) {
            $method = 'row';
        } else {
            $method = 'result';
        }

        if (!count($this->db->ar_orderby)) {
            $this->db->order_by($this->_order_by);
        }
        return $this->db->get($this->_table_name)->$method();
    }

    public function get_by($where, $single = FALSE)
    {
        $this->db->where($where);
        return $this->get(NULL, $single);
    }

    public function save($data, $id = NULL)
    {


        // Set timestamps
        if ($this->_timestamps == TRUE) {
            $now = date('Y-m-d H:i:s');
            $id || $data['created'] = $now;
            $data['modified'] = $now;
        }

        // Insert
        if ($id === NULL) {
            !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $id = $this->db->insert_id();
        } // Update
        else {

            $filter = $this->_primary_filter;
            $id = $filter($id);

            $this->db->set($data);
            $this->db->where($this->_primary_key, $id);
            $this->db->update($this->_table_name);
        }

        return $id;
    }

    public function delete($id)
    {
        $filter = $this->_primary_filter;
        $id = $filter($id);

        if (!$id) {
            return FALSE;
        }
        $this->db->where($this->_primary_key, $id);
        $this->db->limit(1);
        $this->db->delete($this->_table_name);
    }

    /**
     * Delete Multiple rows
     */
    public function delete_multiple($where)
    {
        $this->db->where($where);
        $this->db->delete($this->_table_name);
    }

    function uploadImage($field)
    {

        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = '20240000245';
        $config['overwrite'] = TRUE;
//        $config['max_width'] = '1024';
//        $config['max_height'] = '768';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "error";
            $message = $error;
            set_message($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $fdata = $this->upload->data();
            $img_data ['path'] = $config['upload_path'] . $fdata['file_name'];
            return $img_data;
            // uploading successfull, now do your further actions
        }
    }

    function uploadFile($field)
    {
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = 'pdf|docx|doc';
        $config['max_size'] = '20240000245';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "error";
            $message = $error;
            set_message($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $fdata = $this->upload->data();
            $file_data ['fileName'] = $fdata['file_name'];
            $file_data ['path'] = $config['upload_path'] . $fdata['file_name'];
            $file_data ['fullPath'] = $fdata['full_path'];
            $file_data ['ext'] = $fdata['file_ext'];
            $file_data ['size'] = $fdata['file_size'];
            $file_data ['is_image'] = $fdata['is_image'];
            $file_data ['image_width'] = $fdata['image_width'];
            $file_data ['image_height'] = $fdata['image_height'];
            return $file_data;
            // uploading successfull, now do your further actions
        }
    }

    function uploadAllType($field)
    {
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '20240000245';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "error";
            $message = $error;
            set_message($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $fdata = $this->upload->data();
            $file_data ['fileName'] = $fdata['file_name'];
            $file_data ['path'] = $config['upload_path'] . $fdata['file_name'];
            $file_data ['fullPath'] = $fdata['full_path'];
            $file_data ['ext'] = $fdata['file_ext'];
            $file_data ['size'] = $fdata['file_size'];
            $file_data ['is_image'] = $fdata['is_image'];
            $file_data ['image_width'] = $fdata['image_width'];
            $file_data ['image_height'] = $fdata['image_height'];
            return $file_data;
            // uploading successfull, now do your further actions
        }
    }

    function multi_uploadAllType($field)
    {
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '20240000245';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_multi_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "error";
            $message = $error;
            set_message($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $multi_fdata = $this->upload->get_multi_upload_data();
            foreach ($multi_fdata as $fdata) {

                $file_data ['fileName'] = $fdata['file_name'];
                $file_data ['path'] = $config['upload_path'] . $fdata['file_name'];
                $file_data ['fullPath'] = $fdata['full_path'];
                $file_data ['ext'] = $fdata['file_ext'];
                $file_data ['size'] = $fdata['file_size'];
                $file_data ['is_image'] = $fdata['is_image'];
                $file_data ['image_width'] = $fdata['image_width'];
                $file_data ['image_height'] = $fdata['image_height'];

                $result[] = $file_data;
            }
            return $result;
            // uploading successfull, now do your further actions
        }
    }

    public function check_by($where, $tbl_name)
    {

        $this->db->select('*');
        $this->db->from($tbl_name);
        $this->db->where($where);
        $query_result = $this->db->get();
        $result = $query_result->row();
        return $result;
    }

    function count_rows($table, $where)
    {
        $this->db->where($where);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    function get_any_field($table, $where_criteria, $table_field)
    {
        $query = $this->db->select($table_field)->where($where_criteria)->get($table);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->$table_field;
        }
    }

    /**
     * @ Upadate row with duplicasi check
     */
    public function check_update($table, $where, $id = Null)
    {
        $this->db->select('*', FALSE);
        $this->db->from($table);
        if ($id != null) {
            $this->db->where($id);
        }
        $this->db->where($where);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    // set actiion setting 

    public function set_action($where, $value, $tbl_name)
    {
        $this->db->set($value);
        $this->db->where($where);
        $this->db->update($tbl_name);
    }

    function get_sum($table, $field, $where)
    {

        $this->db->where($where);
        $this->db->select_sum($field);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->$field;
        } else {
            return 0;
        }
    }

    public function get_limit($where, $tbl_name, $limit)
    {

        $this->db->select('*');
        $this->db->from($tbl_name);
        $this->db->where($where);
        $this->db->limit($limit);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    function short_description($string = FALSE, $from_start = 30, $from_end = 10, $limit = FALSE)
    {
        if (!$string) {
            return FALSE;
        }
        if ($limit) {
            if (mb_strlen($string) < $limit) {
                return $string;
            }
        }
        return mb_substr($string, 0, $from_start - 1) . "..." . ($from_end > 0 ? mb_substr($string, -$from_end) : '');
    }

    function get_table_field($tableName, $where = array(), $field)
    {

        return $this->db->select($field)->where($where)->get($tableName)->row()->$field;
    }

    function get_time_different($from, $to)
    {
        $diff = abs($from - $to);
        $years = $diff / 31557600;
        $months = $diff / 2635200;
        $weeks = $diff / 604800;
        $days = $diff / 86400;
        $hours = $diff / 3600;
        $minutes = $diff / 60;
        if ($years > 1) {
            $duration = round($years) . lang('years');
        } elseif ($months > 1) {
            $duration = round($months) . lang('months');
        } elseif ($weeks > 1) {
            $duration = round($weeks) . lang('weeks');
        } elseif ($days > 1) {
            $duration = round($days) . lang('days');
        } elseif ($hours > 1) {
            $duration = round($hours) . lang('hours');
        } else {
            $duration = round($minutes) . lang('minutes');
        }

        return $duration;
    }

    public function client_currency_sambol($client_id)
    {
        $this->db->select('tbl_client.currency', FALSE);
        $this->db->select('tbl_currencies.*', FALSE);
        $this->db->from('tbl_client');
        $this->db->join('tbl_currencies', 'tbl_currencies.code = tbl_client.currency', 'left');
        $this->db->where('tbl_client.client_id', $client_id);
        $query_result = $this->db->get();
        $result = $query_result->row();
        return $result;
    }

    public function allowad_user_id($menu_id)
    {
        $permission_user = $this->all_permission_user($menu_id);
        // if not exist data show empty array.
        if (!empty($permission_user)) {
            foreach ($permission_user as $p_user) {
                $user_id[] = $p_user->user_id;
            }
        }
        if (!empty($user_id)) {
            return $user_id;
        }
    }

    public function allowad_user($menu_id)
    {
        return $this->all_permission_user($menu_id);
    }

    public function all_permission_user($menu_id)
    {
        $this->db->select('tbl_user_role.designations_id', FALSE);
        $this->db->select('tbl_account_details.designations_id', FALSE);
        $this->db->select('tbl_users.*', FALSE);
        $this->db->from('tbl_user_role');
        $this->db->join('tbl_account_details', 'tbl_account_details.designations_id = tbl_user_role.designations_id', 'left');
        $this->db->join('tbl_users', 'tbl_users.user_id = tbl_account_details.user_id', 'left');
        $this->db->where('tbl_user_role.menu_id', $menu_id);
        $this->db->where('tbl_users.activated', 1);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function get_permission($table, $flag = null)
    {
        $role = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');
        if ($role != 1) {
            $result_info = $this->db->get($table)->result();
            if (!empty($result_info)) {
                foreach ($result_info as $result) {
                    if ($result->permission == 'all') {
                        $permission[] = $result;
                    } else {
                        $get_permission = json_decode($result->permission);
                        if (!empty($get_permission)) {
                            foreach ($get_permission as $id => $v_permission) {
                                if ($user_id == $id) {
                                    $permission[] = $result;
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $permission = $this->db->get($table)->result();
        }
        if (empty($permission)) {
            $permission = array();
        }
        return $permission;
    }

    public function my_permission($table, $user_id)
    {
        $result_info = $this->db->get($table)->result();
        if (!empty($result_info)) {
            foreach ($result_info as $result) {
                if ($result->permission == 'all') {
                    $permission[] = $result;
                } else {
                    $get_permission = json_decode($result->permission);
                    if (!empty($get_permission)) {
                        foreach ($get_permission as $id => $v_permission) {
                            if ($user_id == $id) {
                                $permission[] = $result;
                            }
                        }
                    }
                }
            }
        }
        if (empty($permission)) {
            $permission = array();
        }
        return $permission;
    }

    public function can_action($table, $action, $id)
    {
        $role = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');
        $result_info = $this->db->where($id)->get($table)->row();

        if ($role != 1) {
            if (!empty($result_info)) {
                if ($result_info->permission != 'all') {
                    $get_permission = json_decode($result_info->permission);
                } else {
                    return true;
                }
                if (!empty($get_permission)) {
                    foreach ($get_permission as $user => $v_permission) {
                        if (!empty($v_permission)) {
                            foreach ($v_permission as $v_action) {
                                if ($user == $user_id) {
                                    if ($v_action == $action) {
                                        return true;
                                    }
                                }

                            }
                        }
                    }
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }


    public
    function hash($string)
    {
        return hash('sha512', $string . config_item('encryption_key'));
    }

    public
    function generate_invoice_number()
    {
        $query = $this->db->select_max('invoices_id')->get('tbl_invoices');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $next_number = ++$row->invoices_id;
            $next_number = $this->reference_no_exists($next_number);
            $next_number = sprintf('%04d', $next_number);
            return $next_number;
        } else {
            return sprintf('%04d', config_item('invoice_start_no'));
        }
    }

    public
    function reference_no_exists($next_number)
    {
        $next_number = sprintf('%04d', $next_number);

        $records = $this->db->where('reference_no', config_item('invoice_prefix') . $next_number)->get('tbl_invoices')->num_rows();
        if ($records > 0) {
            return $this->reference_no_exists($next_number + 1);
        } else {
            return $next_number;
        }
    }

    function send_email($params)
    {
        $config = array();
        // If postmark API is being used
        if (config_item('use_postmark') == 'TRUE') {
            $config = array('api_key' => config_item('postmark_api_key'));
            $this->load->library('postmark', $config);
            $this->postmark->from(config_item('postmark_from_address'), config_item('company_name'));
            $this->postmark->to($params['recipient']);
            $this->postmark->subject($params['subject']);
            $this->postmark->message_plain($params['message']);
            $this->postmark->message_html($params['message']);
            // Check resourceed file
            if (isset($params['resourcement_url'])) {
                $this->postmark->resource($params['resourceed_file']);
            }
            $this->postmark->send();
        } else {
            // If using SMTP
            if (config_item('protocol') == 'smtp') {
                $this->load->library('encrypt');
                $raw_smtp_pass = config_item('smtp_pass');
                $config = array(
                    'smtp_host' => config_item('smtp_host'),
                    'smtp_port' => config_item('smtp_port'),
                    'smtp_user' => config_item('smtp_user'),
                    'smtp_pass' => $raw_smtp_pass,
                    'crlf' => "\r\n",
                    'protocol' => config_item('protocol'),
                );
            }
            // Send email
            $config['useragent'] = 'UniqueCoder LTD';
            $config['mailtype'] = "html";
            $config['newline'] = "\r\n";
            $config['charset'] = 'utf-8';
            $config['wordwrap'] = TRUE;

            $this->load->library('email', $config);
            $this->email->from(config_item('company_email'), config_item('company_name'));
            $this->email->to($params['recipient']);

            $this->email->subject($params['subject']);
            $this->email->message($params['message']);
            if ($params['resourceed_file'] != '') {
                $this->email->attach($params['resourceed_file']);
            }
            $send = $this->email->send();
            if ($send) {
                return $send;
            } else {
                $error = show_error($this->email->print_debugger());
                return $error;
            }
        }
    }

    public
    function all_files()
    {
        $language = array(
            "main_lang.php" => "./application/",
            "tasks_lang.php" => "./application/",
            "sales_lang.php" => "./application/",
            "transactions_lang.php" => "./application/",
            "tickets_lang.php" => "./application/",
            "client_lang.php" => "./application/",
            "departments_lang.php" => "./application/",
            "leave_management_lang.php" => "./application/",
            "settings_lang.php" => "./application/",
            "utilities_lang.php" => "./application/",
            "stock_lang.php" => "./application/",
            "performance_lang.php" => "./application/",
            "payroll_lang.php" => "./application/",
        );
        return $language;
    }

    function task_spent_time_by_id($id, $project = null)
    {
        if (!empty($project)) {
            $where = 'project_id = ' . $id;
        } else {
            $where = 'task_id = ' . $id;
        }
        $total_time = "SELECT start_time,end_time,end_time - start_time time_spent
						FROM tbl_tasks_timer WHERE $where";
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

    function get_time_spent_result($seconds)
    {
        $init = $seconds;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;
        return "<ul class='timer'><li>" . $hours . "<span>" . lang('hours') . "</span></li>" . "<li class='dots'>" . ":</li><li>" . $minutes . "<span>" . lang('minutes') . "</span></li>" . "<li class='dots'>" . ":</li><li>" . $seconds . "<span>" . lang('seconds') . "</span></li></ul>";

    }

    public function get_progress($goal_info, $currency = null)
    {

        $goal_type_info = $this->db->where('goal_type_id', $goal_info->goal_type_id)->get('tbl_goal_type')->row();

        $start_date = $goal_info->start_date;
        $end_date = $goal_info->end_date;
        $achievement = round($goal_info->achievement);
        if ($goal_type_info->tbl_name == 'tbl_transactions') {
            if ($goal_type_info->type_name == 'achive_total_income_by_bank' || $goal_type_info->type_name == 'achive_total_expense_by_bank') {
                if ($goal_info->account_id != '0') {
                    $where = array('account_id' => $goal_info->account_id, 'date >=' => $start_date, 'date <=' => $end_date, 'type' => $goal_type_info->query);
                } else {
                    $where = array('date >=' => $start_date, 'date <=' => $end_date, 'type' => $goal_type_info->query);
                }
            } else {
                $where = array('date >=' => $start_date, 'date <=' => $end_date, 'type' => $goal_type_info->query);
            }
            $curency = $this->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
            $transactions_result = $this->db->select_sum('amount')->where($where)->get($goal_type_info->tbl_name)->row()->amount;
            $tr_amount = round($transactions_result);
            if ($achievement <= $tr_amount) {
                $result['progress'] = 100;
            } else {
                $progress = ($tr_amount / $achievement) * 100;
                $result['progress'] = round($progress);

            }
            if (!empty($currency)) {
                $result['achievement'] = $tr_amount;
            } else {
                $result['achievement'] = display_money($tr_amount, $curency->symbol);
            }
        }
        if ($goal_type_info->tbl_name == 'tbl_invoices' || $goal_type_info->tbl_name == 'tbl_estimates') {
            $where = array('date_saved >=' => $start_date . " 00:00:00", 'date_saved <=' => $end_date . " 23:59:59");
            $invoice_result = count($this->db->where($where)->get($goal_type_info->tbl_name)->result());
            if ($achievement <= $invoice_result) {
                $result['progress'] = 100;
            } else {
                $progress = ($invoice_result / $achievement) * 100;
                $result['progress'] = round($progress);
            }
            $result['achievement'] = $invoice_result;
        }
        if ($goal_type_info->tbl_name == 'tbl_task') {
            $where = array('task_created_date >=' => $start_date . " 00:00:00", 'task_created_date <=' => $end_date . " 23:59:59", 'task_status' => 'completed');

            $task_result = count($this->db->where($where)->get($goal_type_info->tbl_name)->result());
            if ($achievement <= $task_result) {
                $result['progress'] = 100;
            } else {
                $progress = ($task_result / $achievement) * 100;
                $result['progress'] = round($progress);
            }
            $result['achievement'] = $task_result;
        }
        if ($goal_type_info->tbl_name == 'tbl_bug') {
            $where = array('update_time >=' => $start_date . " 00:00:00", 'update_time <=' => $end_date . " 23:59:59", 'bug_status' => 'resolved');

            $bugs_result = count($this->db->where($where)->get($goal_type_info->tbl_name)->result());
            if ($achievement <= $bugs_result) {
                $result['progress'] = 100;
            } else {
                $progress = ($bugs_result / $achievement) * 100;
                $result['progress'] = round($progress);
            }
            $result['achievement'] = $bugs_result;
        }
        if ($goal_type_info->tbl_name == 'tbl_client') {
            if ($goal_type_info->type_name = 'convert_leads_to_client') {
                $where = array('date_added >=' => $start_date . " 00:00:00", 'date_added <=' => $end_date . " 23:59:59", 'leads_id !=' => '0');
            } else {
                $where = array('date_added >=' => $start_date . " 00:00:00", 'date_added <=' => $end_date . " 23:59:59", 'leads_id' => '0');
            }
            $client_result = count($this->db->where($where)->get($goal_type_info->tbl_name)->result());

            if ($achievement <= $client_result) {
                $result['progress'] = 100;
            } else {
                $progress = ($client_result / $achievement) * 100;
                $result['progress'] = round($progress);
            }
            $result['achievement'] = $client_result;
        }
        if ($goal_type_info->tbl_name == 'tbl_payments') {
            $where = array('payment_date >=' => $start_date, 'payment_date <=' => $end_date);

            $payments_result = $this->db->select('currency')->select_sum('amount')->where($where)->get($goal_type_info->tbl_name)->row();

            if ($achievement <= $payments_result->amount) {
                $result['progress'] = 100;
            } else {
                $progress = ($payments_result->amount / $achievement) * 100;
                $result['progress'] = round($progress);
            }
            if (!empty($currency)) {
                $result['achievement'] = $payments_result->amount;
            } else {
                $result['achievement'] = display_money($payments_result->amount, $payments_result->currency);
            }
        }
        if ($goal_type_info->tbl_name == 'tbl_project') {
            $where = array('created_time >=' => $start_date . " 00:00:00", 'created_time <=' => $end_date . " 23:59:59", 'project_status' => 'completed');

            $task_result = count($this->db->where($where)->get($goal_type_info->tbl_name)->result());
            if ($achievement <= $task_result) {
                $result['progress'] = 100;
            } else {
                $progress = ($task_result / $achievement) * 100;
                $result['progress'] = round($progress);
            }
            $result['achievement'] = $task_result;
        }
        if (!empty($result)) {
            return $result;
        } else {
            $result['progress'] = 0;
            $result['achievement'] = 0;
            return $result;
        }
    }

    public function send_goal_mail($type, $goal_info)
    {
        $email_template = $this->check_by(array('email_group' => $type), 'tbl_email_templates');


        $goal_type_info = $this->db->where('goal_type_id', $goal_info->goal_type_id)->get('tbl_goal_type')->row();
        $progress = $this->get_progress($goal_info);

        $message = $email_template->template_body;

        $subject = $email_template->subject;

        $Type = str_replace("{Goal_Type}", lang($goal_type_info->type_name), $message);
        $achievement = str_replace("{achievement}", $goal_info->achievement, $Type);
        $total_achievement = str_replace("{total_achievement}", $progress['achievement'], $achievement);
        $start_date = str_replace("{start_date}", $goal_info->start_date, $total_achievement);
        $message = str_replace("{End_date}", $goal_info->end_date, $start_date);

        $data['message'] = $message;

        $message = $this->load->view('email_template', $data, TRUE);

        $params['subject'] = $subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';

        if (!empty($goal_info->permission) && $goal_info->permission != 'all') {
            $user = json_decode($goal_info->permission);
            foreach ($user as $key => $v_user) {
                $allowad_user[] = $key;
            }
        } else {
            $allowad_user = $this->allowad_user_id('69');
        }

        foreach ($allowad_user as $v_user) {
            $login_info = $this->check_by(array('user_id' => $v_user), 'tbl_users');
            $params['recipient'] = $login_info->email;
            $this->send_email($params);
        }

        $udate['email_send'] = 'yes';
        $this->_table_name = "tbl_goal_tracking"; //table name
        $this->_primary_key = "goal_tracking_id";
        $this->save($udate, $goal_info->goal_tracking_id);

        return true;
    }

    function GetDays($start_date, $end_date, $step = '+1 day', $output_format = 'Y-m-d')
    {

        $dates = array();
        $current = strtotime($start_date);
        $end_date = strtotime($end_date);
        while ($current <= $end_date) {
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

    public function all_designation()
    {
        $all_department = $this->db->get('tbl_departments')->result();
        if (!empty($all_department)) {
            foreach ($all_department as $v_department) {
                $designation[$v_department->deptname] = $this->db->where('departments_id', $v_department->departments_id)->get('tbl_designations')->result();
            }
            return $designation;
        }
    }

    public function get_all_employee()
    {
        $all_department = $this->db->get('tbl_departments')->result();
        if (!empty($all_department)) {
            foreach ($all_department as $v_department) {
                $designation[$v_department->deptname] = $this->all_employee($v_department->departments_id);
            }
            return $designation;
        }
    }

    function all_employee($department_id)
    {
        $this->db->select('tbl_account_details.*', FALSE);
        $this->db->select('tbl_designations.*', FALSE);
        $this->db->select('tbl_departments.*', FALSE);
        $this->db->from('tbl_account_details');
        $this->db->join('tbl_designations', 'tbl_account_details.designations_id = tbl_designations.designations_id', 'left');
        $this->db->join('tbl_departments', 'tbl_departments.departments_id = tbl_designations.departments_id', 'left');
        $this->db->where('tbl_departments.departments_id', $department_id);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }


}
