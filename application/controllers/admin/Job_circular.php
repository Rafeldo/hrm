<?php

class Job_Circular extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('job_circular_model');
    }

    public function jobs_posted($flag = NULL, $id = NULL)
    {
        $data['title'] = lang('job_posted_list');

        //get all training information
        $data['job_post_info'] = $this->job_circular_model->get_permission('tbl_job_circular');

        $data['subview'] = $this->load->view('admin/job_circular/jobs_posted', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function new_jobs_posted($id = null)
    {
        $data['title'] = lang('new') . ' ' . lang('jobs_posted');
        if (!empty($id)) {
            $can_edit = $this->job_circular_model->can_action('tbl_job_circular', 'edit', array('job_circular_id' => $id));
            if (!empty($can_edit)) {
                $data['job_posted'] = $this->db->where('job_circular_id', $id)->get('tbl_job_circular')->row();

                if (empty($data['job_posted'])) {
                    // messages for user
                    $type = "error";
                    $message = "Not Found!";
                    set_message($type, $message);
                    redirect('admin/job_circular/jobs_posted');
                }
            }
        }
        // get all department info and designation info
        $data['all_dept_info'] = $this->db->get('tbl_departments')->result();
        // get all department info and designation info
        foreach ($data['all_dept_info'] as $v_dept_info) {
            $data['all_department_info'][] = $this->job_circular_model->get_add_department_by_id($v_dept_info->departments_id);
        }

        $data['assign_user'] = $this->job_circular_model->allowad_user('103');

        $data['subview'] = $this->load->view('admin/job_circular/new_jobs_posted', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data); //page load
    }

    public function save_job_posted($id = NULL)
    {

        $data = $this->job_circular_model->array_from_post(array('job_title', 'designations_id', 'vacancy_no', 'posted_date', 'description', 'last_date', 'status'));

        $permission = $this->input->post('permission', true);
        if (!empty($permission)) {
            if ($permission == 'everyone') {
                $assigned = 'all';
            } else {
                $assigned_to = $this->job_circular_model->array_from_post(array('assigned_to'));
                if (!empty($assigned_to['assigned_to'])) {
                    foreach ($assigned_to['assigned_to'] as $assign_user) {
                        $assigned[$assign_user] = $this->input->post('action_' . $assign_user, true);
                    }
                }
            }
            if ($assigned != 'all') {
                $assigned = json_encode($assigned);
            }
            $data['permission'] = $assigned;
        } else {
            set_message('error', lang('assigned_to') . ' Field is required');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $this->job_circular_model->_table_name = "tbl_job_circular"; // table name
        $this->job_circular_model->_primary_key = "job_circular_id"; // $id  
        $return_id = $this->job_circular_model->save($data, $id);

        if (!empty($id)) {
            $activity = 'activity_update_job_posted';
            $msg = lang('job_posted_information_update');
        } else {
            $activity = 'activity_added_job_posted';
            $msg = lang('job_posted_information_saved');
            $id = $return_id;
        }

        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'job_circular',
            'module_field_id' => $id,
            'activity' => $activity,
            'icon' => 'fa-ticket',
            'value1' => $data['job_title'],
        );

        // Update into tbl_project
        $this->job_circular_model->_table_name = "tbl_activities"; //table name
        $this->job_circular_model->_primary_key = "activities_id";
        $this->job_circular_model->save($activities);

        // messages for user
        $type = "success";
        $message = $msg;
        set_message($type, $message);
        redirect('admin/job_circular/jobs_posted');
    }

    public function delete_jobs_posted($id)
    {
        // check into tbl_job_allocations 
        // if id exist delete this
        $check_existing_data = $this->job_circular_model->check_by(array('job_circular_id' => $id), 'tbl_job_appliactions');
        $job_posted_info = $this->job_circular_model->check_by(array('job_circular_id' => $id), 'tbl_job_circular');

        if (empty($check_existing_data)) {

            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'job_circular',
                'module_field_id' => $id,
                'activity' => 'activity_delete_job_posted',
                'icon' => 'fa-ticket',
                'value1' => $job_posted_info->job_title,
            );
            // Update into tbl_project
            $this->job_circular_model->_table_name = "tbl_activities"; //table name
            $this->job_circular_model->_primary_key = "activities_id";
            $this->job_circular_model->save($activities);

            // delete into tbl_job_circular
            $this->job_circular_model->_table_name = "tbl_job_circular"; // table name
            $this->job_circular_model->_primary_key = "job_circular_id"; // $id  
            $this->job_circular_model->delete($id);
            // messages for user
            $type = "success";
            $message = lang('job_posted_information_delete');

        } else {
            $type = "error";
            $message = lang('job_posted_information_used');
        }
        set_message($type, $message);
        redirect('admin/job_circular/jobs_posted');
    }

    public function change_status($status, $id)
    {
        // if flag == 1 that means it is published to un pubslished
        // else unpublished to pubslished        
        $this->job_circular_model->set_action(array('job_circular_id' => $id), array('status' => $status), 'tbl_job_circular');

        $type = "success";
        $message = lang('job_posted_status_change') . ' ' . $status . ' !';
        set_message($type, $message);
        redirect('admin/job_circular/jobs_posted');
    }

    public function view_circular_details($id)
    {
        $data['title'] = lang('view_circular_details');
        $data['job_posted'] = $this->db->where('job_circular_id', $id)->get('tbl_job_circular')->row();
        $data['subview'] = $this->load->view('admin/job_circular/circular_details', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data); //page load
    }

    public function jobs_posted_pdf($id)
    {
        $data['job_posted'] = $this->db->where('job_circular_id', $id)->get('tbl_job_circular')->row();

        $this->load->helper('dompdf');
        $view_file = $this->load->view('admin/job_circular/jobs_posted_pdf', $data, true);
        pdf_create($view_file, lang('jobs_posted') . '- ' . $data['job_posted']->job_title);
    }

    public function jobs_applications()
    {
        $data['title'] = lang('all_jobs_application');
        // get salary template deatails       
        $data['job_application_info'] = $this->job_circular_model->get_job_application_info();

        $data['subview'] = $this->load->view('admin/job_circular/jobs_applications', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function change_application_status($flag, $id)
    {
        // if flag == 1 that means it is published to un pubslished
        // else unpublished to pubslished        
        $this->job_circular_model->set_action(array('job_appliactions_id' => $id), array('application_status' => $flag), 'tbl_job_appliactions');
        // messages for user
        if ($flag == 1) {
            $status = lang('approved');
        } else {
            $status = lang('rejected');
        }
        $type = "success";
        $message = lang('job_posted_status_change') . ' ' . $status . ' !';
        set_message($type, $message);
        redirect('admin/job_circular/jobs_applications');
    }

    public function jobs_application_details($id)
    {
        $data['title'] = lang('jobs_application_details');
        // get salary template deatails

        $data['job_application_info'] = $this->job_circular_model->get_job_application_info($id);

        $data['subview'] = $this->load->view('admin/job_circular/jobs_applications_details', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }

    public function delete_jobs_application($id)
    {
        $jobs_application = $this->job_circular_model->get_job_application_info($id);
        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'job_circular',
            'module_field_id' => $id,
            'activity' => 'activity_delete_job_application',
            'icon' => 'fa-ticket',
            'value1' => $jobs_application->name,
        );
        // Update into tbl_project
        $this->job_circular_model->_table_name = "tbl_activities"; //table name
        $this->job_circular_model->_primary_key = "activities_id";
        $this->job_circular_model->save($activities);

        $this->job_circular_model->_table_name = "tbl_job_appliactions"; // table name
        $this->job_circular_model->_primary_key = "job_appliactions_id"; // $id  
        $this->job_circular_model->delete($id);

        // messages for user
        $type = "success";
        $message = lang('deleted_job_application');
        set_message($type, $message);
        redirect('admin/job_circular/jobs_applications');
    }

    public function download_resume($filename)
    {
        $this->load->helper('download');
        $file = $this->uri->segment(5);
        force_download($file, $filename);

    }

}
