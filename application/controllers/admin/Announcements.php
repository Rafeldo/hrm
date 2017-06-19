<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Announcements extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('announcements_model');

    }

    public function index($id = NULL)
    {

        $data['title'] = lang('all') . ' ' . lang('announcements');
        if ($id) {
            $data['announcements'] = $this->db->where('announcements_id', $id)->get('tbl_announcements')->row();
            if (empty($data['announcements'])) {
                $type = "error";
                $message = "No Record Found";
                set_message($type, $message);
                redirect('admin/announcements');
            }
        }
        $data['all_announcements'] = $this->db->get('tbl_announcements')->result();

        $data['subview'] = $this->load->view('admin/announcements/all_announcements', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function new_announcements($id = null)
    {
        $data['title'] = lang('new') . ' ' . lang('announcements'); //Page title

        $this->announcements_model->_table_name = "tbl_announcements"; // table name
        $this->announcements_model->_order_by = "announcements_id"; // $id
        $data['all_announcements'] = $this->announcements_model->get();
        if (!empty($id)) {
            $data['announcements'] = $this->db->where('announcements_id', $id)->get('tbl_announcements')->row();
            if (empty($data['announcements'])) {
                $type = "error";
                $message = "No Record Found";
                set_message($type, $message);
                redirect('admin/announcements/create_announcements');
            }
        }
        $data['subview'] = $this->load->view('admin/announcements/new_announcements', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data); //page load
    }

    public function save_announcements($id = NULL)
    {
        $data = $this->announcements_model->array_from_post(array(
            'title',
            'description',
            'start_date',
            'end_date',
            'all_client',
            'status',
        ));

        $data['user_id'] = $this->session->userdata('user_id');

        $this->announcements_model->_table_name = "tbl_announcements"; // table name
        $this->announcements_model->_primary_key = "announcements_id"; // $id
        $return_id = $this->announcements_model->save($data, $id);
        if (!empty($id)) {
            $activity = 'activity_update_announcements';
            $msg = lang('announcements_information_update');
        } else {
            $activity = 'activity_added_announcements';
            $msg = lang('announcements_information_saved');

            $announcements_email = config_item('announcements_email');
            if (!empty($announcements_email) && $announcements_email == 1) {

                $email_template = $this->announcements_model->check_by(array('email_group' => 'new_notice_published'), 'tbl_email_templates');
                $message = $email_template->template_body;
                $subject = $email_template->subject;
                $title = str_replace("{TITLE}", $data['title'], $message);
                $Link = str_replace("{LINK}", base_url() . 'admin/announcements/announcements_details/' . $return_id, $title);
                $message = str_replace("{SITE_NAME}", config_item('company_name'), $Link);
                $data['message'] = $message;
                $message = $this->load->view('email_template', $data, TRUE);

                $params['subject'] = $subject;
                $params['message'] = $message;
                $params['resourceed_file'] = '';
                if ($data['all_client'] == 1) {
                    $all_users = $this->db->get('tbl_users')->result();
                } else {
                    $all_users = $this->db->where('role_id !=', '2')->get('tbl_users')->result();
                }
                foreach ($all_users as $v_user) {
                    $params['recipient'] = $v_user->email;
                    $this->announcements_model->send_email($params);
                }
            }
        }
        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'announcements',
            'module_field_id' => $id,
            'activity' => $activity,
            'icon' => 'fa-ticket',
            'value1' => $data['title'],
        );

        // Update into tbl_project
        $this->announcements_model->_table_name = "tbl_activities"; //table name
        $this->announcements_model->_primary_key = "activities_id";
        $this->announcements_model->save($activities);

        // messages for user
        $type = "success";
        $message = $msg;
        set_message($type, $message);
        redirect('admin/announcements');
    }


    public function announcements_details($id)
    {
        $data['title'] = lang('announcements_details'); //Page title

        $this->announcements_model->_table_name = "tbl_announcements"; // table name
        $this->announcements_model->_order_by = "announcements_id"; // $id
        $data['announcements_details'] = $this->announcements_model->get_by(array('announcements_id' => $id), TRUE);
        $this->announcements_model->_primary_key = 'announcements_id';
        $updata['view_status'] = '1';
        $this->announcements_model->save($updata, $id);
        $data['subview'] = $this->load->view('admin/announcements/announcements_details', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data); //page load
    }

    public function delete_announcements($id = NULL)
    {
        $announcements_info = $this->announcements_model->check_by(array('announcements_id' => $id), 'tbl_announcements');
        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'announcements',
            'module_field_id' => $id,
            'activity' => 'activity_delete_announcements',
            'icon' => 'fa-ticket',
            'value1' => $announcements_info->title,
        );

        // Update into tbl_project
        $this->announcements_model->_table_name = "tbl_activities"; //table name
        $this->announcements_model->_primary_key = "activities_id";
        $this->announcements_model->save($activities);

        $this->announcements_model->_table_name = "tbl_announcements";
        $this->announcements_model->_primary_key = "announcements_id";
        $this->announcements_model->delete($id);;

        // messages for user
        $type = "success";
        $message = lang('announcements_information_delete');
        set_message($type, $message);
        redirect('admin/announcements');
    }

}
