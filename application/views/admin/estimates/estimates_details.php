<?= message_box('success') ?>
<?= message_box('error'); ?>
<div class="row mb">

    <div class="col-sm-8">
        <?php
        $can_edit = $this->estimates_model->can_action('tbl_estimates', 'edit', array('estimates_id' => $estimates_info->estimates_id));
        $can_delete = $this->estimates_model->can_action('tbl_estimates', 'delete', array('estimates_id' => $estimates_info->estimates_id));
        $client_info = $this->estimates_model->check_by(array('client_id' => $estimates_info->client_id), 'tbl_client');

        $client_lang = $client_info->language;
        unset($this->lang->is_loaded[5]);
        $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
        $currency = $this->estimates_model->client_currency_sambol($estimates_info->client_id);
        ?>

        <?php if (!empty($can_edit)) { ?>
            <a data-toggle="modal" data-target="#myModal"
               href="<?= base_url() ?>admin/estimates/insert_items/<?= $estimates_info->estimates_id ?>"
               title="<?= lang('item_quick_add') ?>" class="btn btn-sm btn-primary">
                <i class="fa fa-list-alt text-white"></i> <?= lang('from_items') ?></a>

            <?php if ($estimates_info->show_client == 'Yes') { ?>
            <a class="btn btn-sm btn-success"
               href="<?= base_url() ?>admin/estimates/change_status/hide/<?= $estimates_info->estimates_id ?>"
               title="<?= lang('hide_to_client') ?>"><i class="fa fa-eye-slash"></i> <?= lang('hide_to_client') ?>
                </a><?php } else { ?>
            <a class="btn btn-sm btn-warning"
               href="<?= base_url() ?>admin/estimates/change_status/show/<?= $estimates_info->estimates_id ?>"
               title="<?= lang('show_to_client') ?>"><i class="fa fa-eye"></i> <?= lang('show_to_client') ?>
                </a><?php } ?>

            <a data-original-title="<?= lang('convert_to_invoice') ?>" data-toggle="tooltip" data-placement="top"
               class="btn btn-sm btn-purple <?php
               if ($estimates_info->invoiced == 'Yes' OR $estimates_info->client_id == '0') {
                   echo "disabled";
               }
               ?>" href="<?= base_url() ?>admin/estimates/convert_to_invoice/<?= $estimates_info->estimates_id ?>"
               title="<?= lang('convert_to_invoice') ?>">
                <?= lang('convert_to_invoice') ?></a>

            <?php
        }
        ?>

        <div class="btn-group">
            <button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                <?= lang('more_actions') ?>
                <span class="caret"></span></button>
            <ul class="dropdown-menu animated zoomIn">
                <?php if (!empty($can_edit)) { ?>
                    <li>
                        <a href="<?= base_url() ?>admin/estimates/index/email_estimates/<?= $estimates_info->estimates_id ?>"
                           data-toggle="ajaxModal"><?= lang('email_estimate') ?></a></li>
                    <li>
                        <a href="<?= base_url() ?>admin/estimates/index/estimates_history/<?= $estimates_info->estimates_id ?>"><?= lang('estimate_history') ?></a>
                    </li>
                <?php } ?>
                <li>
                    <a href="<?= base_url() ?>admin/estimates/change_status/declined/<?= $estimates_info->estimates_id ?>"><?= lang('declined') ?></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/estimates/change_status/accepted/<?= $estimates_info->estimates_id ?>"><?= lang('accepted') ?></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-sm-4 pull-right">
        <a
            href="<?= base_url() ?>admin/estimates/estimate_email/<?= $estimates_info->estimates_id ?>"
            data-toggle="tooltip" data-placement="top" title="<?= lang('send_email') ?>"
            class="btn btn-sm btn-primary pull-right">
            <i class="fa fa-envelope-o"></i>
        </a>
        <a onclick="print_estimates('print_estimates')" href="#" data-toggle="tooltip" data-placement="top" title=""
           data-original-title="Print" class="mr-sm btn btn-sm btn-danger pull-right">
            <i class="fa fa-print"></i>
        </a>

        <a style="margin-right: 5px"
           href="<?= base_url() ?>admin/estimates/index/pdf_estimates/<?= $estimates_info->estimates_id ?>"
           data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF"
           class="btn btn-sm btn-success pull-right">
            <i class="fa fa-file-pdf-o"></i>
        </a>

    </div>
