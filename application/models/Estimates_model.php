<?php

class Estimates_Model extends MY_Model {

    public $_table_name;
    public $_order_by;
    public $_primary_key;

    function estimate_calculation($estimate_value, $estimates_id) {
        switch ($estimate_value) {
            case 'estimate_cost':
                return $this->get_estimate_cost($estimates_id);
                break;
            case 'tax':
                return $this->get_estimate_tax_amount($estimates_id);
                break;
            case 'discount':
                return $this->get_estimate_discount($estimates_id);
                break;
            case 'estimate_amount':
                return $this->get_estimate_amount($estimates_id);
                break;
        }
    }

    function get_estimate_cost($estimates_id) {
        $this->db->select_sum('total_cost');
        $this->db->where('estimates_id', $estimates_id);
        $this->db->from('tbl_estimate_items');
        $query_result = $this->db->get();
        $cost = $query_result->row();
        if (!empty($cost->total_cost)) {
            $result = $cost->total_cost;
        } else {
            $result = '0';
        }
        return $result;
    }

    function get_estimate_tax_amount($estimates_id) {
        $estimate_cost = $this->get_estimate_cost($estimates_id);
        $invoice_info = $this->check_by(array('estimates_id' => $estimates_id), 'tbl_estimates');
        $tax = $invoice_info->tax;
        return ($tax / 100) * $estimate_cost;
    }

    function get_estimate_discount($estimates_id) {
        $estimate_cost = $this->get_estimate_cost($estimates_id);
        $invoice_info = $this->check_by(array('estimates_id' => $estimates_id), 'tbl_estimates');
        $discount = $invoice_info->discount;
        return ($discount / 100) * $estimate_cost;
    }

    function get_estimate_amount($estimates_id) {

        $tax = $this->get_estimate_tax_amount($estimates_id);
        $discount = $this->get_estimate_discount($estimates_id);
        $estimate_cost = $this->get_estimate_cost($estimates_id);
        return (($estimate_cost - $discount) + $tax);
    }

    function ordered_items_by_id($id) {
        $result = $this->db->where('estimates_id', $id)->order_by('item_order', 'asc')->get('tbl_estimate_items')->result();
        return $result;
    }

    public function generate_invoice_number() {

        $query = $this->db->select_max('invoices_id')->get('tbl_invoices');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $next_number = ++$row->invoices_id;
            $next_number = $this->reference_no_exists($next_number);
            $next_number = sprintf('%04d', $next_number);
            return $next_number;
        } else {
            return sprintf('%04d', config_item('invoice_start_no'));
        }
    }

    public function reference_no_exists($next_number) {
        $next_number = sprintf('%04d', $next_number);

        $records = $this->db->where('reference_no', config_item('invoice_prefix') . $next_number)->get('tbl_invoices')->num_rows();
        if ($records > 0) {
            return $this->_ref_no_exists($next_number + 1);
        } else {
            return $next_number;
        }
    }

}
