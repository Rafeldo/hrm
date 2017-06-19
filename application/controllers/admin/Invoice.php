<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoice extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_model');

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

    public function manage_invoice($action = NULL, $id = NULL, $item_id = NULL)
    {
        $data['page'] = lang('sales');

        if ($action == 'all_payments') {
            $data['sub_active'] = lang('payments_received');
        } else {
            $data['sub_active'] = lang('invoice');
        }
        if (!empty($item_id)) {
            $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $id));

            if (!empty($can_edit)) {
                $data['item_info'] = $this->invoice_model->check_by(array('items_id' => $item_id), 'tbl_items');
            }
        }
        if (!empty($id)) {
            // get all invoice info by id
            $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $id));

            if (!empty($can_edit)) {
                $data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $id), 'tbl_invoices');
            }

        }
        if ($action == 'create_invoice') {
            $data['active'] = 2;
        } else {
            $data['active'] = 1;
        }
        // get all client
        $this->invoice_model->_table_name = 'tbl_client';
        $this->invoice_model->_order_by = 'client_id';
        $data['all_client'] = $this->invoice_model->get();

        // get permission user
        $data['permission_user'] = $this->invoice_model->all_permission_user('13');
        // get all invoice
        $data['all_invoices_info'] = $this->invoice_model->get_permission('tbl_invoices');

        if ($action == 'invoice_details') {
            $data['title'] = "Invoice Details"; //Page title
            $data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $id), 'tbl_invoices');
            $data['client_info'] = $this->invoice_model->check_by(array('client_id' => $data['invoice_info']->client_id), 'tbl_client');

            $lang = $this->invoice_model->all_files();
            foreach ($lang as $file => $altpath) {
                $shortfile = str_replace("_lang.php", "", $file);
                //CI will record your lang file is loaded, unset it and then you will able to load another
                //unset the lang file to allow the loading of another file
                if (isset($this->lang->is_loaded)) {
                    $loaded = sizeof($this->lang->is_loaded);
                    if ($loaded < 3) {
                        for ($i = 3; $i <= $loaded; $i++) {
                            unset($this->lang->is_loaded[$i]);
                        }
                    } else {
                        for ($i = 0; $i <= $loaded; $i++) {
                            unset($this->lang->is_loaded[$i]);
                        }
                    }
                }
                $data['language_info'] = $this->lang->load($shortfile, $data['client_info']->language, TRUE, TRUE, $altpath);
            }
            $subview = 'invoice_details';
        } elseif ($action == 'payment') {
            $data['title'] = "Invoice Payment"; //Page title      
            $subview = 'payment';
        } elseif ($action == 'payments_details') {
            $data['title'] = "Payments Details"; //Page title      
            $subview = 'payments_details';

            // get payment info by id
            $this->invoice_model->_table_name = 'tbl_payments';
            $this->invoice_model->_order_by = 'payments_id';
            $data['payments_info'] = $this->invoice_model->get_by(array('payments_id' => $id), TRUE);

        } elseif ($action == 'payments_pdf') {
            $data['title'] = "Payments PDF"; //Page title

            // get payment info by id
            $this->invoice_model->_table_name = 'tbl_payments';
            $this->invoice_model->_order_by = 'payments_id';
            $data['payments_info'] = $this->invoice_model->get_by(array('payments_id' => $id), TRUE);
            $this->load->helper('dompdf');
            $viewfile = $this->load->view('admin/invoice/payments_pdf', $data, TRUE);
//            $subview = 'payments_pdf';
            pdf_create($viewfile, 'Payment  # ' . $data['payments_info']->trans_id);


        } elseif ($action == 'invoice_history') {
            $data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $id), 'tbl_invoices');
            $data['title'] = "Invoice History"; //Page title      
            $subview = 'invoice_history';
        } elseif ($action == 'email_invoice') {
            $data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $id), 'tbl_invoices');
            $data['title'] = "Email Invoice"; //Page title
            $subview = 'email_invoice';
            $data['editor'] = $this->data;
        } elseif ($action == 'send_reminder') {
            $data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $id), 'tbl_invoices');
            $data['title'] = "Send Remainder"; //Page title      
            $subview = 'send_reminder';
            $data['editor'] = $this->data;
        } elseif ($action == 'send_overdue') {
            $data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $id), 'tbl_invoices');
            $data['title'] = lang('send_invoice_overdue'); //Page title      
            $subview = 'send_overdue';
            $data['editor'] = $this->data;
        } elseif ($action == 'pdf_invoice') {
            $data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $id), 'tbl_invoices');
            $data['title'] = "Invoice PDF"; //Page title                             
            $this->load->helper('dompdf');
            $viewfile = $this->load->view('admin/invoice/invoice_pdf', $data, TRUE);
            pdf_create($viewfile, 'Invoice  # ' . $data['invoice_info']->reference_no);
        } else {
            $data['title'] = "Manage Invoice"; //Page title      
            $subview = 'manage_invoice';
        }
        $data['subview'] = $this->load->view('admin/invoice/' . $subview, $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }


    public function all_payments($id = NULL)
    {
        if (!empty($id)) {
            $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $id));
            if (!empty($can_edit)) {
                $data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $id), 'tbl_invoices');
            }
            $data['title'] = "Edit Payments"; //Page title
            $subview = 'edit_payments';
        } else {
            $data['title'] = "All Payments"; //Page title      
            $subview = 'all_payments';
        }
        $data['all_invoice_info'] = $this->invoice_model->get_permission('tbl_invoices');

        // get payment info by id

        if (!empty($id)) {
            $can_edit = $this->invoice_model->can_action('tbl_payments', 'edit', array('payments_id' => $id));
            if (!empty($can_edit)) {
                $this->invoice_model->_table_name = 'tbl_payments';
                $this->invoice_model->_order_by = 'payments_id';
                $data['payments_info'] = $this->invoice_model->get_by(array('payments_id' => $id), TRUE);
            } else {
                set_message('error', lang('no_permission_to_access'));
                redirect($_SERVER['HTTP_REFERER']);
            }

        }
        $data['subview'] = $this->load->view('admin/invoice/' . $subview, $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_invoice($id = NULL)
    {

        $data = $this->invoice_model->array_from_post(array('reference_no', 'client_id', 'tax', 'discount'));
        $data['allow_paypal'] = ($this->input->post('allow_paypal') == 'Yes') ? 'Yes' : 'No';
        $data['allow_stripe'] = ($this->input->post('allow_stripe') == 'Yes') ? 'Yes' : 'No';
        $data['allow_2checkout'] = ($this->input->post('allow_2checkout') == 'Yes') ? 'Yes' : 'No';
        $data['allow_authorize'] = ($this->input->post('allow_authorize') == 'Yes') ? 'Yes' : 'No';
        $data['allow_ccavenue'] = ($this->input->post('allow_ccavenue') == 'Yes') ? 'Yes' : 'No';
        $data['allow_braintree'] = ($this->input->post('allow_braintree') == 'Yes') ? 'Yes' : 'No';


        $data['due_date'] = date('Y-m-d', strtotime($this->input->post('due_date', TRUE)));

        $data['notes'] = $this->input->post('notes', TRUE);

        $currency = $this->invoice_model->client_currency_sambol($data['client_id']);
        $data['currency'] = $currency->code;

        $permission = $this->input->post('permission', true);
        if (!empty($permission)) {
            if ($permission == 'everyone') {
                $assigned = 'all';
            } else {
                $assigned_to = $this->invoice_model->array_from_post(array('assigned_to'));
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
        $this->invoice_model->_table_name = 'tbl_invoices';
        $this->invoice_model->_primary_key = 'invoices_id';
        if (!empty($id)) {
            $invoice_id = $id;
            $this->invoice_model->save($data, $id);
            $action = lang('activity_invoice_updated');
            $msg = lang('invoice_updated');

        } else {
            $invoice_id = $this->invoice_model->save($data);
            $action = lang('activity_invoice_created');
            $msg = lang('invoice_created');
        }
        save_custom_field(9, $invoice_id);

        $recuring_frequency = $this->input->post('recuring_frequency', TRUE);

        if (!empty($recuring_frequency) && $recuring_frequency != 'none') {
            $recur_data = $this->invoice_model->array_from_post(array('recur_start_date', 'recur_end_date'));
            $recur_data['recuring_frequency'] = $recuring_frequency;
            $this->get_recuring_frequency($invoice_id, $recur_data); // set recurring
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'invoice',
            'module_field_id' => $invoice_id,
            'activity' => $action,
            'icon' => 'fa-circle-o',
            'value1' => $data['reference_no']
        );
        $this->invoice_model->_table_name = 'tbl_activities';
        $this->invoice_model->_primary_key = 'activities_id';
        $this->invoice_model->save($activity);

        // messages for user
        $type = "success";
        $message = $msg;
        set_message($type, $message);
        redirect('admin/invoice/manage_invoice');
    }

    public function recurring_invoice($id = NULL)
    {
        $data['title'] = lang('recurring_invoice');
        if (!empty($id)) {
            $data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $id), 'tbl_invoices');
            $data['active'] = 2;
        } else {
            $data['active'] = 1;
        }
        // get all client
        $this->invoice_model->_table_name = 'tbl_client';
        $this->invoice_model->_order_by = 'client_id';
        $data['all_client'] = $this->invoice_model->get();
        // get permission user
        $data['permission_user'] = $this->invoice_model->all_permission_user('51');

        // get all invoice
        $data['all_invoices_info'] = $this->invoice_model->get_permission('tbl_invoices');

        $data['subview'] = $this->load->view('admin/invoice/recurring_invoice', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    function get_recuring_frequency($invoices_id, $recur_data)
    {
        $recur_days = $this->get_calculate_recurring_days($recur_data['recuring_frequency']);
        $due_date = $this->invoice_model->get_table_field('tbl_invoices', array('invoices_id' => $invoices_id), 'due_date');

        $next_date = date("Y-m-d", strtotime($due_date . "+ " . $recur_days . " days"));

        if ($recur_data['recur_end_date'] == '') {
            $recur_end_date = '0000-00-00';
        } else {
            $recur_end_date = date('Y-m-d', strtotime($recur_data['recur_end_date']));
        }
        $update_invoice = array(
            'recurring' => 'Yes',
            'recuring_frequency' => $recur_days,
            'recur_frequency' => $recur_data['recuring_frequency'],
            'recur_start_date' => date('Y-m-d', strtotime($recur_data['recur_start_date'])),
            'recur_end_date' => $recur_end_date,
            'recur_next_date' => $next_date
        );
        $this->invoice_model->_table_name = 'tbl_invoices';
        $this->invoice_model->_primary_key = 'invoices_id';
        $this->invoice_model->save($update_invoice, $invoices_id);
        return TRUE;
    }

    function get_calculate_recurring_days($recuring_frequency)
    {
        switch ($recuring_frequency) {
            case '7D':
                return 7;
                break;
            case '1M':
                return 31;
                break;
            case '3M':
                return 90;
                break;
            case '6M':
                return 182;
                break;
            case '1Y':
                return 365;
                break;
        }
    }

    public function stop_recurring($invoices_id)
    {
        $update_recur = array(
            'recurring' => 'No',
            'recur_end_date' => date('Y-m-d'),
            'recur_next_date' => '0000-00-00'
        );
        $this->invoice_model->_table_name = 'tbl_invoices';
        $this->invoice_model->_primary_key = 'invoices_id';
        $this->invoice_model->save($update_recur, $invoices_id);
        // Log Activity
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'invoice',
            'module_field_id' => $invoices_id,
            'activity' => 'activity_recurring_stopped',
            'icon' => 'fa-plus'
        );
        $this->invoice_model->_table_name = 'tbl_activities';
        $this->invoice_model->_primary_key = 'activities_id';
        $this->invoice_model->save($activity);
        // messages for user
        $type = "success";
        $message = lang('recurring_invoice_stopped');
        set_message($type, $message);
        redirect('admin/invoice/manage_invoice');
    }

    public function insert_items($invoices_id)
    {
        $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoices_id));
        if (!empty($can_edit)) {
            $data['invoices_id'] = $invoices_id;
            $data['modal_subview'] = $this->load->view('admin/invoice/_modal_insert_items', $data, FALSE);
            $this->load->view('admin/_layout_modal', $data);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function clone_invoice($invoices_id)
    {
        $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoices_id));
        if (!empty($can_edit)) {
            $data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $invoices_id), 'tbl_invoices');
            // get all client
            $this->invoice_model->_table_name = 'tbl_client';
            $this->invoice_model->_order_by = 'client_id';
            $data['all_client'] = $this->invoice_model->get();

            $data['modal_subview'] = $this->load->view('admin/invoice/_modal_clone_invoice', $data, FALSE);
            $this->load->view('admin/_layout_modal', $data);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function cloned_invoice($id)
    {
        $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $id));
        if (!empty($can_edit)) {
            if (config_item('increment_invoice_number') == 'FALSE') {
                $this->load->helper('string');
                $reference_no = config_item('invoice_prefix') . ' ' . random_string('nozero', 6);
            } else {
                $reference_no = config_item('invoice_prefix') . ' ' . $this->invoice_model->generate_invoice_number();
            }
            $invoice_info = $this->invoice_model->check_by(array('invoices_id' => $id), 'tbl_invoices');
            // save into invoice table
            $new_invoice = array(
                'reference_no' => $reference_no,
                'recur_start_date' => $invoice_info->recur_start_date,
                'recur_end_date' => $invoice_info->recur_end_date,
                'client_id' => $this->input->post('client_id', true),
                'due_date' => $invoice_info->due_date,
                'notes' => $invoice_info->notes,
                'tax' => $invoice_info->tax,
                'discount' => $invoice_info->discount,
                'recurring' => $invoice_info->recurring,
                'recuring_frequency' => $invoice_info->recuring_frequency,
                'recur_frequency' => $invoice_info->recur_frequency,
                'recur_next_date' => $invoice_info->recur_next_date,
                'currency' => $invoice_info->currency,
                'status' => $invoice_info->status,
                'date_saved' => $invoice_info->date_saved,
                'emailed' => $invoice_info->emailed,
                'show_client' => $invoice_info->show_client,
                'viewed' => $invoice_info->viewed,
                'allow_paypal' => $invoice_info->allow_paypal,
                'allow_stripe' => $invoice_info->allow_stripe,
                'allow_2checkout' => $invoice_info->allow_2checkout,
                'allow_authorize' => $invoice_info->allow_authorize,
                'allow_ccavenue' => $invoice_info->allow_ccavenue,
                'allow_braintree' => $invoice_info->allow_braintree,
                'permission' => $invoice_info->permission,
            );

            $this->invoice_model->_table_name = "tbl_invoices"; //table name
            $this->invoice_model->_primary_key = "invoices_id";
            $new_invoice_id = $this->invoice_model->save($new_invoice);

            $invoice_items = $this->db->where('invoices_id', $id)->get('tbl_items')->result();
            if (!empty($invoice_items)) {
                foreach ($invoice_items as $new_item) {
                    $items = array(
                        'invoices_id' => $new_invoice_id,
                        'item_name' => $new_item->item_name,
                        'item_desc' => $new_item->item_desc,
                        'unit_cost' => $new_item->unit_cost,
                        'quantity' => $new_item->quantity,
                        'item_tax_rate' => $new_item->item_tax_rate,
                        'item_tax_total' => $new_item->item_tax_total,
                        'total_cost' => $new_item->total_cost,
                        'item_order' => $new_item->item_order,
                        'date_saved' => $new_item->date_saved,
                    );
                    $this->invoice_model->_table_name = "tbl_items"; //table name
                    $this->invoice_model->_primary_key = "items_id";
                    $this->invoice_model->save($items);
                }
            }


            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'invoice',
                'module_field_id' => $new_invoice_id,
                'activity' => ('activity_invoice_created') . lang('clone') . ' from ' . $invoice_info->reference_no . ' to ',
                'icon' => 'fa-copy',
                'value1' => $reference_no,
            );
            // Update into tbl_project
            $this->invoice_model->_table_name = "tbl_activities"; //table name
            $this->invoice_model->_primary_key = "activities_id";
            $this->invoice_model->save($activities);

            // messages for user
            $type = "success";
            $message = lang('invoice_created');
            set_message($type, $message);
            redirect('admin/invoice/manage_invoice/invoice_details/' . $new_invoice_id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function add_insert_items($invoices_id)
    {
        $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoices_id));
        if (!empty($can_edit)) {
            $saved_items_id = $this->input->post('saved_items_id', TRUE);
            if (!empty($saved_items_id)) {
                foreach ($saved_items_id as $v_items_id) {
                    $items_info = $this->invoice_model->check_by(array('saved_items_id' => $v_items_id), 'tbl_saved_items');

                    $data['quantity'] = $items_info->quantity;
                    $data['invoices_id'] = $invoices_id;
                    $data['item_name'] = $items_info->item_name;
                    $data['item_desc'] = $items_info->item_desc;
                    $data['unit_cost'] = $items_info->unit_cost;
                    $data['item_tax_rate'] = $items_info->item_tax_rate;
                    $data['item_tax_total'] = $items_info->item_tax_total;
                    $data['total_cost'] = $items_info->total_cost;
                    // get all client
                    $this->invoice_model->_table_name = 'tbl_items';
                    $this->invoice_model->_primary_key = 'items_id';
                    $items_id = $this->invoice_model->save($data);
                    $action = lang('activity_invoice_items_added');
                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'invoice',
                        'module_field_id' => $items_id,
                        'activity' => $action,
                        'icon' => 'fa-circle-o',
                        'value1' => $items_info->item_name
                    );
                    $this->invoice_model->_table_name = 'tbl_activities';
                    $this->invoice_model->_primary_key = 'activities_id';
                    $this->invoice_model->save($activity);
                }
                $type = "success";
                $msg = lang('invoice_item_added');
            } else {
                $type = "error";
                $msg = 'please Select an items';
            }
            $message = $msg;
            set_message($type, $message);
            redirect('admin/invoice/manage_invoice/invoice_details/' . $invoices_id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function add_item($id = NULL)
    {

        $data = $this->invoice_model->array_from_post(array('invoices_id', 'item_order'));
        $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $data['invoices_id']));
        if (!empty($can_edit)) {
            $quantity = $this->input->post('quantity', TRUE);
            $array_data = $this->invoice_model->array_from_post(array('item_name', 'item_desc', 'item_tax_rate', 'unit_cost'));
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
                    $this->invoice_model->_table_name = 'tbl_items';
                    $this->invoice_model->_primary_key = 'items_id';
                    if (!empty($id)) {
                        $items_id = $id;
                        $this->invoice_model->save($data, $id);
                        $action = lang('activity_invoice_items_updated');
                        $msg = lang('invoice_item_updated');
                    } else {
                        $items_id = $this->invoice_model->save($data);
                        $action = lang('activity_invoice_items_added');
                        $msg = lang('invoice_item_added');
                    }
                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'invoice',
                        'module_field_id' => $items_id,
                        'activity' => $action,
                        'icon' => 'fa-circle-o',
                        'value1' => $data['item_name']
                    );
                    $this->invoice_model->_table_name = 'tbl_activities';
                    $this->invoice_model->_primary_key = 'activities_id';
                    $this->invoice_model->save($activity);
                }
            }
            $type = "success";
            $message = $msg;
            set_message($type, $message);
            redirect('admin/invoice/manage_invoice/invoice_details/' . $data['invoices_id']);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function change_status($action, $id)
    {
        $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $id));
        if (!empty($can_edit)) {
            $where = array('invoices_id' => $id);
            if ($action == 'hide') {
                $data = array('show_client' => 'No');
            } else {
                $data = array('show_client' => 'Yes');
            }
            $this->invoice_model->set_action($where, $data, 'tbl_invoices');
            // messages for user
            $type = "success";
            $message = lang('invoice_' . $action);
            set_message($type, $message);
            redirect('admin/invoice/manage_invoice/invoice_details/' . $id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function delete($action, $invoices_id, $item_id = NULL)
    {
        $can_delete = $this->invoice_model->can_action('tbl_invoices', 'delete', array('invoices_id' => $invoices_id));
        if (!empty($can_delete)) {
            $invoices_info = $this->invoice_model->check_by(array('invoices_id' => $invoices_id), 'tbl_invoices');

            if ($action == 'delete_item') {
                $this->invoice_model->_table_name = 'tbl_items';
                $this->invoice_model->_primary_key = 'items_id';
                $this->invoice_model->delete($item_id);
            } elseif ($action == 'delete_invoice') {
                $this->invoice_model->_table_name = 'tbl_items';
                $this->invoice_model->delete_multiple(array('invoices_id' => $invoices_id));

                $this->invoice_model->_table_name = 'tbl_payments';
                $this->invoice_model->delete_multiple(array('invoices_id' => $invoices_id));

                $this->invoice_model->_table_name = 'tbl_invoices';
                $this->invoice_model->_primary_key = 'invoices_id';
                $this->invoice_model->delete($invoices_id);
            } elseif ($action == 'delete_payment') {
                $this->invoice_model->_table_name = 'tbl_payments';
                $this->invoice_model->_primary_key = 'payments_id';
                $this->invoice_model->delete($invoices_id);
            }
            $type = "success";
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'invoice',
                'module_field_id' => $invoices_id,
                'activity' => ('activity_invoice' . $action),
                'icon' => 'fa-circle-o',
                'value1' => $invoices_info->reference_no,
            );
            $this->invoice_model->_table_name = 'tbl_activities';
            $this->invoice_model->_primary_key = 'activities_id';
            $this->invoice_model->save($activity);

            if ($action == 'delete_item') {
                $text = lang('invoice_item_deleted');
                set_message($type, $text);
                redirect('admin/invoice/manage_invoice/invoice_details/' . $invoices_id);
            } elseif ($action == 'delete_payment') {
                $text = lang('payment_deleted');
                set_message($type, $text);
                redirect('admin/invoice/manage_invoice/all_payments');
            } else {
                $text = lang('deleted_invoice');
                set_message($type, $text);
                redirect('admin/invoice/manage_invoice');
            }
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function get_payemnt($invoices_id)
    {
        $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoices_id));
        if (!empty($can_edit)) {
            $due = round($this->invoice_model->calculate_to('invoice_due', $invoices_id), 2);

            $paid_amount = $this->input->post('amount', TRUE);

            if ($paid_amount != 0) {
                if ($paid_amount > $due) {
                    // messages for user
                    $type = "error";
                    $message = lang('overpaid_amount');
                    set_message($type, $message);
                    redirect('admin/invoice/manage_invoice/payment/' . $invoices_id);
                } else {
                    $inv_info = $this->invoice_model->check_by(array('invoices_id' => $invoices_id), 'tbl_invoices');
                    $data = array(
                        'invoices_id' => $invoices_id,
                        'paid_by' => $inv_info->client_id,
                        'payment_method' => $this->input->post('payment_method', TRUE),
                        'currency' => $this->input->post('currency', TRUE),
                        'amount' => $paid_amount,
                        'payment_date' => date('Y-m-d', strtotime($this->input->post('payment_date', TRUE))),
                        'trans_id' => $this->input->post('trans_id'),
                        'notes' => $this->input->post('notes'),
                        'month_paid' => date("m", strtotime($this->input->post('payment_date', TRUE))),
                        'year_paid' => date("Y", strtotime($this->input->post('payment_date', TRUE))),
                    );

                    $this->invoice_model->_table_name = 'tbl_payments';
                    $this->invoice_model->_primary_key = 'payments_id';
                    $this->invoice_model->save($data);

                    $currency = $this->invoice_model->client_currency_sambol($inv_info->client_id);
                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'invoice',
                        'module_field_id' => $invoices_id,
                        'activity' => ('activity_new_payment'),
                        'icon' => 'fa-usd',
                        'value1' => display_money($paid_amount, $currency->symbol),
                        'value2' => $inv_info->reference_no,
                    );
                    $this->invoice_model->_table_name = 'tbl_activities';
                    $this->invoice_model->_primary_key = 'activities_id';
                    $this->invoice_model->save($activity);


                    // save into tbl_transaction
                    $tr_data = array(
                        'type' => 'Income',
                        'amount' => $paid_amount,
                        'credit' => $paid_amount,
                        'date' => date('Y-m-d', strtotime($this->input->post('payment_date', TRUE))),
                        'paid_by' => $inv_info->client_id,
                        'payment_methods_id' => $this->input->post('payment_method', TRUE),
                        'reference' => $this->input->post('trans_id'),
                        'notes' => $this->input->post('notes'),
                        'permission' => 'all',
                    );

                    $tr_data['account_id'] = config_item('default_account');
                    if (!empty($tr_data['account_id'])) {
                        $account_id = $tr_data['account_id'];
                    } else {
                        $account_info = $this->db->get('tbl_accounts')->row();
                        $account_id = $account_info->account_id;

                    }
                    if (!empty($account_id)) {

                        $account_info = $this->invoice_model->check_by(array('account_id' => $account_id), 'tbl_accounts');
                        $ac_data['balance'] = $account_info->balance + $tr_data['amount'];
                        $this->invoice_model->_table_name = "tbl_accounts"; //table name
                        $this->invoice_model->_primary_key = "account_id";
                        $this->invoice_model->save($ac_data, $account_info->account_id);

                        $aaccount_info = $this->invoice_model->check_by(array('account_id' => $account_id), 'tbl_accounts');
                        $tr_data['total_balance'] = $aaccount_info->balance;

                        // save into tbl_transaction
                        $this->invoice_model->_table_name = "tbl_transactions"; //table name
                        $this->invoice_model->_primary_key = "transactions_id";
                        $return_id = $this->invoice_model->save($tr_data);

                        // save into activities
                        $activities = array(
                            'user' => $this->session->userdata('user_id'),
                            'module' => 'transactions',
                            'module_field_id' => $return_id,
                            'activity' => 'activity_new_deposit',
                            'icon' => 'fa-coffee',
                            'value1' => $account_info->account_name,
                            'value2' => $paid_amount,
                        );
                        // Update into tbl_project
                        $this->invoice_model->_table_name = "tbl_activities"; //table name
                        $this->invoice_model->_primary_key = "activities_id";
                        $this->invoice_model->save($activities);

                    }

                    if ($this->input->post('send_thank_you') == 'on') {
                        $this->send_payment_email($invoices_id, $paid_amount); //send thank you email
                    }
                }
            }
            // messages for user
            $type = "success";
            $message = lang('generate_payment');
            set_message($type, $message);
            redirect('admin/invoice/manage_invoice/invoice_details/' . $invoices_id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public
    function update_payemnt($payments_id)
    {
        $data = array(
            'amount' => $this->input->post('amount', TRUE),
            'payment_method' => $this->input->post('payment_method', TRUE),
            'payment_date' => date('Y-m-d', strtotime($this->input->post('payment_date', TRUE))),
            'notes' => $this->input->post('notes', TRUE),
            'month_paid' => date("m", strtotime($this->input->post('payment_date', TRUE))),
            'year_paid' => date("Y", strtotime($this->input->post('payment_date', TRUE))),
        );
        $this->invoice_model->_table_name = 'tbl_payments';
        $this->invoice_model->_primary_key = 'payments_id';
        $this->invoice_model->save($data, $payments_id);

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'invoice',
            'module_field_id' => $payments_id,
            'activity' => ('activity_update_payment'),
            'icon' => 'fa-usd',
            'value1' => $data['amount'],
            'value2' => $data['payment_date'],
        );
        $this->invoice_model->_table_name = 'tbl_activities';
        $this->invoice_model->_primary_key = 'activities_id';
        $this->invoice_model->save($activity);

        // messages for user
        $type = "success";
        $message = lang('generate_payment');
        set_message($type, $message);
        redirect('admin/invoice/all_payments');

    }

    public
    function send_payment($invoices_id, $paid_amount)
    {
        $this->send_payment_email($invoices_id, $paid_amount); //send email
        $type = "success";
        $message = lang('payment_information_send');
        set_message($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    function send_payment_email($invoices_id, $paid_amount)
    {
        $email_template = $this->invoice_model->check_by(array('email_group' => 'payment_email'), 'tbl_email_templates');
        $message = $email_template->template_body;
        $subject = $email_template->subject;

        $inv_info = $this->invoice_model->check_by(array('invoices_id' => $invoices_id), 'tbl_invoices');
        $currency = $inv_info->currency;
        $reference = $inv_info->reference_no;

        $invoice_currency = str_replace("{INVOICE_CURRENCY}", $currency, $message);
        $reference = str_replace("{INVOICE_REF}", $reference, $invoice_currency);
        $amount = str_replace("{PAID_AMOUNT}", $paid_amount, $reference);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $amount);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);
        $client_info = $this->invoice_model->check_by(array('client_id' => $inv_info->client_id), 'tbl_client');

        $address = $client_info->email;

        $params['recipient'] = $address;

        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'invoice',
            'module_field_id' => $invoices_id,
            'activity' => ('activity_send_payment'),
            'icon' => 'fa-usd',
            'value1' => $reference,
            'value2' => $currency . ' ' . $amount,
        );
        $this->invoice_model->_table_name = 'tbl_activities';
        $this->invoice_model->_primary_key = 'activities_id';
        $this->invoice_model->save($activity);

        $this->invoice_model->send_email($params);
    }

    public
    function send_invoice_email($invoice_id)
    {

        $ref = $this->input->post('ref', TRUE);
        $subject = $this->input->post('subject', TRUE);
        $message = $this->input->post('message', TRUE);

        $client_name = str_replace("{CLIENT}", $this->input->post('client_name', TRUE), $message);
        $Ref = str_replace("{REF}", $ref, $client_name);
        $Amount = str_replace("{AMOUNT}", $this->input->post('amount'), $Ref);
        $Currency = str_replace("{CURRENCY}", $this->input->post('currency', TRUE), $Amount);
        $Due_date = str_replace("{DUE_DATE}", $this->input->post('due_date', TRUE), $Currency);
        if (!empty($Due_date)) {
            $Due_date = $Due_date;
        } else {
            $Due_date = $Currency;
        }
        $link = str_replace("{INVOICE_LINK}", base_url() . 'client/invoice/manage_invoice/invoice_details/' . $invoice_id, $Due_date);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $link);

        $this->send_email_invoice($invoice_id, $message, $subject); // Email Invoice

        $data = array('emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));

        $this->invoice_model->_table_name = 'tbl_invoices';
        $this->invoice_model->_primary_key = 'invoices_id';
        $this->invoice_model->save($data, $invoice_id);

        // Log Activity
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'invoice',
            'module_field_id' => $invoice_id,
            'activity' => ('activity_invoice_sent'),
            'icon' => 'fa-envelope',
            'value1' => $ref,
            'value2' => $this->input->post('currency', TRUE) . ' ' . $this->input->post('amount'),
        );
        $this->invoice_model->_table_name = 'tbl_activities';
        $this->invoice_model->_primary_key = 'activities_id';
        $this->invoice_model->save($activity);
        // messages for user
        $type = "success";
        $imessage = lang('invoice_sent');
        set_message($type, $imessage);
        redirect('admin/invoice/manage_invoice/invoice_details/' . $invoice_id);
    }

    function send_email_invoice($invoice_id, $message, $subject)
    {
        $invoice_info = $this->invoice_model->check_by(array('invoices_id' => $invoice_id), 'tbl_invoices');
        $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');

        $recipient = $client_info->email;

        $data['message'] = $message;

        $message = $this->load->view('email_template', $data, TRUE);
        $params = array(
            'recipient' => $recipient,
            'subject' => $subject,
            'message' => $message
        );
        $params['resourceed_file'] = '';
        $this->invoice_model->send_email($params);

    }

    function invoice_email($invoice_id)
    {

        $data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $invoice_id), 'tbl_invoices');

        $data['title'] = "Invoice PDF"; //Page title
        $message = $this->load->view('admin/invoice/invoice_pdf', $data, TRUE);

        $client_info = $this->invoice_model->check_by(array('client_id' => $data['invoice_info']->client_id), 'tbl_client');

        $recipient = $client_info->email;

        $data['message'] = $message;

        $message = $this->load->view('email_template', $data, TRUE);

        $params = array(
            'recipient' => $recipient,
            'subject' => '[ ' . config_item('company_name') . ' ]' . ' New Invoice' . ' ' . $data['invoice_info']->reference_no,
            'message' => $message
        );
        $params['resourceed_file'] = '';

        $this->invoice_model->send_email($params);

        $data = array('emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));

        $this->invoice_model->_table_name = 'tbl_invoices';
        $this->invoice_model->_primary_key = 'invoices_id';
        $invoice_id = $this->invoice_model->save($data, $invoice_id);

        $data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $invoice_id), 'tbl_invoices');
        // Log Activity
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'invoice',
            'module_field_id' => $invoice_id,
            'activity' => ('activity_invoice_sent'),
            'icon' => 'fa-envelope',
            'value1' => $data['invoice_info']->reference_no,
        );

        $this->invoice_model->_table_name = 'tbl_activities';
        $this->invoice_model->_primary_key = 'activities_id';
        $this->invoice_model->save($activity);
        // messages for user
        $type = "success";
        $imessage = lang('invoice_sent');
        set_message($type, $imessage);
        redirect('admin/invoice/manage_invoice/invoice_details/' . $invoice_id);

    }

    public
    function tax_rates($action = NULL, $id = NULL)
    {

        $data['page'] = lang('sales');
        $data['sub_active'] = lang('tax_rates');
        if ($action == 'edit_tax_rates') {
            $data['active'] = 2;
            if (!empty($id)) {
                $can_edit = $this->invoice_model->can_action('tbl_tax_rates', 'edit', array('tax_rates_id' => $id));
                if (!empty($can_edit)) {
                    $data['tax_rates_info'] = $this->invoice_model->check_by(array('tax_rates_id' => $id), 'tbl_tax_rates');
                }
            }
        } else {
            $data['active'] = 1;
        }
        if ($action == 'delete_tax_rates') {
            $can_delete = $this->invoice_model->can_action('tbl_tax_rates', 'delete', array('tax_rates_id' => $id));
            if (!empty($can_delete)) {
                $data['tax_rates_info'] = $this->invoice_model->check_by(array('tax_rates_id' => $id), 'tbl_tax_rates');

                // Log Activity
                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'invoice',
                    'module_field_id' => $id,
                    'activity' => ('activity_taxt_rate_deleted'),
                    'icon' => 'fa-circle-o',
                    'value1' => $data['tax_rates_info']->tax_rate_name,
                );
                $this->invoice_model->_table_name = 'tbl_activities';
                $this->invoice_model->_primary_key = 'activities_id';
                $this->invoice_model->save($activity);

                $this->invoice_model->_table_name = 'tbl_tax_rates';
                $this->invoice_model->_primary_key = 'tax_rates_id';
                $this->invoice_model->delete($id);
                // messages for user
                $type = "success";
                $message = lang('tax_deleted');
                set_message($type, $message);
                redirect('admin/invoice/tax_rates');
            } else {
                set_message('error', lang('there_in_no_value'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $data['title'] = "Tax Rates Info"; //Page title
            $subview = 'tax_rates';
        }
        // get permission user
        $data['permission_user'] = $this->invoice_model->all_permission_user('16');

        // get all invoice
        $data['all_tax_rates'] = $this->invoice_model->get_permission('tbl_invoices');


        $data['subview'] = $this->load->view('admin/invoice/' . $subview, $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public
    function save_tax_rate($id = NULL)
    {
        $data = $this->invoice_model->array_from_post(array('tax_rate_name', 'tax_rate_percent'));
        $permission = $this->input->post('permission', true);
        if (!empty($permission)) {
            if ($permission == 'everyone') {
                $assigned = 'all';
            } else {
                $assigned_to = $this->invoice_model->array_from_post(array('assigned_to'));
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

        $this->invoice_model->_table_name = 'tbl_tax_rates';
        $this->invoice_model->_primary_key = 'tax_rates_id';
        $id = $this->invoice_model->save($data, $id);

        // Log Activity
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'invoice',
            'module_field_id' => $id,
            'activity' => ('activity_taxt_rate_add'),
            'icon' => 'fa-circle-o',
            'value1' => $data['tax_rate_name'],
        );
        $this->invoice_model->_table_name = 'tbl_activities';
        $this->invoice_model->_primary_key = 'activities_id';
        $this->invoice_model->save($activity);

        // messages for user
        $type = "success";
        $message = lang('tax_added');
        set_message($type, $message);
        redirect('admin/invoice/tax_rates');
    }

}
