<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Estimates extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('estimates_model');

        $this->load->helper('ckeditor');
        $this->data['ckeditor'] = array(
            'id' => 'ck_editor',
            'path' => 'asset/js/ckeditor',
            'config' => array(
                'toolbar' => "Full",
                'width' => "99.8%",
                'height' => "400px"
            )
        );
    }

    public function index($action = NULL, $id = NULL, $item_id = NULL)
    {

        $data['page'] = lang('sales');
        $data['sub_active'] = lang('estimates');
        if (!empty($item_id)) {
            $can_edit = $this->estimates_model->can_action('tbl_estimates', 'edit', array('estimates_id' => $id));
            if (!empty($can_edit)) {
                $data['item_info'] = $this->estimates_model->check_by(array('estimate_items_id' => $item_id), 'tbl_estimate_items');
            }
        }
        if (!empty($id)) {
            $can_edit = $this->estimates_model->can_action('tbl_estimates', 'edit', array('estimates_id' => $id));
            if (!empty($can_edit)) {
                // get all estimates info by id
                $data['estimates_info'] = $this->estimates_model->check_by(array('estimates_id' => $id), 'tbl_estimates');
            }
        }
        if ($action == 'edit_estimates') {
            $data['active'] = 2;
        } else {
            $data['active'] = 1;
        }
        // get all client
        $this->estimates_model->_table_name = 'tbl_client';
        $this->estimates_model->_order_by = 'client_id';
        $data['all_client'] = $this->estimates_model->get();
        // get permission user
        $data['permission_user'] = $this->estimates_model->all_permission_user('14');
        // get all estimate
        $data['all_estimates_info'] = $this->estimates_model->get_permission('tbl_estimates');

        if ($action == 'estimates_details') {
            $data['title'] = "Estimates Details"; //Page title
            $data['estimates_info'] = $this->estimates_model->check_by(array('estimates_id' => $id), 'tbl_estimates');
            $subview = 'estimates_details';
        } elseif ($action == 'estimates_history') {
            $data['estimates_info'] = $this->estimates_model->check_by(array('estimates_id' => $id), 'tbl_estimates');
            $data['title'] = "Estimates History"; //Page title      
            $subview = 'estimates_history';
        } elseif ($action == 'email_estimates') {
            $data['estimates_info'] = $this->estimates_model->check_by(array('estimates_id' => $id), 'tbl_estimates');
            $data['title'] = "Email Estimates"; //Page title      
            $subview = 'email_estimates';
            $data['editor'] = $this->data;
        } elseif ($action == 'pdf_estimates') {
            $data['estimates_info'] = $this->estimates_model->check_by(array('estimates_id' => $id), 'tbl_estimates');
            $data['title'] = "Estimates PDF"; //Page title                             
            $this->load->helper('dompdf');
            $viewfile = $this->load->view('admin/estimates/estimates_pdf', $data, TRUE);
            pdf_create($viewfile, 'Estimates  # ' . $data['estimates_info']->reference_no);
        } else {
            $data['title'] = "Estimates"; //Page title      
            $subview = 'estimates';
        }
        $data['subview'] = $this->load->view('admin/estimates/' . $subview, $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_estimates($id = NULL)
    {

        $data = $this->estimates_model->array_from_post(array('reference_no', 'client_id', 'tax', 'discount'));

        $data['due_date'] = date('Y-m-d', strtotime($this->input->post('due_date', TRUE)));

        $data['notes'] = $this->input->post('notes', TRUE);

        $currency = $this->estimates_model->client_currency_sambol($data['client_id']);
        $data['currency'] = $currency->code;

        $permission = $this->input->post('permission', true);
        if (!empty($permission)) {
            if ($permission == 'everyone') {
                $assigned = 'all';
            } else {
                $assigned_to = $this->estimates_model->array_from_post(array('assigned_to'));
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

        // get all client
        $this->estimates_model->_table_name = 'tbl_estimates';
        $this->estimates_model->_primary_key = 'estimates_id';
        if (!empty($id)) {
            $estimates_id = $id;
            $this->estimates_model->save($data, $id);
            $action = ('activity_estimates_updated');
            $msg = lang('estimate_updated');
        } else {
            $estimates_id = $this->estimates_model->save($data);
            $action = ('activity_estimates_created');
            $msg = lang('estimate_created');
        }
        save_custom_field(10, $estimates_id);

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'estimates',
            'module_field_id' => $estimates_id,
            'activity' => $action,
            'icon' => 'fa-circle-o',
            'value1' => $data['reference_no']
        );
        $this->estimates_model->_table_name = 'tbl_activities';
        $this->estimates_model->_primary_key = 'activities_id';
        $this->estimates_model->save($activity);

        // messages for user
        $type = "success";
        $message = $msg;
        set_message($type, $message);
        redirect('admin/estimates');
    }

    public function insert_items($estimates_id)
    {
        $can_edit = $this->estimates_model->can_action('tbl_estimates', 'edit', array('estimates_id' => $estimates_id));
        if (!empty($can_edit)) {
            $data['estimates_id'] = $estimates_id;
            $data['modal_subview'] = $this->load->view('admin/estimates/_modal_insert_items', $data, FALSE);
            $this->load->view('admin/_layout_modal', $data);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function add_insert_items($estimates_id)
    {
        $can_edit = $this->estimates_model->can_action('tbl_estimates', 'edit', array('estimates_id' => $estimates_id));
        if (!empty($can_edit)) {
            $saved_items_id = $this->input->post('saved_items_id', TRUE);
            if (!empty($saved_items_id)) {
                foreach ($saved_items_id as $v_items_id) {
                    $items_info = $this->estimates_model->check_by(array('saved_items_id' => $v_items_id), 'tbl_saved_items');

                    $data['quantity'] = $items_info->quantity;
                    $data['estimates_id'] = $estimates_id;
                    $data['item_name'] = $items_info->item_name;
                    $data['item_desc'] = $items_info->item_desc;
                    $data['unit_cost'] = $items_info->unit_cost;
                    $data['item_tax_rate'] = $items_info->item_tax_rate;
                    $data['item_tax_total'] = $items_info->item_tax_total;
                    $data['total_cost'] = $items_info->total_cost;
                    // get all client
                    $this->estimates_model->_table_name = 'tbl_estimate_items';
                    $this->estimates_model->_primary_key = 'estimate_items_id';
                    $items_id = $this->estimates_model->save($data);
                    $action = 'activity_invoice_items_added';
                    $msg = lang('estimate_item_save');
                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'estimates',
                        'module_field_id' => $items_id,
                        'activity' => $action,
                        'icon' => 'fa-circle-o',
                        'value1' => $items_info->item_name
                    );
                    $this->estimates_model->_table_name = 'tbl_activities';
                    $this->estimates_model->_primary_key = 'activities_id';
                    $this->estimates_model->save($activity);
                }
                $type = "success";
            } else {
                $type = "error";
                $msg = 'Please Select a items';
            }
            $message = $msg;
            set_message($type, $message);
            redirect('admin/estimates/index/estimates_details/' . $estimates_id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function add_item($id = NULL)
    {

        $data = $this->estimates_model->array_from_post(array('estimates_id', 'item_order'));
        $can_edit = $this->estimates_model->can_action('tbl_estimates', 'edit', array('estimates_id' => $data['estimates_id']));
        if (!empty($can_edit)) {
            $quantity = $this->input->post('quantity', TRUE);
            $array_data = $this->estimates_model->array_from_post(array('item_name', 'item_desc', 'item_tax_rate', 'unit_cost'));
            if (!empty($quantity)) {
                foreach ($quantity as $key => $value) {
                    $data['quantity'] = $value;
                    $data['item_name'] = $array_data['item_name'][$key];
                    $data['item_desc'] = $array_data['item_desc'][$key];
                    $data['unit_cost'] = $array_data['unit_cost'][$key];
                    $data['item_tax_rate'] = $array_data['item_tax_rate'][$key];
                    $sub_total = $data['unit_cost'] * $data['quantity'];

                    $data['item_tax_total'] = ($data['item_tax_rate'] / 100) * $sub_total;
                    $data['total_cost'] = $sub_total + $data['item_tax_total'];


                    // get all client
                    $this->estimates_model->_table_name = 'tbl_estimate_items';
                    $this->estimates_model->_primary_key = 'estimate_items_id';
                    if (!empty($id)) {
                        $estimate_items_id = $id;
                        $this->estimates_model->save($data, $id);
                        $action = ('activity_estimates_items_updated');
                    } else {
                        $estimate_items_id = $this->estimates_model->save($data);
                        $action = 'activity_estimates_items_added';
                    }
                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'estimates',
                        'module_field_id' => $estimate_items_id,
                        'activity' => $action,
                        'icon' => 'fa-circle-o',
                        'value1' => $data['item_name']
                    );
                    $this->estimates_model->_table_name = 'tbl_activities';
                    $this->estimates_model->_primary_key = 'activities_id';
                    $this->estimates_model->save($activity);
                }
            }
            // messages for user
            $type = "success";
            $message = lang('estimate_item_save');
            set_message($type, $message);
            redirect('admin/estimates/index/estimates_details/' . $data['estimates_id']);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function change_status($action, $id)
    {
        $can_edit = $this->estimates_model->can_action('tbl_estimates', 'edit', array('estimates_id' => $id));
        if (!empty($can_edit)) {
            $where = array('estimates_id' => $id);
            if ($action == 'hide') {
                $data = array('show_client' => 'No');
            } elseif ($action == 'declined') {
                $data = array('status' => 'Declined');
            } elseif ($action == 'accepted') {
                $data = array('status' => 'Accepted');
            } else {
                $data = array('show_client' => 'Yes');
            }
            $this->estimates_model->set_action($where, $data, 'tbl_estimates');
            // messages for user
            $type = "success";
            $message = lang('estimate_' . $action);
            set_message($type, $message);
            redirect('admin/estimates/index/estimates_details/' . $id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public
    function delete($action, $estimates_id, $item_id = NULL)
    {
        $can_delete = $this->estimates_model->can_action('tbl_estimates', 'delete', array('estimates_id' => $estimates_id));
        if (!empty($can_delete)) {
            if ($action == 'delete_item') {
                $this->estimates_model->_table_name = 'tbl_estimate_items';
                $this->estimates_model->_primary_key = 'estimate_items_id';
                $this->estimates_model->delete($item_id);
            } elseif ($action == 'delete_estimates') {

                $this->estimates_model->_table_name = 'tbl_estimate_items';
                $this->estimates_model->delete_multiple(array('estimates_id' => $estimates_id));

                $this->estimates_model->_table_name = 'tbl_estimates';
                $this->estimates_model->_primary_key = 'estimates_id';
                $this->estimates_model->delete($estimates_id);
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'estimates',
                'module_field_id' => $estimates_id,
                'activity' => ('activity_' . $action),
                'icon' => 'fa-circle-o',
                'value1' => $action
            );

            $this->estimates_model->_table_name = 'tbl_activities';
            $this->estimates_model->_primary_key = 'activities_id';
            $this->estimates_model->save($activity);
            $type = 'success';

            if ($action == 'delete_item') {
                $text = lang('estimate_item_deleted');
                set_message($type, $text);
                redirect('admin/estimates/index/estimates_details/' . $estimates_id);
            } else {
                $text = lang('estimate_deleted');
                set_message($type, $text);
                redirect('admin/estimates');
            }
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function send_estimates_email($estimates_id)
    {

        $ref = $this->input->post('ref', TRUE);
        $subject = $this->input->post('subject', TRUE);
        $message = $this->input->post('message', TRUE);

        $client_name = str_replace("{CLIENT}", $this->input->post('client_name', TRUE), $message);
        $Ref = str_replace("{ESTIMATE_REF}", $ref, $client_name);
        $Amount = str_replace("{AMOUNT}", $this->input->post('amount'), $Ref);
        $Currency = str_replace("{CURRENCY}", $this->input->post('currency', TRUE), $Amount);
        $link = str_replace("{ESTIMATE_LINK}", base_url() . 'admin/estimates/index/estimates_details/' . $estimates_id, $Currency);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $link);


        $this->send_email_estimates($estimates_id, $message, $subject); // Email estimates

        $data = array('emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));

        $this->estimates_model->_table_name = 'tbl_estimates';
        $this->estimates_model->_primary_key = 'estimates_id';
        $this->estimates_model->save($data, $estimates_id);

        // Log Activity
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'estimates',
            'module_field_id' => $estimates_id,
            'activity' => 'activity_estimates_sent',
            'icon' => 'fa-envelope',
            'value1' => $ref
        );
        $this->estimates_model->_table_name = 'tbl_activities';
        $this->estimates_model->_primary_key = 'activities_id';
        $this->estimates_model->save($activity);

        $type = 'success';
        $text = lang('estimate_email_sent');
        set_message($type, $text);
        redirect('admin/estimates/index/estimates_details/' . $estimates_id);
    }

    function send_email_estimates($estimates_id, $message, $subject)
    {
        $estimates_info = $this->estimates_model->check_by(array('estimates_id' => $estimates_id), 'tbl_estimates');
        $client_info = $this->estimates_model->check_by(array('client_id' => $estimates_info->client_id), 'tbl_client');

        $recipient = $client_info->email;

        $data['message'] = $message;

        $message = $this->load->view('email_template', $data, TRUE);
        $params = array(
            'recipient' => $recipient,
            'subject' => $subject,
            'message' => $message
        );
        $params['resourceed_file'] = '';
        $this->estimates_model->send_email($params);
    }

    function estimate_email($estimates_id)
    {
        $data['estimates_info'] = $this->estimates_model->check_by(array('estimates_id' => $estimates_id), 'tbl_estimates');

        $client_info = $this->estimates_model->check_by(array('client_id' => $data['estimates_info']->client_id), 'tbl_client');

        $recipient = $client_info->email;

        $message = $this->load->view('admin/estimates/estimates_pdf', $data, TRUE);

        $data['message'] = $message;

        $message = $this->load->view('email_template', $data, TRUE);
        $params = array(
            'recipient' => $recipient,
            'subject' => '[ ' . config_item('company_name') . ' ]' . ' New Invoice' . ' ' . $data['estimates_info']->reference_no,
            'message' => $message
        );
        $params['resourceed_file'] = '';

        $this->estimates_model->send_email($params);

        $data = array('emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));

        $this->estimates_model->_table_name = 'tbl_estimates';
        $this->estimates_model->_primary_key = 'estimates_id';
        $this->estimates_model->save($data, $estimates_id);

        // Log Activity
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'estimates',
            'module_field_id' => $estimates_id,
            'activity' => 'activity_estimates_sent',
            'icon' => 'fa-envelope',
            'value1' => $data['estimates_info']->reference_no
        );
        $this->estimates_model->_table_name = 'tbl_activities';
        $this->estimates_model->_primary_key = 'activities_id';
        $this->estimates_model->save($activity);

        $type = 'success';
        $text = lang('estimate_email_sent');
        set_message($type, $text);
        redirect('admin/estimates/index/estimates_details/' . $estimates_id);
    }

    public
    function convert_to_invoice($id)
    {
        $can_edit = $this->estimates_model->can_action('tbl_estimates', 'edit', array('estimates_id' => $id));
        if (!empty($can_edit)) {
            $estimates_info = $this->estimates_model->check_by(array('estimates_id' => $id), 'tbl_estimates');

            $ref = config_item('invoice_prefix') . filter_var($estimates_info->reference_no, FILTER_SANITIZE_NUMBER_INT);
            if (config_item('increment_invoice_number') == 'TRUE') {
                $ref = config_item('invoice_prefix') . $this->estimates_model->generate_invoice_number();
            }
            $invoice_data = array(
                'reference_no' => $ref,
                'client_id' => $estimates_info->client_id,
                'currency' => $estimates_info->currency,
                'due_date' => $estimates_info->due_date,
                'notes' => $estimates_info->notes,
                'tax' => $estimates_info->tax,
            );

            $this->estimates_model->_table_name = 'tbl_invoices';
            $this->estimates_model->_primary_key = 'invoices_id';
            $invoice_id = $this->estimates_model->save($invoice_data);


            $this->estimates_model->_table_name = 'tbl_estimate_items';
            $this->estimates_model->_order_by = 'estimates_id';
            $estimate_items = $this->estimates_model->get_by(array('estimates_id' => $id), FALSE);

            if (!empty($estimate_items)) {
                foreach ($estimate_items as $v_est_item) {
                    $items_data = array(
                        'invoices_id' => $invoice_id,
                        'item_name' => $v_est_item->item_name,
                        'item_desc' => $v_est_item->item_desc,
                        'unit_cost' => $v_est_item->unit_cost,
                        'quantity' => $v_est_item->quantity,
                        'total_cost' => $v_est_item->total_cost,
                    );
                    $this->estimates_model->_table_name = 'tbl_items';
                    $this->estimates_model->_primary_key = 'items_id';
                    $this->estimates_model->save($items_data);
                }
            }

            // Log Activity
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'estimates',
                'module_field_id' => $id,
                'activity' => 'activity_estimate_convert_to_invoice',
                'icon' => 'fa-laptop',
                'value1' => $ref
            );
            $this->estimates_model->_table_name = 'tbl_activities';
            $this->estimates_model->_primary_key = 'activities_id';
            $this->estimates_model->save($activity);

            $data = array('invoiced' => 'Yes');

            $this->estimates_model->_table_name = 'tbl_estimates';
            $this->estimates_model->_primary_key = 'estimates_id';
            $this->estimates_model->save($data, $id);

            $type = 'success';
            $message = lang('estimate_invoiced');
            set_message($type, $message);
            redirect('admin/estimates/index/estimates_details/' . $id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

}