</div>
<!-- Start Display Details -->
<?php
if (strtotime($estimates_info->due_date) < time() AND $estimates_info->status == 'Pending') {
    ?>
    <div class="alert bg-danger-light hidden-print">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="fa fa-warning"></i>
        <?= lang('estimate_overdue') ?>
    </div>
    <?php
}
?>
<!-- Main content -->
<div class="panel" id="print_estimates">
    <!-- title row -->
    <div class="show_print ">
        <div class="col-xs-12">
            <h4 class="page-header">
                <img style="width: 60px;width: 60px;margin-top: -10px;margin-right: 10px;"
                     src="<?= base_url() . config_item('invoice_logo') ?>"><?= config_item('company_name') ?>
            </h4>
        </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="panel-body">
        <?php if (!empty($can_edit)) { ?>
            <a href="<?= base_url() ?>admin/estimates/index/edit_estimates/<?= $estimates_info->estimates_id ?>"
               class="pull-right btn btn-primary btn-sm"><?= $language_info['edit_estimate'] ?></a>
        <?php } ?>
        <h3 class="mt0"><?= $estimates_info->reference_no ?></h3>
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
                        <h4><?= $client_info->name . ' (' . $status . ')' ?></h4>
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
                    <p class="pull-left">ESTIMATES NO.</p>
                    <p class="pull-right mr"><?= $estimates_info->reference_no ?></p>
                </div>
                <div class="clearfix"><?php
                    if (strtotime($estimates_info->due_date) < time() AND $estimates_info->status == 'Pending') {
                        $danger = 'text-danger';
                    } else {
                        $danger = null;
                    }
                    ?>
                    <p class="pull-left <?= $danger ?>"> <?= $language_info['valid_until'] ?></p>
                    <p class="pull-right mr <?= $danger ?>"><?= strftime(config_item('date_format'), strtotime($estimates_info->due_date)); ?></p>
                </div>
                <div class="clearfix">
                    <?php
                    if ($estimates_info->status == 'Accepted') {
                        $label = 'success';
                    } else {
                        $label = 'danger';
                    }
                    ?>
                    <p class="pull-left"><?= $language_info['estimate_status'] ?></p>
                    <p class="pull-right mr"><span
                            class="label label-<?= $label ?>"><?= $estimates_info->status ?></span></p>
                </div>
                <?php $show_custom_fields = custom_form_label(10, $estimates_info->estimates_id);

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
        </div><!-- /.row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <form method="post" action="<?= base_url() ?>admin/estimates/add_item/<?php
                if (!empty($item_info)) {
                    echo $item_info->estimate_items_id;
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
                        $estimates_items = $this->estimates_model->ordered_items_by_id($estimates_info->estimates_id);

                        if (!empty($estimates_items)) :
                            foreach ($estimates_items as $v_item) :
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
                                        <?php if (!empty($can_edit)) { ?>
                                            <?= btn_edit('admin/estimates/index/estimates_details/' . $v_item->estimates_id . '/' . $v_item->estimate_items_id) ?>
                                            <?= btn_delete('admin/estimates/delete/delete_item/' . $v_item->estimates_id . '/' . $v_item->estimate_items_id) ?>
                                        <?php } ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif ?>
                        <?php if (!empty($can_edit)) { ?>
                        <?php if ($estimates_info->status != 'Paid') { ?>

                        <tr class="hidden-print">
                            <input type="hidden" name="estimates_id" value="<?= $estimates_info->estimates_id ?>">
                            <input type="hidden" name="item_order" value="<?= count($estimates_items) + 1 ?>">
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
            </div>
            <td>
                <strong><a href="javascript:void(0);" id="add_more" class="addCF "><i class="fa fa-plus"></i>&nbsp;More</a></strong>
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
            <div class="row">
                <div class="col-xs-8">

                    <p class="well well-sm mt">
                        <?= $estimates_info->notes ?>
                    </p>
                </div><!-- /.col -->
                <div class="col-sm-4 pv">
                    <div class="clearfix">
                        <p class="pull-left"><?= $language_info['sub_total'] ?></p>
                        <p class="pull-right mr">
                            <?= display_money($this->estimates_model->estimate_calculation('estimate_cost', $estimates_info->estimates_id), $currency->symbol) ?>
                        </p>
                    </div>
                    <?php if ($estimates_info->tax > 0.00): ?>
                        <div class="clearfix">
                            <p class="pull-left"><?= $language_info['tax'] ?> (<?php echo $estimates_info->tax; ?>
                                %)</p>
                            <p class="pull-right mr">
                                <?= display_money($this->estimates_model->estimate_calculation('tax', $estimates_info->estimates_id), $currency->symbol) ?>
                            </p>
                        </div>
                    <?php endif ?>

                    <?php if ($estimates_info->discount > 0): ?>
                        <div class="clearfix">
                            <p class="pull-left"><?= $language_info['discount'] ?>
                                (<?php echo $estimates_info->discount; ?>
                                %)</p>
                            <p class="pull-right mr">
                                <?= display_money($this->estimates_model->estimate_calculation('discount', $estimates_info->estimates_id), $currency->symbol) ?>
                            </p>
                        </div>
                    <?php endif ?>

                    <div class="clearfix">
                        <p class="pull-left h3"><?= $language_info['total'] ?></p>
                        <p class="pull-right mr h3">
                            <?= display_money($this->estimates_model->estimate_calculation('estimate_amount', $estimates_info->estimates_id), $currency->symbol) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

</section>
<script type="text/javascript">
    function print_estimates(print_estimates) {
        var printContents = document.getElementById(print_estimates).innerHTML;
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
                var add_new = $('<tr ><td class="col-sm-1"><input type="text" name="quantity[]" placeholder="1" required class="form-control"></td>">\n\
                    <td><input type="text" name="item_name[]" required  placeholder="Item Name" class="form-control"></td>\n\
                        <td><textarea rows="1" name="item_desc[]" placeholder="Item Description" class="form-control"></textarea></td>\n\
                        <td><select name="item_tax_rate[]" class="form-control"><option value="0.00"><?= lang('none') ?></option>\n\\n\
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