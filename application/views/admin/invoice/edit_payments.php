<div class="row">
    <div class="col-sm-3">
        <div class="panel panel-custom">
            <div class="panel-heading">
                <?= lang('all_payments') ?>
            </div>

            <div class="panel-body">
                <section class="scrollable  ">
                    <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0"
                         data-size="5px" data-color="#333333">
                        <ul class="nav"><?php
                            if (!empty($all_payments_info)) {
                                foreach ($all_payments_info as $v_payments_info) {
                                    $invoice_info = $this->invoice_model->check_by(array('invoices_id' => $v_payments_info->invoices_id), 'tbl_invoices');
                                    $client_info = $this->invoice_model->check_by(array('client_id' => $v_payments_info->paid_by), 'tbl_client');
                                    ?>
                                    <li class="<?= ($v_payments_info->payments_id == $this->uri->segment(4) ? 'active' : '') ?>">
                                        <a href="<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?= $v_payments_info->payments_id ?>">
                                            <?= ucfirst($client_info->name) ?>
                                            <div class="pull-right">
                                                <?php
                                                $currency = $this->invoice_model->client_currency_sambol($invoice_info->client_id);
                                                ?>
                                                <?= display_money($v_payments_info->amount, $currency->symbol); ?>
                                            </div>
                                            <br>
                                            <small class="block small text-info"><?= $v_payments_info->trans_id ?>
                                                | <?= strftime(config_item('date_format'), strtotime($v_payments_info->created_date)); ?> </small>

                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>

                    </div>
                </section>
            </div>
        </div>
    </div>
    <section class="col-sm-9">
        <?= message_box('error') ?>
        <!-- Start create invoice -->
        <section class="panel panel-custom">

            <header class="panel-heading  "><?= lang('payment_details') ?> -
                TRANS <?= $payments_info->trans_id ?></header>
            <div class="panel-body">


                <form method="post" action="<?= base_url() ?>admin/invoice/update_payemnt/<?php
                if (!empty($payments_info)) {
                    echo $payments_info->payments_id;
                }
                ?>" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('amount') ?> <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <input type="text" required="" class="form-control" value="<?= $payments_info->amount ?>"
                                   name="amount">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('payment_method') ?> <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <select name="payment_method" required="" class="form-control">
                                <?php
                                $payment_methods = $this->db->get('tbl_payment_methods')->result();
                                if (!empty($payment_methods)) {
                                    foreach ($payment_methods as $p_method) {
                                        ?>
                                        <option
                                            value="<?= $p_method->payment_methods_id ?>"<?= ($payments_info->payment_method == $p_method->payment_methods_id ? ' selected="selected"' : '') ?>><?= $p_method->method_name ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('payment_date') ?></label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input type="text" required="" name="payment_date" class="form-control datepicker"
                                       value="<?php
                                       if (!empty($payments_info->payment_date)) {
                                           echo $payments_info->payment_date;
                                       } else {
                                           echo date('Y-m-d');
                                       }
                                       ?>" data-date-format="<?= config_item('date_picker_format'); ?>">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('notes') ?> </label>
                        <div class="col-lg-7">
                            <textarea name="notes" class="textarea form-control"><?= $payments_info->notes ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"></label>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-sm btn-primary"> <?= lang('save_changes') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </section>
</div>

<!-- end -->






