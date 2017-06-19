<?= message_box('success'); ?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('all_invoices') ?></h3>
    </div>
    <div class="table-responsive">
        <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th><?= lang('invoice') ?></th>
                <th class="col-date"><?= lang('due_date') ?></th>
                <th class="col-currency"><?= lang('amount') ?></th>
                <th class="col-currency"><?= lang('due_amount') ?></th>
                <th><?= lang('status') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $all_invoices_info = $this->db->where(array('client_id' => $this->session->userdata('client_id')))->get('tbl_invoices')->result();
            if (!empty($all_invoices_info)) {
                foreach ($all_invoices_info as $v_invoices) {

                    if ($this->invoice_model->get_payment_status($v_invoices->invoices_id) == lang('fully_paid')) {
                        $invoice_status = lang('fully_paid');
                        $label = "success";
                    } elseif ($v_invoices->emailed == 'Yes') {
                        $invoice_status = lang('sent');
                        $label = "info";
                    } else {
                        $invoice_status = lang('draft');
                        $label = "default";
                    }
                    ?>
                    <tr>


                        <td><a class="text-info"
                               href="<?= base_url() ?>client/invoice/manage_invoice/invoice_details/<?= $v_invoices->invoices_id ?>"><?= $v_invoices->reference_no ?></a>
                        </td>
                        <td><?= strftime(config_item('date_format'), strtotime($v_invoices->due_date)) ?></td>
                        <?php $currency = $this->invoice_model->client_currency_sambol($v_invoices->client_id); ?>
                        <td><?= display_money($this->invoice_model->calculate_to('invoice_cost', $v_invoices->invoices_id), $currency->symbol) ?></td>
                        <td><?= display_money($this->invoice_model->calculate_to('invoice_due', $v_invoices->invoices_id), $currency->symbol) ?></td>
                        <td><span class="label label-<?= $label ?>"><?= $invoice_status ?></span>
                            <?php if ($v_invoices->recurring == 'Yes') { ?>
                                <span class="label label-primary"><i class="fa fa-retweet"></i></span>
                            <?php } ?>

                        </td>


                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>