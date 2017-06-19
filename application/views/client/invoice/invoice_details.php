<?= message_box('success') ?>
<?= message_box('error') ?>


<div class="row mb">

    <div class="col-sm-8">
        <?php
        $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');
        $currency = $this->invoice_model->client_currency_sambol($invoice_info->client_id);
        $client_lang = $client_info->language;
        unset($this->lang->is_loaded[5]);
        $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
        ?>
        <?php if ($this->invoice_model->get_invoice_cost($invoice_info->invoices_id) > 0) { ?>
            <a class="btn btn-success"
               href="<?= base_url() ?>client/invoice/manage_invoice/invoice_history/<?= $invoice_info->invoices_id ?>"><?= lang('invoice_history') ?></a>
            <div class="btn-group">
                <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                    <?= lang('pay_invoice') ?>
                    <span class="caret"></span></button>
                <ul class="dropdown-menu animated zoomIn">
                    <?php if ($invoice_info->allow_paypal == 'Yes') {
                        ?>
                        <li><a data-toggle="modal" data-target="#myModal"
                               href="<?= base_url() ?>payment/paypal/pay/<?= $invoice_info->invoices_id ?>"
                               title="<?= lang('paypal') ?>"><?= lang('paypal') ?></a></li>
                        <?php
                    }
                    if ($invoice_info->allow_2checkout == 'Yes') {
                        ?>
                        <li><a data-toggle="modal" data-target="#myModal"
                               href="<?= base_url() ?>payment/checkout/pay/<?= $invoice_info->invoices_id ?>"
                               title="<?= lang('2checkout') ?>"><?= lang('2checkout') ?></a></li>

                    <?php }
                    if ($invoice_info->allow_stripe == 'Yes') { ?>
                        <li><a data-toggle="modal" data-target="#myModal"
                               href="<?= base_url() ?>payment/stripe/pay/<?= $invoice_info->invoices_id ?>"
                               title="<?= lang('stripe') ?>"><?= lang('stripe') ?></a></li>

                    <?php }
                    if ($invoice_info->allow_authorize == 'Yes') { ?>
                        <li><a data-toggle="modal" data-target="#myModal"
                               href="<?= base_url() ?>payment/authorize/pay/<?= $invoice_info->invoices_id ?>"
                               title="<?= lang('authorize') ?>"><?= lang('authorize') ?></a></li>
                    <?php }
                    if ($invoice_info->allow_ccavenue == 'Yes') { ?>
                        <li><a data-toggle="modal" data-target="#myModal"
                               href="<?= base_url() ?>payment/ccavenue/pay/<?= $invoice_info->invoices_id ?>"
                               title="<?= lang('ccavenue') ?>"><?= lang('ccavenue') ?></a></li>
                    <?php }
                    if ($invoice_info->allow_braintree == 'Yes') { ?>
                        <li><a data-toggle="modal" data-target="#myModal"
                               href="<?= base_url() ?>payment/braintree/pay/<?= $invoice_info->invoices_id ?>"
                               title="<?= lang('braintree') ?>"><?= lang('braintree') ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <?php
        if (!empty($invoice_info->project_id)) {
            $project_info = $this->db->where('project_id', $invoice_info->project_id)->get('tbl_project')->row();
            ?>
            <strong><?= lang('project') ?>:</strong>
            <a
                href="<?= base_url() ?>client/project/project_details/<?= $invoice_info->project_id ?>"
                class="">
                <?= $project_info->project_name ?>
            </a>
        <?php } ?>
    </div>
    <div class="col-sm-4 pull-right">
        <a onclick="print_invoice('print_invoice')" href="#" data-toggle="tooltip" data-placement="top" title=""
           data-original-title="Print" class="btn btn-sm btn-danger pull-right">
            <i class="fa fa-print"></i>
        </a>

        <a style="margin-right: 5px"
           href="<?= base_url() ?>client/invoice/manage_invoice/pdf_invoice/<?= $invoice_info->invoices_id ?>"
           data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF"
           class="btn btn-sm btn-success pull-right">
            <i class="fa fa-file-pdf-o"></i>
        </a>
    </div>
</div>

<?php
$payment_status = $this->invoice_model->get_payment_status($invoice_info->invoices_id);
if (strtotime($invoice_info->due_date) < time() AND $payment_status != lang('fully_paid')) {
    ?>
    <div class="alert bg-danger-light hidden-print">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="fa fa-warning"></i>
        <?= lang('invoice_overdue') ?>
    </div>
    <?php
}
?>

<div class="panel" id="print_invoice">
    <div class="show_print ">
        <div class="col-xs-12">
            <h4 class="page-header">
                <img style="width: 60px;width: 60px;margin-top: -10px;margin-right: 10px;"
                     src="<?= base_url() . config_item('invoice_logo') ?>"><?= config_item('company_name') ?>
            </h4>
        </div><!-- /.col -->
    </div>
    <div class="panel-body">

        <h3 class="mt0"><?= $invoice_info->reference_no ?></h3>
        <hr>
        <div class="row mb-lg">
            <div class="col-lg-4 col-xs-6 br pv">
                <div class="row">
                    <div class="col-md-2 text-center visible-md visible-lg">
                        <em class="fa fa-truck fa-4x text-muted"></em>
                    </div>
                    <div class="col-md-10">
                        <h4 class="ml-sm"><?= (config_item('company_legal_name_' . $client_lang) ? config_item('company_legal_name_' . $client_lang) : config_item('company_legal_name')) ?></h4>
                        <address></address><?= (config_item('company_address_' . $client_lang) ? config_item('company_address_' . $client_lang) : config_item('company_address')) ?>
                        <br><?= (config_item('company_city_' . $client_lang) ? config_item('company_city_' . $client_lang) : config_item('company_city')) ?>
                        , <?= config_item('company_zip_code') ?>
                        <br><?= (config_item('company_country_' . $client_lang) ? config_item('company_country_' . $client_lang) : config_item('company_country')) ?>
                        <br/><?= $language_info['phone'] ?> : <?= config_item('company_phone') ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-xs-6 br pv">
                <div class="row">
                    <div class="col-md-2 text-center visible-md visible-lg">
                        <em class="fa fa-plane fa-4x text-muted"></em>
                    </div>

                    <?php
                    if ($client_info->client_status == 1) {
                        $status = lang('person');
                    } else {
                        $status =lang('company');
                    }
                    ?>

                    <div class="col-md-10">
                        <h4><?= $client_info->name . ' ' . $status . ' ' ?></h4>
                        <address></address><?= $client_info->address ?>
                        <br> <?= $client_info->city ?>, <?= $client_info->zipcode ?>
                        <br><?= $client_info->country ?>
                        <br><?= $language_info['phone'] ?>: <?= $client_info->phone ?></div>
                </div>
            </div>
            <div class="clearfix hidden-md hidden-lg">
                <hr>
            </div>
            <div class="col-lg-4 col-xs-12 pv">
                <div class="clearfix">
                    <p class="pull-left">INVOICE NO.</p>
                    <p class="pull-right mr"><?= $invoice_info->reference_no ?></p>
                </div>
                <div class="clearfix">
                    <p class="pull-left"><?= $language_info['invoice_date'] ?></p>
                    <p class="pull-right mr"><?= strftime(config_item('date_format'), strtotime($invoice_info->date_saved)); ?></p>
                </div>
                <div class="clearfix">
                    <p class="pull-left"><?= $language_info['payment_due'] ?></p>
                    <p class="pull-right mr"><?= strftime(config_item('date_format'), strtotime($invoice_info->due_date)); ?></p>
                </div>
                <div class="clearfix">
                    <?php
                    if ($payment_status == lang('fully_paid')) {
                        $label = 'success';
                    } else {
                        $label = 'danger';
                    }
                    ?>
                    <p class="pull-left"><?= $language_info['payment_status'] ?></p>
                    <p class="pull-right mr"><span class="label label-<?= $label ?>"><?= $payment_status ?></span></p>
                </div>
            </div>
        </div>
        <div class="table-responsive table-bordered mb-lg">

            <table class="table">
                <thead>
                <tr>
                    <th class="col-sm-1"><?= $language_info['qty'] ?></th>
                    <th><?= $language_info['item_name'] ?></th>
                    <th><?= $language_info['description'] ?></th>
                    <th><?= $language_info['tax_rate'] ?> </th>
                    <th class="col-sm-2"><?= $language_info['unit_price'] ?></th>
                    <th class="col-sm-1"><?= $language_info['tax'] ?></th>
                    <th><?= $language_info['total'] ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $invoice_items = $this->invoice_model->ordered_items_by_id($invoice_info->invoices_id);

                if (!empty($invoice_items)) :
                    foreach ($invoice_items as $v_item) :
                        $item_name = $v_item->item_name ? $v_item->item_name : $v_item->item_desc;
                        ?>
                        <tr>
                            <td><?= $v_item->quantity ?></td>
                            <td><?= $item_name ?></td>
                            <td><?= nl2br($v_item->item_desc) ?></td>
                            <td><?= $v_item->item_tax_rate ?>%</td>
                            <td><?= display_money($v_item->unit_cost) ?></td>
                            <td><?= display_money($v_item->item_tax_total) ?></td>
                            <td><?= display_money($v_item->total_cost) ?></td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif ?>

                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <p class="well well-sm mt">
                    <?= $invoice_info->notes ?>
                </p>
            </div>
            <div class="col-sm-4 pv">
                <div class="clearfix">
                    <p class="pull-left"><?= $language_info['sub_total'] ?></p>
                    <p class="pull-right mr">
                        <?= display_money($this->invoice_model->calculate_to('invoice_cost', $invoice_info->invoices_id), $currency->symbol); ?>
                    </p>
                </div>
                <?php if ($invoice_info->tax > 0.00): ?>
                    <div class="clearfix">
                        <p class="pull-left"><?= $language_info['tax'] ?> (<?php echo $invoice_info->tax; ?> %)</p>
                        <p class="pull-right mr">
                            <?= display_money($this->invoice_model->calculate_to('tax', $invoice_info->invoices_id), $currency->symbol); ?>
                        </p>
                    </div>
                <?php endif ?>

                <?php if ($invoice_info->discount > 0): ?>
                    <div class="clearfix">
                        <p class="pull-left"><?= $language_info['discount'] ?> (<?php echo $invoice_info->discount; ?>
                            %)</p>
                        <p class="pull-right mr">
                            <?= display_money($this->invoice_model->calculate_to('discount', $invoice_info->invoices_id), $currency->symbol); ?>
                        </p>
                    </div>
                <?php endif ?>

                <?php
                $paid_amount = display_money($this->invoice_model->calculate_to('paid_amount', $invoice_info->invoices_id), $currency->symbol);

                if ($paid_amount > 0.00) {
                    $total = $language_info['total_due'];
                } else {
                    $total = $language_info['total'];
                }
                ?>
                <div class="clearfix">
                    <p class="pull-left h3"><?= $total ?></p>
                    <p class="pull-right mr h3"><?= display_money($this->invoice_model->calculate_to('invoice_due', $invoice_info->invoices_id), $currency->symbol); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function print_invoice(print_invoice) {
        var printContents = document.getElementById(print_invoice).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

