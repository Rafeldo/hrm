<?php

class Invoice_Model extends MY_Model {

    public $_table_name;
    public $_order_by;
    public $_primary_key;

    public function get_payment_status($invoice_id) {

        $tax = $this->get_invoice_tax_amount($invoice_id);

        $discount = $this->get_invoice_discount($invoice_id);
        $invoice_cost = $this->get_invoice_cost($invoice_id);
        $payment_made = round($this->get_invoice_paid_amount($invoice_id), 2);
        $due = round(((($invoice_cost - $discount) + $tax) - $payment_made));
        if ($payment_made < 1) {
            return lang('not_paid');
        } elseif ($due <= 0) {
            return lang('fully_paid');
        } else {
            return lang('partially_paid');
        }
    }

    public function invoice_perc($invoice) {
        $invoice_payment = $this->invoice_payment($invoice);
        $invoice_payable = $this->invoice_payable($invoice);
        if ($invoice_payable < 1 OR $invoice_payment < 1) {
            $perc_paid = 0;
        } else {
            $perc_paid = ($invoice_payment / $invoice_payable) * 100;
        }
        return round($perc_paid);
    }

    public function invoice_payment($invoice) {
        $this->ci->db->where('invoice', $invoice);
        $this->ci->db->select_sum('amount');
        $query = $this->ci->db->get('payments');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->amount;
        }
    }

    function ordered_items_by_id($id, $type = 'invoices') {
        $table = ($type == 'invoices' ? '' : 'estimate_') . 'tbl_items';
        $result = $this->db->where($type . '_id', $id)->order_by('item_order', 'asc')->get($table)->result();
        return $result;
    }

    function calculate_to($invoice_value, $invoice_id) {
        switch ($invoice_value) {
            case 'invoice_cost':
                return $this->get_invoice_cost($invoice_id);
                break;
            case 'tax':
                return $this->get_invoice_tax_amount($invoice_id);
                break;
            case 'discount':
                return $this->get_invoice_discount($invoice_id);
                break;
            case 'paid_amount':
                return $this->get_invoice_paid_amount($invoice_id);
                break;
            case 'invoice_due':
                return $this->get_invoice_due_amount($invoice_id);
                break;
        }
    }

    function get_invoice_cost($invoice_id) {
        $this->db->select_sum('total_cost');
        $this->db->where('invoices_id', $invoice_id);
        $this->db->from('tbl_items');
        $query_result = $this->db->get();
        $cost = $query_result->row();
        if (!empty($cost->total_cost)) {
            $result = $cost->total_cost;
        } else {
            $result = '0';
        }
        return $result;
    }


    public function get_invoice_tax_amount($invoice_id) {
        $invoice_cost = $this->get_invoice_cost($invoice_id);
        $invoice_info = $this->check_by(array('invoices_id' => $invoice_id), 'tbl_invoices');
        $tax = $invoice_info->tax;
        return ($tax / 100) * $invoice_cost;
    }

    public function get_invoice_discount($invoice_id) {
        $invoice_cost = $this->get_invoice_cost($invoice_id);
        $invoice_info = $this->check_by(array('invoices_id' => $invoice_id), 'tbl_invoices');
        $discount = $invoice_info->discount;
        return ($discount / 100) * $invoice_cost;
    }

    public function get_invoice_paid_amount($invoice_id) {

        $this->db->select_sum('amount');
        $this->db->where('invoices_id', $invoice_id);
        $this->db->from('tbl_payments');
        $query_result = $this->db->get();
        $amount = $query_result->row();
        if (!empty($amount->amount)) {
            $result = $amount->amount;
        } else {
            $result = '0';
        }
        return $result;
    }

    public function get_invoice_due_amount($invoice_id) {

        $tax = $this->get_invoice_tax_amount($invoice_id);
        $discount = $this->get_invoice_discount($invoice_id);
        $invoice_cost = $this->get_invoice_cost($invoice_id);
        $payment_made = $this->get_invoice_paid_amount($invoice_id);
        $due_amount = (($invoice_cost - $discount) + $tax) - $payment_made;
        if ($due_amount <= 0) {
            $due_amount = 0;
        }
        return $due_amount;
    }

    function all_invoice_amount() {
        $invoices = $this->db->get('tbl_invoices')->result();
        $cost[] = array();
        foreach ($invoices as $invoice) {
            $tax = round($this->get_invoice_tax_amount($invoice->invoices_id));
            $discount = round($this->get_invoice_discount($invoice->invoices_id));
            $invoice_cost = round($this->get_invoice_cost($invoice->invoices_id));

            $cost[] = ($invoice_cost + $tax) - $discount;
        }
        if (is_array($cost)) {
            return round(array_sum($cost), 2);
        } else {
            return 0;
        }
    }

    function all_outstanding() {
        $invoices = $this->db->get('tbl_invoices')->result();
        $due[] = array();
        foreach ($invoices as $invoice) {
            $due[] = $this->get_invoice_due_amount($invoice->invoices_id);
        }
        if (is_array($due)) {
            return round(array_sum($due), 2);
        } else {
            return 0;
        }
    }

    function client_outstanding($client_id) {
        $due[] = array();
        $invoices_info = $this->db->where('client_id', $client_id)->get('tbl_invoices')->result();

        foreach ($invoices_info as $v_invoice) {
            $due[] = $this->get_invoice_due_amount($v_invoice->invoices_id);
        }
        if (is_array($due)) {
            return round(array_sum($due), 2);
        } else {
            return 0;
        }
    }

}
