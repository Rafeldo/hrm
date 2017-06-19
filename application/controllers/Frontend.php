<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Frontend extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('job_circular_model');
        $this->load->helper('string');
    }

    function index()
    {
        $data['title'] = lang('job_posted_list');
        $data['subview'] = $this->load->view('frontend/job_vacancy', $data, TRUE);
        $this->load->view('frontend/_layout_main', $data);
    }

    public function circular_details($id)
    {
        $data['title'] = lang('view_circular_details');

        //get all training information
        $data['circular_details'] = $this->db->where('job_circular_id', $id)->get('tbl_job_circular')->row();

        $data['subview'] = $this->load->view('frontend/circular_details', $data, TRUE);
        $this->load->view('frontend/_layout_main', $data);
    }

    public function apply_jobs($id)
    {
        $data['title'] = lang('view_circular_details');

        //get all training information
        $data['circular_info'] = $this->db->where('job_circular_id', $id)->get('tbl_job_circular')->row();

        $data['subview'] = $this->load->view('frontend/apply_jobs', $data, false);
        $this->load->view('admin/_layout_modal_lg', $data);
    }

    public function save_job_application($id)
    {
        $data = $this->job_circular_model->array_from_post(array('name', 'email', 'mobile', 'cover_letter'));
        // Resume File upload
        if (!empty($_FILES['resume']['name'])) {
            $val = $this->job_circular_model->uploadFile('resume');
            $val == TRUE || redirect('employee/dashboard/job_circular');
            $data['resume'] = $val['path'];
        }
        $data['job_circular_id'] = $id;

        $this->job_circular_model->_table_name = 'tbl_job_appliactions';
        $this->job_circular_model->_primary_key = 'job_appliactions_id';
        $job_appliactions_id = $this->job_circular_model->save($data);

        $circular_info = $this->db->where('job_circular_id', $id)->get('tbl_job_circular')->row();

        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'job_circular',
            'module_field_id' => $job_appliactions_id,
            'activity' => 'activity_new_job_application',
            'icon' => 'fa-ticket',
            'value1' => $circular_info->job_title,
            'value2' => $data['name'],
        );
        // Update into tbl_project
        $this->job_circular_model->_table_name = "tbl_activities"; //table name
        $this->job_circular_model->_primary_key = "activities_id";
        $this->job_circular_model->save($activities);

        $job_circular_email = config_item('job_circular_email');
        if (!empty($job_circular_email) && $job_circular_email == 1) {

            $design_info = $this->db->where('designations_id', $circular_info->designations_id)->get('tbl_designations')->row();
            $dept_head_id = $this->db->where('departments_id', $design_info->departments_id)->get('tbl_departments')->row();
            $user_info = $this->job_circular_model->check_by(array('user_id' => $dept_head_id->department_head_id), 'tbl_users');
            $email_template = $this->job_circular_model->check_by(array('email_group' => 'new_job_application_email'), 'tbl_email_templates');

            $message = $email_template->template_body;
            $subject = $email_template->subject;
            $name = str_replace("{NAME}", $data['name'], $message);
            $job_title = str_replace("{JOB_TITLE}", $circular_info->job_title, $name);
            $email = str_replace("{EMAIL}", $data['email'], $job_title);
            $mobile = str_replace("{MOBILE}", $data['mobile'], $email);
            $cover_letter = str_replace("{COVER_LETTER}", $data['cover_letter'], $mobile);
            $Link = str_replace("{LINK}", base_url() . 'admin/job_circular/jobs_applications/jobs_application_details/' . $job_appliactions_id, $cover_letter);
            $message = str_replace("{SITE_NAME}", config_item('company_name'), $Link);
            $data['message'] = $message;
            $message = $this->load->view('email_template', $data, TRUE);

            $params['subject'] = $subject;
            $params['message'] = $message;
            $params['resourceed_file'] = '';
            $params['recipient'] = $user_info->email;
            $this->job_circular_model->send_email($params);
        }

        // messages for user
        $type = "success";
        $message = lang('job_application_submitted');
        set_message($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function jobs_posted_pdf($id)
    {
        $data['job_posted'] = $this->db->where('job_circular_id', $id)->get('tbl_job_circular')->row();

        $this->load->helper('dompdf');
        $view_file = $this->load->view('admin/job_circular/jobs_posted_pdf', $data, true);
        pdf_create($view_file, lang('jobs_posted') . '- ' . $data['job_posted']->job_title);
    }


}
