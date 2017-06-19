<?php
$cur = $this->invoice_model->check_by(array('code' => $invoice_info['currency']), 'tbl_currencies');
?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title">Paying <strong>
                <?= display_money($invoice_info['amount'], $cur->symbol); ?>
            </strong> for Invoice # <?= $invoice_info['item_name'] ?> via Braintree</h4>
    </div>
    <div class="modal-body">

        <?php
        $attributes = array('id' => '2checkout', 'class' => 'form-horizontal');
        echo form_open('payment/braintree/process', $attributes);
        ?>

        <?php
        // Show PHP errors, if they exist:
        if (isset($errors) && !empty($errors) && is_array($errors)) {
            echo '<div class="alert alert-error"><h4>Error!</h4>The following error(s) occurred:<ul>';
            foreach ($errors as $e) {
                echo "<li>$e</li>";
            }
            echo '</ul></div>';
        }
        ?>

        <div id="payment-errors"></div>
        <input type="hidden" name="invoice_id" value="<?= $invoice_info['item_number'] ?>">
        <input type="hidden" name="ref" value="<?= $invoice_info['item_name'] ?>">
        <input type="hidden" name="amount" value="<?= ($invoice_info['amount']) ?>">


        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></a>
            <button type="submit" class="btn btn-success" id="submitBtn">Process Payment</button>
        </div>
        </form>


    </div>


</div>
<!-- /.modal-content -->