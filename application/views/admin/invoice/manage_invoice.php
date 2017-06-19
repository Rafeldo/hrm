<link href="<?php echo base_url() ?>asset/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="<?php echo base_url() ?>asset/js/bootstrap-toggle.min.js"></script>
<?php include_once 'assets/admin-ajax.php'; ?>
<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="#manage"
                                                            data-toggle="tab"><?= lang('all_invoices') ?></a></li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="#create"
                                                            data-toggle="tab"><?= lang('create_invoice') ?></a></li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">

            <div class="table-responsive">
                <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th><?= lang('invoice') ?></th>
                        <th class="col-date"><?= lang('due_date') ?></th>
                        <th><?= lang('client_name') ?></th>
                        <th class="col-currency"><?= lang('amount') ?></th>
                        <th class="col-currency"><?= lang('due_amount') ?></th>
                        <th><?= lang('status') ?></th>
                        <th class="hidden-print"><?= lang('action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($all_invoices_info)) {
                        foreach ($all_invoices_info as $v_invoices) {
                            $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $v_invoices->invoices_id));
                            $can_delete = $this->invoice_model->can_action('tbl_invoices', 'delete', array('invoices_id' => $v_invoices->invoices_id));

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
                                       href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_invoices->invoices_id ?>"><?= $v_invoices->reference_no ?></a>
                                </td>
                                <td><?= strftime(config_item('date_format'), strtotime($v_invoices->due_date)) ?>
                                    <?php
                                    $payment_status = $this->invoice_model->get_payment_status($v_invoices->invoices_id);
                                    if (strtotime($v_invoices->due_date) < time() AND $payment_status != lang('fully_paid')) { ?>
                                        <span
                                            class="label label-danger "><?= lang('overdue') ?></span>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <?php
                                $client_info = $this->invoice_model->check_by(array('client_id' => $v_invoices->client_id), 'tbl_client');

                                if ($client_info->client_status == 1) {
                                    $status = lang('person');
                                } else {
                                    $status = lang('company');
                                }
                                ?>
                                <td><?= $client_info->name . ' ' . $status . ' '; ?></td>
                                <?php $currency = $this->invoice_model->client_currency_sambol($v_invoices->client_id); ?>
                                <td><?= display_money($this->invoice_model->calculate_to('invoice_cost', $v_invoices->invoices_id), $currency->symbol) ?></td>
                                <td><?= display_money($this->invoice_model->calculate_to('invoice_due', $v_invoices->invoices_id), $currency->symbol) ?></td>
                                <td><span class="label label-<?= $label ?>"><?= $invoice_status ?></span>
                                    <?php if ($v_invoices->recurring == 'Yes') { ?>
                                        <span data-toggle="tooltip" data-placement="top"
                                              title="<?= lang('recurring') ?>" class="label label-primary"><i
                                                class="fa fa-retweet"></i></span>
                                    <?php } ?>

                                </td>
                                <td class="hidden-print">
                                    <?php if (!empty($can_edit)) { ?>
                                        <?= btn_edit('admin/invoice/manage_invoice/create_invoice/' . $v_invoices->invoices_id) ?>
                                    <?php }
                                    if (!empty($can_delete)) {
                                        ?>
                                        <?= btn_delete('admin/invoice/delete/delete_invoice/' . $v_invoices->invoices_id) ?>
                                    <?php } ?>
                                    <?php if (!empty($can_edit)) { ?>
                                        <div class="btn-group">
                                            <button class="btn btn-xs btn-default dropdown-toggle"
                                                    data-toggle="dropdown">
                                                <?= lang('change_status') ?>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu animated zoomIn">
                                                <li>
                                                    <a href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_invoices->invoices_id ?>"><?= lang('preview_invoice') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url() ?>admin/invoice/manage_invoice/payment/<?= $v_invoices->invoices_id ?>"><?= lang('pay_invoice') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url() ?>admin/invoice/manage_invoice/email_invoice/<?= $v_invoices->invoices_id ?>"><?= lang('email_invoice') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url() ?>admin/invoice/manage_invoice/send_reminder/<?= $v_invoices->invoices_id ?>"><?= lang('send_reminder') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url() ?>admin/invoice/manage_invoice/send_overdue/<?= $v_invoices->invoices_id ?>"><?= lang('send_invoice_overdue') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_history/<?= $v_invoices->invoices_id ?>"><?= lang('invoice_history') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url() ?>admin/invoice/manage_invoice/pdf_invoice/<?= $v_invoices->invoices_id ?>"><?= lang('pdf') ?></a>
                                                </li>
                                            </ul>
                                        </div>
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
        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
            <form role="form" enctype="multipart/form-data" id="form"
                  action="<?php echo base_url(); ?>admin/invoice/save_invoice/<?php
                  if (!empty($invoice_info)) {
                      echo $invoice_info->invoices_id;
                  }
                  ?>" method="post" class="form-horizontal  ">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('reference_no') ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" value="<?php
                        if (!empty($invoice_info)) {
                            echo $invoice_info->reference_no;
                        } else {
                            echo config_item('invoice_prefix');
                            if (config_item('increment_invoice_number') == 'FALSE') {
                                $this->load->helper('string');
                                echo random_string('nozero', 6);
                            } else {
                                echo $this->invoice_model->generate_invoice_number();
                            }
                        }
                        ?>" name="reference_no">
                    </div>
                    <?php if (!empty($invoice_info)) { ?>
                        <div class="btn btn-xs btn-info" id="start_recurring"><?= lang('recurring') ?></div>
                    <?php }
                    ?>

                </div>
                <?php if (!empty($invoice_info)) { ?>
                    <div id="recurring" class="<?php
                    if (!empty($invoice_info) && $invoice_info->recurring == 'No') {
                        echo 'hide';
                    }
                    ?>">
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('recur_frequency') ?> </label>
                            <div class="col-lg-4">
                                <select name="recuring_frequency" id="recuring_frequency" class="form-control" <?php
                                if (!empty($invoice_info) && $invoice_info->recurring == 'No') {
                                    echo 'disabled';
                                }
                                ?>>
                                    <option value="none"><?= lang('none') ?></option>
                                    <option
                                        value="7D"<?= ($invoice_info->recur_frequency == "7D" ? ' selected="selected"' : '') ?>><?= lang('week') ?></option>
                                    <option
                                        value="1M"<?= ($invoice_info->recur_frequency == "1M" ? ' selected="selected"' : '') ?>><?= lang('month') ?></option>
                                    <option
                                        value="3M"<?= ($invoice_info->recur_frequency == "3M" ? ' selected="selected"' : '') ?>><?= lang('quarter') ?></option>
                                    <option
                                        value="6M"<?= ($invoice_info->recur_frequency == "6M" ? ' selected="selected"' : '') ?>><?= lang('six_months') ?></option>
                                    <option
                                        value="1Y"<?= ($invoice_info->recur_frequency == "1Y" ? ' selected="selected"' : '') ?>><?= lang('1year') ?></option>
                                    <option
                                        value="2Y"<?= ($invoice_info->recur_frequency == "2Y" ? ' selected="selected"' : '') ?>><?= lang('2year') ?></option>
                                    <option
                                        value="3Y"<?= ($invoice_info->recur_frequency == "3Y" ? ' selected="selected"' : '') ?>><?= lang('3year') ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('start_date') ?></label>
                            <div class="col-lg-5">
                                <?php
                                if (!empty($invoice_info) && $invoice_info->recurring == 'Yes') {
                                    $recur_start_date = date('Y-m-d', strtotime($invoice_info->recur_start_date));
                                    $recur_end_date = date('Y-m-d', strtotime($invoice_info->recur_end_date));
                                } else {
                                    $recur_start_date = date('Y-m-d');
                                    $recur_end_date = date('Y-m-d');
                                }
                                ?>
                                <div class="input-group">
                                    <input class="form-control datepicker" type="text" value="<?= $recur_start_date; ?>"
                                           name="recur_start_date"
                                           data-date-format="<?= config_item('date_picker_format'); ?>">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('end_date') ?></label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input class="form-control datepicker" type="text" value="<?= $recur_end_date; ?>"
                                           name="recur_end_date"
                                           data-date-format="<?= config_item('date_picker_format'); ?>">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }

                ?>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('client') ?> <span class="text-danger">*</span>
                    </label>
                    <div class="col-lg-5">
                        <select class="form-control select_box" required style="width: 100%" name="client_id" >
                            <option value="-"><?= lang('select') . ' ' . lang('client') ?></option>
                            <?php
                            if (!empty($all_client)) {
                                foreach ($all_client as $v_client) {
                                    if ($v_client->client_status == 1) {
                                        $status = lang('person');
                                    } else {
                                        $status = lang('company');
                                    }
                                    if (!empty($project_info->client_id)) {
                                        $client_id = $project_info->client_id;
                                    } elseif ($invoice_info->client_id) {
                                        $client_id = $invoice_info->client_id;
                                    }
                                    ?>
                                    <option value="<?= $v_client->client_id ?>"
                                        <?php
                                        if (!empty($client_id)) {
                                            echo $client_id == $v_client->client_id ? 'selected' : null;
                                        }
                                        ?>
                                    ><?= ucfirst($v_client->name) . ' <small>' . $status . '</small>' ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('due_date') ?></label>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <input type="text" name="due_date" class="form-control datepicker" value="<?php
                            if (!empty($invoice_info->due_date)) {
                                echo $invoice_info->due_date;
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
                    <label class="col-lg-3 control-label"><?= lang('default_tax') ?> </label>
                    <div class="col-lg-5">
                        <div class="input-group  ">
                            <input class="form-control " type="text" value="<?php
                            if (!empty($invoice_info)) {
                                echo $invoice_info->tax;
                            } else {
                                echo $this->config->item('default_tax');
                            }
                            ?>" name="tax">
                            <span class="input-group-addon">%</span>
                        </div>

                    </div>
                </div>

                <!-- Start discount fields -->

                <div id="discounts">

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('discount') ?> </label>
                        <div class="col-lg-5">
                            <div class="input-group  ">
                                <input class="form-control " type="text" value="<?php
                                if (!empty($invoice_info)) {
                                    echo $invoice_info->discount;
                                } else {
                                    echo '0';
                                }
                                ?>" name="discount">
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>

                </div>
                <?php if (config_item('paypal_status') == 'active'): ?>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('allow_paypal') ?></label>

                        <div class="col-sm-5">
                            <input data-toggle="toggle" name="allow_paypal" value="Yes" <?php
                            if (!empty($invoice_info) && $invoice_info->allow_paypal == 'Yes') {
                                echo 'checked';
                            }
                            ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success"
                                   data-offstyle="danger" type="checkbox">
                        </div>
                    </div>
                <?php endif ?>
                <?php if (config_item('stripe_status') == 'active'): ?>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('allow_stripe') ?></label>

                        <div class="col-sm-5">
                            <input data-toggle="toggle" name="allow_stripe" value="Yes" <?php
                            if (!empty($invoice_info) && $invoice_info->allow_stripe == 'Yes') {
                                echo 'checked';
                            }
                            ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success"
                                   data-offstyle="danger" type="checkbox">
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (config_item('2checkout_status') == 'active'): ?>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('allow_2checkout') ?></label>

                        <div class="col-sm-5">
                            <input data-toggle="toggle" name="allow_2checkout" value="Yes" <?php
                            if (!empty($invoice_info) && $invoice_info->allow_2checkout == 'Yes') {
                                echo 'checked';
                            }
                            ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success"
                                   data-offstyle="danger" type="checkbox">
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (config_item('authorize_status') == 'active'): ?>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('allow_authorize.net') ?></label>

                        <div class="col-sm-5">
                            <input data-toggle="toggle" name="allow_authorize" value="Yes" <?php
                            if (!empty($invoice_info) && $invoice_info->allow_authorize == 'Yes') {
                                echo 'checked';
                            }
                            ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success"
                                   data-offstyle="danger" type="checkbox">
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (config_item('ccavenue_status') == 'active'): ?>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('allow_ccavenue') ?></label>

                        <div class="col-sm-5">
                            <input data-toggle="toggle" name="allow_ccavenue" value="Yes" <?php
                            if (!empty($invoice_info) && $invoice_info->allow_ccavenue == 'Yes') {
                                echo 'checked';
                            }
                            ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success"
                                   data-offstyle="danger" type="checkbox">
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (config_item('braintree_status') == 'active'): ?>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('allow_braintree') ?></label>

                        <div class="col-sm-5">
                            <input data-toggle="toggle" name="allow_braintree" value="Yes" <?php
                            if (!empty($invoice_info) && $invoice_info->allow_braintree == 'Yes') {
                                echo 'checked';
                            }
                            ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success"
                                   data-offstyle="danger" type="checkbox">
                        </div>
                    </div>
                <?php endif; ?>
                <!-- End discount Fields -->
                <div class="form-group terms">
                    <label class="col-lg-3 control-label"><?= lang('notes') ?> </label>
                    <div class="col-lg-7">
                        <textarea name="notes" class="form-control textarea"><?php
                            if (!empty($invoice_info)) {
                                echo $invoice_info->notes;
                            } else {
                                echo $this->config->item('default_terms');
                            }
                            ?></textarea>
                    </div>
                </div>
                <?php
                if (!empty($invoice_info)) {
                    $invoices_id = $invoice_info->invoices_id;
                } else {
                    $invoices_id = null;
                }
                ?>
                <?= custom_form_Fields(9, $invoices_id); ?>
                <?php if (!empty($project_id)): ?>
                    <div class="form-group">
                        <label for="field-1"
                               class="col-sm-3 control-label"><?= lang('visible_to_client') ?>
                            <span class="required">*</span></label>
                        <div class="col-sm-8">
                            <input data-toggle="toggle" name="client_visible" value="Yes" <?php
                            if (!empty($invoice_info) && $invoice_info->client_visible == 'Yes') {
                                echo 'checked';
                            }
                            ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>"
                                   data-onstyle="success" data-offstyle="danger" type="checkbox">
                        </div>
                    </div>
                <?php endif ?>
                <div class="form-group" id="border-none">
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('permission') ?> <span
                            class="required">*</span></label>
                    <div class="col-sm-9">
                        <div class="checkbox c-radio needsclick">
                            <label class="needsclick">
                                <input id="" <?php
                                if (!empty($invoice_info->permission) && $invoice_info->permission == 'all') {
                                    echo 'checked';
                                } elseif (empty($invoice_info)) {
                                    echo 'checked';
                                }
                                ?> type="radio" name="permission" value="everyone">
                                <span class="fa fa-circle"></span><?= lang('everyone') ?>
                                <i title="<?= lang('permission_for_all') ?>"
                                   class="fa fa-question-circle" data-toggle="tooltip"
                                   data-placement="top"></i>
                            </label>
                        </div>
                        <div class="checkbox c-radio needsclick">
                            <label class="needsclick">
                                <input id="" <?php
                                if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                    echo 'checked';
                                }
                                ?> type="radio" name="permission" value="custom_permission"
                                >
                                <span class="fa fa-circle"></span><?= lang('custom_permission') ?> <i
                                    title="<?= lang('permission_for_customization') ?>"
                                    class="fa fa-question-circle" data-toggle="tooltip"
                                    data-placement="top"></i>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group <?php
                if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                    echo 'show';
                }
                ?>" id="permission_user_1">
                    <label for="field-1"
                           class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('users') ?>
                        <span
                            class="required">*</span></label>
                    <div class="col-sm-9">
                        <?php
                        if (!empty($permission_user)) {
                            foreach ($permission_user as $key => $v_user) {

                                if ($v_user->role_id == 1) {
                                    $role = '<strong class="badge btn-danger">' . lang('admin') . '</strong>';
                                } else {
                                    $role = '<strong class="badge btn-primary">' . lang('staff') . '</strong>';
                                }

                                ?>
                                <div class="checkbox c-checkbox needsclick">
                                    <label class="needsclick">
                                        <input type="checkbox"
                                            <?php
                                            if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                $get_permission = json_decode($invoice_info->permission);
                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        echo 'checked';
                                                    }
                                                }

                                            }
                                            ?>
                                               value="<?= $v_user->user_id ?>"
                                               name="assigned_to[]"
                                               class="needsclick">
                                                        <span
                                                            class="fa fa-check"></span><?= $v_user->username . ' ' . $role ?>
                                    </label>

                                </div>
                                <div class="action_1 p
                                                <?php

                                if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                    $get_permission = json_decode($invoice_info->permission);

                                    foreach ($get_permission as $user_id => $v_permission) {
                                        if ($user_id == $v_user->user_id) {
                                            echo 'show';
                                        }
                                    }

                                }
                                ?>
                                                " id="action_1<?= $v_user->user_id ?>">
                                    <label class="checkbox-inline c-checkbox">
                                        <input id="<?= $v_user->user_id ?>" checked type="checkbox"
                                               name="action_1<?= $v_user->user_id ?>[]"
                                               disabled
                                               value="view">
                                                        <span
                                                            class="fa fa-check"></span><?= lang('can') . ' ' . lang('view') ?>
                                    </label>
                                    <label class="checkbox-inline c-checkbox">
                                        <input id="<?= $v_user->user_id ?>"
                                            <?php

                                            if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                $get_permission = json_decode($invoice_info->permission);

                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        if (in_array('edit', $v_permission)) {
                                                            echo 'checked';
                                                        };

                                                    }
                                                }

                                            }
                                            ?>
                                               type="checkbox"
                                               value="edit" name="action_<?= $v_user->user_id ?>[]">
                                                        <span
                                                            class="fa fa-check"></span><?= lang('can') . ' ' . lang('edit') ?>
                                    </label>
                                    <label class="checkbox-inline c-checkbox">
                                        <input id="<?= $v_user->user_id ?>"
                                            <?php

                                            if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                $get_permission = json_decode($invoice_info->permission);
                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        if (in_array('delete', $v_permission)) {
                                                            echo 'checked';
                                                        };
                                                    }
                                                }

                                            }
                                            ?>
                                               name="action_<?= $v_user->user_id ?>[]"
                                               type="checkbox"
                                               value="delete">
                                                        <span
                                                            class="fa fa-check"></span><?= lang('can') . ' ' . lang('delete') ?>
                                    </label>
                                    <input id="<?= $v_user->user_id ?>" type="hidden"
                                           name="action_<?= $v_user->user_id ?>[]" value="view">

                                </div>


                                <?php
                            }
                        }
                        ?>


                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-5">
                        <button type="submit" class="btn btn-sm btn-success"><i
                                class="fa fa-check"></i> <?= lang('create_invoice') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#start_recurring').click(function () {
            $('#recurring').slideToggle("fast");
            $('#recurring').removeClass("hide");
            $('#recuring_frequency').prop('disabled', false);
        });
    });

</script>