<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CCAvenue extends Client_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('invoice_model');
    }

    function pay($invoice_id = NULL) {
        $invoice_info = $this->invoice_model->check_by(array('invoices_id' => $invoice_id), 'tbl_invoices');

        $invoice_due = $this->invoice_model->calculate_to('invoice_due', $invoice_id);
        if ($invoice_due <= 0) {
            $invoice_due = 0.00;
        }
        $data['invoice_info'] = array(
            'item_name' => $invoice_info->reference_no,
            'item_number' => $invoice_id,
            'currency' => $invoice_info->currency,
            'amount' => $invoice_due);
        $data['working_key'] = $this->config->item('ccavenue_key');

        $data['subview'] = $this->load->view('payment/ccavenue', $data, FALSE);
        $this->load->view('client/_layout_modal', $data);
    }

    function process() {

        if ($this->input->post()) {
            $errors = array();
            $invoice_id = $this->input->post('invoice_id');
            if (!isset($_POST['token'])) {
                $errors['token'] = 'The order cannot be processed. Please make sure you have JavaScript enabled and try again.';
            }
            // If no errors, process the order:
            if (empty($errors)) {

                require_once('./' . APPPATH . 'libraries/2checkout/Twocheckout.php');

                Twocheckout::privateKey(config_item('2checkout_private_key'));
                Twocheckout::sellerId(config_item('2checkout_seller_id'));
                Twocheckout::sandbox(false);
                $user_info = $this->invoice_model->check_by(array('user_id' => $this->session->userdata('user_id')), 'tbl_users');
                $invoice_info = $this->invoice_model->check_by(array('invoices_id' => $invoice_id), 'tbl_invoices');
                $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');

                try {

                    $charge = Twocheckout_Charge::auth(array(
                                "merchantOrderId" => $invoice_info->invoices_id,
                                "token" => $this->input->post('token'),
                                "currency" => $invoice_info->currency,
                                "total" => $this->input->post('amount'),
                                "billingAddr" => array(
                                    "name" => $client_info->name,
                                    "addrLine1" => $client_info->address,
                                    "city" => $client_info->city,
                                    "country" => $client_info->country,
                                    "email" => $client_info->email,
                                    "phoneNumber" => $client_info->phone
                                )
                    ));


                    if ($charge['response']['responseCode'] == 'APPROVED') {
                        $transaction = array(
                            'invoices_id' => $charge['response']['merchantOrderId'],
                            'paid_by' => $client_info->client_id,
                            'payer_email' => $charge['response']['billingAddr']['email'],
                            'payment_method' => '1',
                            'notes' => 'Paid by ' . $user_info->username,
                            'amount' => $charge['response']['total'],
                            'trans_id' => $charge['response']['transactionId'],
                            'month_paid' => date('m'),
                            'year_paid' => date('Y'),
                            'payment_date' => date('d-m-Y H:i:s')
                        );

                        $this->invoice_model->_table_name = 'tbl_payments';
                        $this->invoice_model->_primary_key = 'payments_id';
                        $this->invoice_model->save($transaction);

                        $currency = $this->invoice_model->client_currency_sambol($client_info->client_id);
                        $activity = array(
                            'user' => $this->session->userdata('user_id'),
                            'module' => 'invoice',
                            'module_field_id' => $invoice_info->invoices_id,
                            'activity' => 'activity_new_payment',
                            'icon' => 'fa-usd',
                            'value1' => $currency->symbol . ' ' . $charge['response']['total'],
                            'value2' => $invoice_info->reference_no,
                        );
                        $this->invoice_model->_table_name = 'tbl_activities';
                        $this->invoice_model->_primary_key = 'activities_id';
                        $this->invoice_model->save($activity);
                    }                    

                    $this->notify_to_client($invoice_id, $invoice_info->reference_no); // Send email to client
                } catch (Twocheckout_Error $e) {
                    $type = 'error';
                    $message = 'Payment declined with error: ' . $e->getMessage();
                    set_message($type, $message);
                    redirect('client/invoice/manage_invoice/invoice_details/' . $invoice_info->invoices_id);
                }
            }
        }
    }

   
}

////end 