<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= lang('new_payment') ?></h4>
    </div>
    <div class="modal-body">
        <p><?= lang('paypal_redirection_alert') ?></p>

        <?php
        $attributes = array('name' => 'paypal_form', 'class' => 'bs-example form-horizontal');
        echo form_open($paypal_url, $attributes);
        $cur = $this->invoice_model->check_by(array('code' => $invoice_info['currency']), 'tbl_currencies');
        ?>
        <input name="rm" value="2" type="hidden">
        <input name="cmd" value="_xclick" type="hidden">
        <input name="currency_code" value="<?= $invoice_info['currency'] ?>" type="hidden">
        <input name="quantity" value="1" type="hidden">
        <input name="business" value="<?= $this->config->item('paypal_email') ?>" type="hidden">
        <input name="return" value="<?= base_url() ?><?= $this->config->item('paypal_success_url') ?>" type="hidden">
        <input name="cancel_return" value="<?= base_url() ?><?= $this->config->item('paypal_cancel_url') ?>"
               type="hidden">
        <input name="notify_url" value="<?= base_url() ?><?= $this->config->item('paypal_ipn_url') ?>" type="hidden">
        <input name="custom" value="<?= $this->session->userdata('client_id') ?>" type="hidden">
        <input name="item_name" value="<?= $invoice_info['item_name'] ?>" type="hidden">
        <input name="item_number" value="<?= $invoice_info['item_number'] ?>" type="hidden">
        <input name="amount" value="<?= ($invoice_info['amount']) ?>" type="hidden">
        <div class="form-group">
            <label class="col-lg-4 control-label"><?= lang('reference_no') ?></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" readonly value="<?= $invoice_info['item_name'] ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label"><?= lang('amount') ?> ( <?= $cur->symbol ?>) </label>
            <div class="col-lg-4">
                <input type="text" class="form-control" value="<?= display_money($invoice_info['amount']) ?>" readonly>
            </div>
        </div>
        <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></a>
            <button type="submit" class="btn btn-success"><?= lang('pay_invoice') ?></button>
        </div>
    </div>

    </form>
</div>
<!-- /.modal-content -->