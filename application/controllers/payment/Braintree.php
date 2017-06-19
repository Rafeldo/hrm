<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Braintree extends Client_Controller {

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

        $data['subview'] = $this->load->view('payment/braintree', $data, FALSE);
        $this->load->view('client/_layout_modal', $data);
    }

    function process() {

        if ($this->input->post()) {
            $invoice_id = $this->input->post('invoice_id');

// If no errors, process the order:            

            require_once('./' . APPPATH . 'libraries/braintree_lib/Braintree.php');

            Configuration::environment($this->config->item('braintree_live_or_sandbox'));
            Configuration::merchantId($this->config->item('braintree_merchant_id'));
            Configuration::publicKey($this->config->item('braintree_public_key'));
            Configuration::privateKey($this->config->item('braintree_private_key'));

            $result = Transaction::sale([
                        'amount' => $_POST['amount'],
                        'paymentMethodNonce' => 'nonceFromTheClient',
                        'options' => [ 'submitForSettlement' => true]
            ]);
            $user_info = $this->invoice_model->check_by(array('user_id' => $this->session->userdata('user_id')), 'tbl_users');
            $invoice_info = $this->invoice_model->check_by(array('invoices_id' => $invoice_id), 'tbl_invoices');
            $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');
            if ($result->success) {
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
                // messages for user
                $type = "success";
                $message = 'Paid Succeesfully';
            } else if ($result->transaction) {
                $type = "error";
                $message = 'Error processing transaction:' . "\n  code: " . $result->transaction->processorResponseCode . "\n  text: " . $result->transaction->processorResponseText;
            } else {
                $type = "success";
                $message = 'Validation errors: \n' . $result->errors->deepAll();
            }
            set_message($type, $message);
            redirect('client/invoice/manage_invoice/invoice_details/' . $invoice_id);
        }
    }

}

////end 