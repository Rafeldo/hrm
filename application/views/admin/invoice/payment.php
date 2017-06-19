<div class="row">
    <div class="col-sm-3">
        <div class="panel panel-custom">
            <div class="panel-heading">
                <?php
                if ($this->session->userdata('user_type') == '1') {
                    ?>
                    <a style="margin-top: -5px;" href="<?= base_url() ?>admin/invoice/manage_invoice/create_invoice"
                       data-original-title="<?= lang('new_invoice') ?>" data-toggle="tooltip" data-placement="top"
                       class="btn btn-icon btn-<?= config_item('button_color') ?> btn-sm pull-right"><i
                            class="fa fa-plus"></i></a>
                <?php } ?>
                <?= lang('all_invoices') ?>
            </div>

            <div class="panel-body">
                <section class="scrollable  ">
                    <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0"
                         data-size="5px" data-color="#333333">
                        <ul class="nav"><?php

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
                                    <li class="<?php
                                    if ($v_invoices->invoices_id == $this->uri->segment(5)) {
                                        echo "active";
                                    }
                                    ?>">
                                        <?php
                                        $client_info = $this->invoice_model->check_by(array('client_id' => $v_invoices->client_id), 'tbl_client');
                                        ?>
                                        <a href="<?= base_url() ?>admin/invoice/manage_invoice/payment/<?= $v_invoices->invoices_id ?>">
                                            <?= $client_info->name ?>
                                            <div class="pull-right">
                                                <?php $currency = $this->invoice_model->client_currency_sambol($v_invoices->client_id); ?>
                                                <?= display_money($this->invoice_model->get_invoice_cost($v_invoices->invoices_id), $currency->symbol); ?>
                                            </div>
                                            <br>
                                            <small class="block small text-muted"><?= $v_invoices->reference_no ?> <span
                                                    class="label label-<?= $label ?>"><?= $invoice_status ?></span>
                                            </small>
                                        </a></li>
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
            <header class="panel-heading"><?= lang('pay_invoice') ?></header>
            <div class="panel-body">
                <form method="post"
                      action="<?= base_url() ?>admin/invoice/get_payemnt/<?= $invoice_info->invoices_id ?>"
                      class="form-horizontal">
                    <?php $currency = $this->invoice_model->client_currency_sambol($v_invoices->client_id); ?>
                    <input type="hidden" name="currency" value="<?= $currency->symbol ?>">

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('trans_id') ?> <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <?php $this->load->helper('string'); ?>
                            <input type="text" class="form-control" value="<?= random_string('nozero', 6); ?>"
                                   name="trans_id" readonly>
                        </div>
                    </div>
                    <div class="form-group">

                        <label class="col-lg-3 control-label"><?= lang('amount') ?> (<?= $currency->symbol ?>) <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" required="" class="form-control"
                                   value="<?= round($this->invoice_model->calculate_to('invoice_due', $invoice_info->invoices_id), 2) ?>"
                                   name="amount">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('payment_date') ?></label>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <input type="text" required="" name="payment_date" class="form-control datepicker"
                                       value="<?php
                                       if (!empty($payment_info->payment_date)) {
                                           echo $payment_info->payment_date;
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
                        <label class="col-lg-3 control-label"><?= lang('payment_method') ?> <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <select name="payment_method" required="" class="form-control">
                                <?php
                                $payment_methods = $this->db->get('tbl_payment_methods')->result();
                                if (!empty($payment_methods)) {
                                    foreach ($payment_methods as $p_method) {
                                        ?>
                                        <option
                                            value="<?= $p_method->payment_methods_id ?>"><?= $p_method->method_name ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('notes') ?></label>
                        <div class="col-lg-6">
                            <textarea name="notes" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('send_email') ?></label>
                        <div class="col-lg-6">
                            <div class="checkbox c-checkbox">
                                <label>
                                    <input type="checkbox" class="custom-checkbox" name="send_thank_you">
                                    <span class="fa fa-check"></span></label>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3"></label>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-primary"><?= lang('add_payment') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </section>
</div>

<!-- end -->






