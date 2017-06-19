<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admistrator
 *
 * @author pc mart ltd
 */
class Tickets extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('tickets_model');
    }

    public function index($action = NULL, $id = NULL)
    {

        $data['title'] = "Tickets Details"; //Page title      
        if (!empty($id)) {
            $can_edit = $this->tickets_model->can_action('tbl_tickets', 'edit', array('tickets_id' => $id));
            if (!empty($can_edit)) {
                $data['tickets_info'] = $this->tickets_model->check_by(array('tickets_id' => $id), 'tbl_tickets');
            }

        }
        if ($action == 'edit_tickets') {
            $data['active'] = 2;
        } else {
            $data['active'] = 1;
        }
        $data['page'] = lang('tickets');
        $data['sub_active'] = lang('all_tickets');
        if ($action == 'tickets_details') {
            $data['tickets_info'] = $this->tickets_model->check_by(array('tickets_id' => $id), 'tbl_tickets');
            $subview = 'tickets_details';
        } elseif ($action == 'download_file') {
            $this->load->helper('download');
            $file = $this->uri->segment(6);
            if ($id) {
                $down_data = file_get_contents('uploads/' . $file); // Read the file's contents
                force_download($file, $down_data);
            } else {
                $type = "error";
                $message = 'Operation Fieled !';
                set_message($type, $message);
                redirect($_SERVER['HTTP_REFERER']);
            }
        } elseif ($action == 'save_reply') {
            $status = $this->uri->segment(6);
            if (!empty($status)) {
                $this->tickets_model->set_action(array('tickets_id' => $id), array('status' => $status), 'tbl_tickets');
            }
            $rdata['body'] = $this->input->post('body', TRUE);
            if (!empty($_FILES['attachment']['name']['0'])) {
                $old_path_info = $this->input->post('upload_path');
                if (!empty($old_path_info)) {
                    foreach ($old_path_info as $old_path) {
                        unlink($old_path);
                    }
                }
                $mul_val = $this->tickets_model->multi_uploadAllType('attachment');
                $rdata['attachment'] = json_encode($mul_val);
            }

            $rdata['tickets_id'] = $id;
            $rdata['replierid'] = $this->session->userdata('user_id');


            $this->tickets_model->_table_name = 'tbl_tickets_replies';
            $this->tickets_model->_primary_key = 'tickets_replies_id';
            $this->tickets_model->save($rdata);

            //check this tickets already answer or not
            $ticket_info = $this->db->where(array('tickets_id' => $id, 'status' => 'answered'))->get('tbl_tickets')->row();
            if (!empty($ticket_info)) {
                $this->tickets_model->set_action(array('tickets_id' => $id), array('status' => 'answered'), 'tbl_tickets');
            }

            $user_info = $this->db->where(array('user_id' => $rdata['replierid']))->get('tbl_users')->row();

            if ($user_info->role_id == '2') {
                $this->get_notify_ticket_reply('admin', $rdata); // Send email to admins
            } else {
                $this->get_notify_ticket_reply('client', $rdata); // Send email to client
            }
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'tickets',
                'module_field_id' => $id,
                'activity' => 'activity_reply_tickets',
                'icon' => 'fa-ticket',
                'value1' => $rdata['body'],
            );
            // Update into tbl_project
            $this->tickets_model->_table_name = "tbl_activities"; //table name
            $this->tickets_model->_primary_key = "activities_id";
            $this->tickets_model->save($activities);
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $subview = 'tickets';
        }

        // get permission user by menu id
        $data['permission_user'] = $this->tickets_model->all_permission_user('7');

        $data['all_tickets_info'] = $this->tickets_model->get_permission('tbl_tickets');

        $data['subview'] = $this->load->view('admin/tickets/' . $subview, $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public
    function create_tickets($id = NULL)
    {
        $data = $this->tickets_model->array_from_post(array('ticket_code', 'subject', 'reporter', 'priority', 'departments_id', 'body'));
        if (empty($id)) {
            $status = $this->input->post('status', true);
            if (!empty($status)) {
                $data['status'] = $status;
            } else {
                $data['status'] = 'open';
            }
        }

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
            $mul_val = $this->tickets_model->multi_uploadAllType('upload_file');
            $data['upload_file'] = json_encode($mul_val);
        }

        if (!empty($result) && !empty($mul_val)) {
            $file = array_merge($result, $mul_val);
            $data['upload_file'] = json_encode($file);
        }

        $permission = $this->input->post('permission', true);
        if (!empty($permission)) {
            if ($permission == 'everyone') {
                $assigned = 'all';
            } else {
                $assigned_to = $this->tickets_model->array_from_post(array('assigned_to'));
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

        $this->tickets_model->_table_name = 'tbl_tickets';
        $this->tickets_model->_primary_key = 'tickets_id';
        if (!empty($id)) {
            $this->tickets_model->save($data, $id);
        } else {
            $id = $this->tickets_model->save($data, $id);
        }
        save_custom_field(7, $id);
        // send email to reporter
        $this->send_tickets_info_by_email($data);
        // Send email to Client
        $this->send_tickets_info_by_email($data, TRUE);

        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'tickets',
            'module_field_id' => $id,
            'activity' => 'activity_create_tickets',
            'icon' => 'fa-ticket',
            'value1' => $data['ticket_code'],
        );
        // Update into tbl_project
        $this->tickets_model->_table_name = "tbl_activities"; //table name
        $this->tickets_model->_primary_key = "activities_id";
        $this->tickets_model->save($activities);

        // messages for user
        $type = "success";
        $message = lang('ticket_created');
        set_message($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    function send_tickets_info_by_email($postdata, $client = NULL)
    {

        if (!empty($postdata['reporter'])) {
            $postdata['reporter'] = $postdata['reporter'];
        } else {
            $postdata['reporter'] = $this->session->userdata('user_id');
        }

        $user_login_info = $this->tickets_model->check_by(array('user_id' => $postdata['reporter']), 'tbl_users');
        $ticket_info = $this->tickets_model->check_by(array('ticket_code' => $postdata['ticket_code']), 'tbl_tickets');

        if (!empty($client)) {
            $email_template = $this->tickets_model->check_by(array('email_group' => 'ticket_client_email'), 'tbl_email_templates');
            $message = $email_template->template_body;
            $subject = $email_template->subject;

            $client_email = str_replace("{CLIENT_EMAIL}", $user_login_info->email, $message);
            $ticket_code = str_replace("{TICKET_CODE}", $postdata['ticket_code'], $client_email);
            $TicketLink = str_replace("{TICKET_LINK}", base_url() . 'client/tickets/index/tickets_details/' . $ticket_info->tickets_id, $ticket_code);
            $message = str_replace("{SITE_NAME}", config_item('company_name'), $TicketLink);
            $data['message'] = $message;

            $message = $this->load->view('email_template', $data, TRUE);

            $subject = str_replace("[TICKET_CODE]", '[' . $postdata['ticket_code'] . ']', $subject);

            $params['recipient'] = $user_login_info->email;
            $params['subject'] = $subject;
            $params['message'] = $message;
            $params['resourceed_file'] = '';
            $this->tickets_model->send_email($params);
        } else {
            $email_template = $this->tickets_model->check_by(array('email_group' => 'ticket_staff_email'), 'tbl_email_templates');

            $designation_info = $this->db->where('departments_id', $postdata['departments_id'])->get('tbl_designations')->result();
            if (!empty($designation_info)) {
                foreach ($designation_info as $v_designation) {
                    $user_info[] = $this->db->where('designations_id', $v_designation->designations_id)->get('tbl_account_details')->row();
                }
            }

            $message = $email_template->template_body;
            $subject = $email_template->subject;

            $TicketCode = str_replace("{TICKET_CODE}", $postdata['ticket_code'], $message);
            $ReporterEmail = str_replace("{REPORTER_EMAIL}", $user_login_info->email, $TicketCode);
            $TicketLink = str_replace("{TICKET_LINK}", base_url() . 'admin/tickets/index/tickets_details/' . $ticket_info->tickets_id, $ReporterEmail);
            $message = str_replace("{SITE_NAME}", config_item('company_name'), $TicketLink);
            $data['message'] = $message;
            $message = $this->load->view('email_template', $data, TRUE);

            $subject = str_replace("[TICKET_CODE]", '[' . $postdata['ticket_code'] . ']', $subject);

            $params['subject'] = $subject;
            $params['message'] = $message;
            $params['resourceed_file'] = '';

            if (!empty($user_info)) {
                foreach ($user_info as $v_user) {
                    $login_info = $this->tickets_model->check_by(array('user_id' => $v_user->user_id), 'tbl_users');
                    $params['recipient'] = $login_info->email;
                    $this->tickets_model->send_email($params);
                }
            }
        }
    }

    function get_notify_ticket_reply($users, $postdata)
    {
        $email_template = $this->tickets_model->check_by(array('email_group' => 'ticket_reply_email'), 'tbl_email_templates');
        $tickets_info = $this->tickets_model->check_by(array('tickets_id' => $postdata['tickets_id']), 'tbl_tickets');

        $message = $email_template->template_body;

        $subject = $email_template->subject;

        $status = $tickets_info->status;

        $TicketCode = str_replace("{TICKET_CODE}", $tickets_info->ticket_code, $message);
        $TicketStatus = str_replace("{TICKET_STATUS}", ucfirst($status), $TicketCode);
        $TicketLink = str_replace("{TICKET_LINK}", base_url() . 'client/tickets/index/tickets_details/' . $tickets_info->tickets_id, $TicketStatus);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $TicketLink);

        $subject = str_replace("[TICKET_CODE]", '[' . $tickets_info->ticket_code . ']', $subject);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $params['subject'] = $subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';

        switch ($users) {
            case 'admin':
                $designation_info = $this->db->where('departments_id', $tickets_info->departments_id)->get('tbl_designations')->result();
                if (!empty($designation_info)) {
                    foreach ($designation_info as $v_designation) {
                        $user_info[] = $this->db->where('designations_id', $v_designation->designations_id)->get('tbl_account_details')->row();
                    }
                }
                if (!empty($user_info)) {
                    foreach ($user_info as $v_user) {
                        $login_info = $this->tickets_model->check_by(array('user_id' => $v_user->user_id), 'tbl_users');
                        $params['recipient'] = $login_info->email;
                        $this->tickets_model->send_email($params);
                    }
                }
            default:
                $login_info = $this->tickets_model->check_by(array('user_id' => $tickets_info->reporter), 'tbl_users');
                if (!empty($login_info)) {
                    $params['recipient'] = $login_info->email;
                    $this->tickets_model->send_email($params);
                }
        }
    }

    public
    function change_status($id, $status)
    {
        $can_edit = $this->tickets_model->can_action('tbl_tickets', 'edit', array('tickets_id' => $id));
        if (!empty($can_edit)) {
            $data['id'] = $id;
            $data['status'] = $status;
            $data['modal_subview'] = $this->load->view('admin/tickets/_modal_change_status', $data, FALSE);
            $this->load->view('admin/_layout_modal', $data);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);

        }
    }

    public
    function answered()
    {
        $data['title'] = 'Answerd Ticket';
        $data['active'] = 1;

        $data['ticket_status'] = 'answered';
        // get permission user by menu id
        $data['permission_user'] = $this->tickets_model->all_permission_user('7');
        $data['all_tickets_info'] = $this->tickets_model->get_permission('tbl_tickets');
        $data['subview'] = $this->load->view('admin/tickets/tickets', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public
    function closed()
    {
        $data['title'] = 'Close Ticket';
        $data['active'] = 1;
        $data['ticket_status'] = 'closed';
        $data['permission_user'] = $this->tickets_model->all_permission_user('7');
        $data['all_tickets_info'] = $this->tickets_model->get_permission('tbl_tickets');
        $data['subview'] = $this->load->view('admin/tickets/tickets', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public
    function open()
    {
        $data['title'] = 'Open Ticket';
        $data['active'] = 1;
        $data['permission_user'] = $this->tickets_model->all_permission_user('7');
        $data['all_tickets_info'] = $this->tickets_model->get_permission('tbl_tickets');
        $data['ticket_status'] = 'open';
        $data['subview'] = $this->load->view('admin/tickets/tickets', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public
    function in_progress()
    {
        $data['title'] = 'In progress Ticket';
        $data['active'] = 1;
        $data['permission_user'] = $this->tickets_model->all_permission_user('7');
        $data['all_tickets_info'] = $this->tickets_model->get_permission('tbl_tickets');
        $data['ticket_status'] = 'in_progress';
        $data['subview'] = $this->load->view('admin/tickets/tickets', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public
    function delete($action, $id, $replay_id = NULL)
    {
        if ($action == 'delete_ticket_replay') {
            $this->tickets_model->_table_name = 'tbl_tickets_replies';
            $this->tickets_model->_primary_key = 'tickets_replies_id';
            $this->tickets_model->delete($replay_id);
            // messages for user
            redirect('admin/tickets/index/tickets_details/' . $id);
        }
        if ($action == 'delete_tickets') {
            $this->tickets_model->_table_name = 'tbl_tickets_replies';
            $this->tickets_model->delete_multiple(array('tickets_id' => $id));

            $this->tickets_model->_table_name = 'tbl_tickets';
            $this->tickets_model->_primary_key = 'tickets_id';
            $this->tickets_model->delete($id);

            redirect('admin/tickets/index/');
        }
    }

}
