<?php

/**
 * Description of training
 *
 * @author Ashraf
 */
class Training extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('training_model');
    }

    public function index($id = NULL)
    {
        $data['title'] = lang('all') . ' ' . lang('training');
        //get all training information
        $data['all_training_info'] = $this->training_model->get_permission('tbl_training');

        $data['assign_user'] = $this->training_model->allowad_user('101');

        $data['subview'] = $this->load->view('admin/training/trainings', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function new_training($id = null)
    {
        $data['title'] = lang('new') . ' ' . lang('training');
        if (!empty($id)) {
            $can_edit = $this->training_model->can_action('tbl_training', 'edit', array('training_id' => $id));
            if (!empty($can_edit)) {
                $data['training_info'] = $this->training_model->get_all_training_info($id);
            }
        }
        // get all employee info
        $data['all_employee'] = $this->training_model->get_all_employee();

        $data['subview'] = $this->load->view('admin/training/new_training', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data); //page load
    }

    public function save_training($id = NULL)
    {

        $data = $this->training_model->array_from_post(array(
            'user_id',
            'training_name',
            'vendor_name',
            'start_date',
            'finish_date',
            'training_cost',
            'status',
            'performance',
            'remarks'));

        $fileName = $this->input->post('fileName');
        $path = $this->input->post('path');
        $fullPath = $this->input->post('fullPath');
        $size = $this->input->post('size');
        $is_image = $this->input->post('is_image');

        if (!empty($fileName)) {
            foreach ($fileName as $key => $name) {
                $old['fileName'] = $name;
                $old['path'] = $path[$key];
                $old['fullPath'] = $fullPath[$key];
                $old['size'] = $size[$key];
                $old['is_image'] = $is_image[$key];
                $result[] = $old;
            }

            $data['upload_file'] = json_encode($result);
        }

        if (!empty($_FILES['upload_file']['name']['0'])) {
            $old_path_info = $this->input->post('upload_path');
            if (!empty($old_path_info)) {
                foreach ($old_path_info as $old_path) {
                    unlink($old_path);
                }
            }
            $mul_val = $this->training_model->multi_uploadAllType('upload_file');

            $data['upload_file'] = json_encode($mul_val);
        }

        if (!empty($result) && !empty($mul_val)) {
            $file = array_merge($result, $mul_val);
            $data['upload_file'] = json_encode($file);
        }
        if (empty($id)) {
            $data['assigned_by'] = $this->session->userdata('user_id');
        }
        $permission = $this->input->post('permission', true);
        if (!empty($permission)) {
            if ($permission == 'everyone') {
                $assigned = 'all';
            } else {
                $assigned_to = $this->training_model->array_from_post(array('assigned_to'));
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

        //save data into table.
        $this->training_model->_table_name = "tbl_training"; // table name
        $this->training_model->_primary_key = "training_id"; // $id
        $return_id = $this->training_model->save($data, $id);

        if (!empty($id)) {
            $activity = 'activity_update_training';
            $msg = lang('training_information_update');
        } else {
            $activity = 'activity_added_training';
            $msg = lang('training_information_saved');

            $training_email = config_item('training_email');
            if (!empty($training_email) && $training_email == 1) {

                $user_info = $this->training_model->check_by(array('user_id' => $data['user_id']), 'tbl_users');

                $email_template = $this->training_model->check_by(array('email_group' => 'new_training_email'), 'tbl_email_templates');
                $message = $email_template->template_body;
                $subject = $email_template->subject;
                $title = str_replace("{TRAINING_NAME}", $data['training_name'], $message);
                $assigned_by = str_replace("{ASSIGNED_BY}", $this->session->userdata('name'), $title);
                $Link = str_replace("{LINK}", base_url() . 'admin/training/training_details/' . $return_id, $assigned_by);
                $message = str_replace("{SITE_NAME}", config_item('company_name'), $Link);
                $data['message'] = $message;
                $message = $this->load->view('email_template', $data, TRUE);

                $params['subject'] = $subject;
                $params['message'] = $message;
                $params['resourceed_file'] = '';

                $params['recipient'] = $user_info->email;
                $this->training_model->send_email($params);
            }
        }
        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'training',
            'module_field_id' => $id,
            'activity' => $activity,
            'icon' => 'fa-ticket',
            'value1' => $data['training_name'],
        );

        // Update into tbl_project
        $this->training_model->_table_name = "tbl_activities"; //table name
        $this->training_model->_primary_key = "activities_id";
        $this->training_model->save($activities);


        $type = "success";
        $message = $msg;
        set_message($type, $message);
        redirect('admin/training');
    }

    public function training_details($id = NULL)
    {
        $data['title'] = lang('training_details');
        //get all training information
        $data['training_info'] = $this->training_model->get_all_training_info($id);

        $data['subview'] = $this->load->view('admin/training/training_detail', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data);
    }

    public function delete_training($id = NULL)
    {
        $can_delete = $this->training_model->can_action('tbl_training', 'delete', array('training_id' => $id));
        if (!empty($can_delete)) {

            $training_info = $training_info = $this->db->where('training_id', $id)->get('tbl_training')->row();
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'award',
                'module_field_id' => $id,
                'activity' => 'activity_delete_training',
                'icon' => 'fa-ticket',
                'value1' => $training_info->training_name,
            );

            // Update into tbl_project
            $this->training_model->_table_name = "tbl_activities"; //table name
            $this->training_model->_primary_key = "activities_id";
            $this->training_model->save($activities);


            $this->training_model->_table_name = "tbl_training"; // table name
            $this->training_model->_primary_key = "training_id"; // $id
            $this->training_model->delete($id); // delete

            // messages for user
            $type = "success";
            $message = lang('training_information_delete');
        } else {
            $type = "error";
            $message = 'Operation Field
             !';
        }
        set_message($type, $message);
        redirect('admin/training');
    }

    public function training_pdf($id)
    {
        $data['training_info'] = $this->training_model->get_all_training_info($id);

        $this->load->helper('dompdf');
        $view_file = $this->load->view('admin/training/training_pdf', $data, true);
        pdf_create($view_file, lang('training_details') . '- ' . $data['training_info']->fullname);
    }

    public function download_file($id)
    {
        $this->load->helper('download');
        $file = $this->uri->segment(5);

        if ($id) {
            $down_data = file_get_contents('uploads/' . $file); // Read the file's contents
            force_download($file, $down_data);
        } else {
            $type = "error";
            $message = 'Operation Fieled !';
            set_message($type, $message);
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

}
