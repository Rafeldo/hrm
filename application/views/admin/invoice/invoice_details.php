<?= message_box('success') ?>
<?= message_box('error') ?>

<div class="row mb">

    <div class="col-sm-10">

        <?php

        $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');
        $currency = $this->invoice_model->client_currency_sambol($invoice_info->client_id);
        $client_lang = $client_info->language;
        unset($this->lang->is_loaded[5]);
        $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
        ?>

        <?php $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoice_info->invoices_id));
        if (!empty($can_edit)) { ?>
            <a data-toggle="modal" data-target="#myModal"
               href="<?= base_url() ?>admin/invoice/insert_items/<?= $invoice_info->invoices_id ?>"
               title="<?= lang('item_quick_add') ?>" class="btn btn-sm btn-primary">
                <i class="fa fa-list-alt text-white"></i> <?= lang('from_items') ?></a>
        <?php }
        ?>
        <?php $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoice_info->invoices_id));
        if (!empty($can_edit)) { ?>
            <?php if ($invoice_info->show_client == 'Yes') { ?>
            <a class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top"
               href="<?= base_url() ?>admin/invoice/change_status/hide/<?= $invoice_info->invoices_id ?>"
               title="<?= lang('hide_to_client') ?>"><i class="fa fa-eye-slash"></i> <?= lang('hide_to_client') ?>
                </a><?php } else { ?>
            <a class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top"
               href="<?= base_url() ?>admin/invoice/change_status/show/<?= $invoice_info->invoices_id ?>"
               title="<?= lang('show_to_client') ?>"><i class="fa fa-eye"></i> <?= lang('show_to_client') ?>
                </a><?php }
        } ?>

        <?php $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoice_info->invoices_id));
        if (!empty($can_edit)) { ?>
            <?php if ($this->invoice_model->get_invoice_cost($invoice_info->invoices_id) > 0) { ?>
                <a class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top"
                   href="<?= base_url() ?>admin/invoice/manage_invoice/payment/<?= $invoice_info->invoices_id ?>"
                   title="<?= lang('add_payment') ?>"><i class="fa fa-credit-card"></i> <?= lang('pay_invoice') ?>
                </a>
                <?php
            }
        }
        ?>
        <?php $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoice_info->invoices_id));
        if (!empty($can_edit)) { ?>
            <span data-toggle="tooltip" data-placement="top" title="<?= lang('clone') . ' ' . lang('invoice') ?>">
            <a data-toggle="modal" data-target="#myModal"
               href="<?= base_url() ?>admin/invoice/clone_invoice/<?= $invoice_info->invoices_id ?>"
               class="btn btn-sm btn-purple">
                <i class="fa fa-copy"></i> <?= lang('clone') ?></a>
            </span>
            <?php
        }
        ?>

        <div class="btn-group">
            <button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                <?= lang('more_actions') ?>
                <span class="caret"></span></button>
            <ul class="dropdown-menu animated zoomIn">
                <?php if ($this->invoice_model->get_invoice_cost($invoice_info->invoices_id) > 0) { ?>
                    <li>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice/email_invoice/<?= $invoice_info->invoices_id ?>"
                           title="<?= lang('email_invoice') ?>"><?= lang('email_invoice') ?></a>
                    </li>

                    <li>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice/send_reminder/<?= $invoice_info->invoices_id ?>"
                           title="<?= lang('send_reminder') ?>"><?= lang('send_reminder') ?></a>
                    </li>
                    <li>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice/send_overdue/<?= $invoice_info->invoices_id ?>"
                           title="<?= lang('send_invoice_overdue') ?>"><?= lang('send_invoice_overdue') ?></a>
                    </li>
                    <li>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_history/<?= $invoice_info->invoices_id ?>"><?= lang('invoice_history') ?></a>
                    </li>
                <?php } ?>


                <li class="divider"></li>
                <?php $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoice_info->invoices_id));
                if (!empty($can_edit)) { ?>
                    <li>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice/create_invoice/<?= $invoice_info->invoices_id ?>"><?= lang('edit_invoice') ?></a>
                    </li>
                <?php } ?>
                <?php $can_delete = $this->invoice_model->can_action('tbl_invoices', 'delete', array('invoices_id' => $invoice_info->invoices_id));
                if (!empty($can_delete)) { ?>
                    <li>
                        <a href="<?= base_url() ?>admin/invoice/delete/delete_invoice/<?= $invoice_info->invoices_id ?>"><?= lang('delete_invoice') ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <?php $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoice_info->invoices_id));
        if (!empty($can_edit)) { ?>
            <?php if ($invoice_info->recurring == 'Yes') { ?>
                <a onclick="return confirm('<?= lang('stop_recurring_alert') ?>')" class="btn btn-sm btn-warning"
                   href="<?= base_url() ?>admin/invoice/stop_recurring/<?= $invoice_info->invoices_id ?>"
                   title="<?= lang('stop_recurring') ?>"><i class="fa fa-retweet"></i> <?= lang('stop_recurring') ?>
                </a>
            <?php }
        } ?>

    </div>
    <div class="col-sm-2 pull-right">

        <a
            href="<?= base_url() ?>admin/invoice/invoice_email/<?= $invoice_info->invoices_id ?>"
            data-toggle="tooltip" data-placement="top" title="<?= lang('send_email') ?>"
            class="btn btn-xs btn-primary pull-right">
            <i class="fa fa-envelope-o"></i>
        </a>
        <a onclick="print_invoice('print_invoice')" href="#" data-toggle="tooltip" data-placement="top" title=""
           data-original-title="Print" class="mr-sm btn btn-xs btn-danger pull-right">
            <i class="fa fa-print"></i>
        </a>
        <a style="margin-right: 5px"
           href="<?= base_url() ?>admin/invoice/manage_invoice/pdf_invoice/<?= $invoice_info->invoices_id ?>"
           data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF"
           class="btn btn-xs btn-success pull-right">
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
        <?php $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoice_info->invoices_id));
        if (!empty($can_edit)) { ?>
            <a href="<?= base_url() ?>admin/invoice/manage_invoice/create_invoice/<?= $invoice_info->invoices_id ?>"
               class="pull-right btn btn-primary btn-sm"><?= $language_info['edit_invoice'] ?></a>
        <?php } ?>
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
                        $status = lang('company');
                    }
                    ?>

                    <div class="col-md-10">
                        <h4><?= $client_info->name . ' ' . $status . '' ?></h4>
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
                <?php $show_custom_fields = custom_form_label(9, $invoice_info->invoices_id);

                if (!empty($show_custom_fields)) {
                    foreach ($show_custom_fields as $c_label => $v_fields) {
                        if (!empty($v_fields)) {
                            ?>
                            <div class="clearfix">
                                <p class="pull-left"><?= $c_label ?></p>
                                <p class="pull-right mr"><?= $v_fields ?></p>

                            </div>
                        <?php }
                    }
                }
                ?>
            </div>
        </div>
        <div class="table-responsive table-bordered mb-lg">
            <form method="post" action="<?= base_url() ?>admin/invoice/add_item/<?php
            if (!empty($item_info)) {
                echo $item_info->items_id;
            }
            ?>">
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
                        <th class="col-sm-1 hidden-print"><?= $language_info['action'] ?></th>
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
                                <td class="hidden-print">
                                    <?php $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoice_info->invoices_id));
                                    if (!empty($can_edit)) { ?>
                                        <?= btn_edit('admin/invoice/manage_invoice/invoice_details/' . $v_item->invoices_id . '/' . $v_item->items_id) ?>
                                        <?= btn_delete('admin/invoice/delete/delete_item/' . $v_item->invoices_id . '/' . $v_item->items_id) ?>
                                    <?php }
                                    ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif ?>
                    <?php $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoice_info->invoices_id));
                    if (!empty($can_edit)) { ?>
                        <?php if ($invoice_info->status != 'Paid') { ?>

                            <tr class="hidden-print">
                                <input type="hidden" name="invoices_id"
                                       value="<?= $invoice_info->invoices_id ?>">
                                <input type="hidden" name="item_order" value="<?= count($invoice_items) + 1 ?>">
                                <td><input type="text" name="quantity[]" value="<?php
                                    if (!empty($item_info)) {
                                        echo $item_info->quantity;
                                    }
                                    ?>" placeholder="1" required class="form-control"></td>
                                <td><input type="text" name="item_name[]" value="<?php
                                    if (!empty($item_info)) {
                                        echo $item_info->item_name;
                                    }
                                    ?>" required placeholder="Item Name" class="form-control"></td>
                                <td><textarea rows="1" name="item_desc[]" placeholder="Item Description"
                                              class="form-control"><?php
                                        if (!empty($item_info)) {
                                            echo $item_info->item_desc;
                                        }
                                        ?></textarea></td>
                                <td>
                                    <select name="item_tax_rate[]" class="form-control  ">
                                        <option value="0.00"><?= lang('none') ?></option>
                                        <?php
                                        $tax_rates = $this->db->get('tbl_tax_rates')->result();
                                        if (!empty($tax_rates)) {
                                            foreach ($tax_rates as $v_tax) {
                                                ?>
                                                <option value="<?= $v_tax->tax_rate_percent ?>" <?php
                                                if (!empty($item_info) && $item_info->item_tax_rate == $v_tax->tax_rate_percent) {
                                                    echo 'selected';
                                                }
                                                ?>><?= $v_tax->tax_rate_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td><input type="text" name="unit_cost[]" value="<?php
                                    if (!empty($item_info)) {
                                        echo $item_info->unit_cost;
                                    }
                                    ?>" required placeholder="100" class="form-control"></td>
                                <td><input type="text" value="<?php
                                    if (!empty($item_info)) {
                                        echo $item_info->item_tax_total;
                                    }
                                    ?>" name="tax" placeholder="0.00" readonly="" class="form-control"></td>

                                <td></td>

                                <td>
                                    <strong><a href="javascript:void(0);" id="add_more" class="addCF "><i
                                                class="fa fa-plus"></i>&nbsp;&nbsp;More</a></strong>
                                </td>
                            </tr>
                            <table class="table" id="add_new">

                            </table>
                        <?php } ?>
                    <?php } ?>
                    <div class="hidden-print pull-right mt mb">
                        <td colspan="8" style="text-align: right;">
                            <button type="submit" class="btn btn-primary"><?= lang('save') ?></button>
                        </td>
                    </div>
                    </tbody>
                </table>
            </form>
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
<script type="text/javascript">
    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more").click(function () {
            if (maxAppend >= 4) {
                alert("Maximum 5 File is allowed");
            } else {
                var add_new = $('<tr style=""><td class="col-sm-1"><input type="text" name="quantity[]" placeholder="1" required class="form-control"></td>">\n\
                    <td><input type="text" name="item_name[]" required  placeholder="Item Name" class="form-control"></td>\n\
                        <td><textarea rows="1" name="item_desc[]" placeholder="Item Description" class="form-control"></textarea></td>\n\
                        <td ><select name="item_tax_rate[]" class="form-control"><option value="0.00"><?= lang('none') ?></option>\n\\n\
<?php
                    $tax_rates = $this->db->get('tbl_tax_rates')->result();
                    if (!empty($tax_rates)) {
                    foreach ($tax_rates as $v_tax) {
                    ?><option value="<?= $v_tax->tax_rate_percent ?>"><?= $v_tax->tax_rate_name ?></option><?php
                    }
                    }
                    ?></select></td>\n\
    <td class="col-sm-2"><input type="text" name="unit_cost[]" required placeholder="100" class="form-control"></td>\n\
<td class="col-sm-1"><input type="text" name="tax" placeholder="0.00" readonly="" class="form-control"></td>\n\
<td><a href="javascript:void(0);" class="remCF">&nbsp;&nbsp;&nbsp;<i class="fa fa-times"></i></a></strong></td></tr>\n<br/>');
                maxAppend++;
                $("#add_new").append(add_new);
            }
        });

        $("#add_new").on('click', '.remCF', function () {
            $(this).parent().parent().remove();
        });
    });
</script>