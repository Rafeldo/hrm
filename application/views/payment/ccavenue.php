<?php
$cur = $this->invoice_model->check_by(array('code' => $invoice_info['currency']), 'tbl_currencies');
?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title">Paying <strong><?= display_money($invoice_info['amount'], $cur->symbol); ?></strong> for
            Invoice # <?= $invoice_info['item_name'] ?> via CCAvenue</h4>
    </div>
    <div class="modal-body">

        <?php
        $attributes = array('id' => 'ccavenue', 'class' => 'form-horizontal');
        echo form_open('https://www.ccavenue.com/shopzone/cc_details.jsp', $attributes);
        ?>
        <p><strong>Are you sure to paid by CCAvenue </strong></p>

        <input type="hidden" name="invoice_id" value="<?= $invoice_info['item_number'] ?>">
        <input type="hidden" name="amount" value="<?= ($invoice_info['amount']) ?>">


        <input type=hidden name="Merchant_Id" value="<?= $this->config->item('ccavenue_merchant_id') ?>">
        <input type="hidden" name="Currency" value="<?= $invoice_info['currency'] ?>">
        <input type="hidden" name="Amount" value="<?= ($invoice_info['amount']) ?>">
        <input type="hidden" name="Order_Id" value="<?= $invoice_info['item_name'] ?>">
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></a>
            <button type="submit" class="btn btn-success" id="submitBtn">Process Payment</button>
        </div>
        </form>

    </div>
</div>