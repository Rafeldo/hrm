<?= message_box('success'); ?>
<?= message_box('error'); ?>
<?php

$recently_paid = $this->db
    ->where('paid_by', $client_details->client_id)
    ->order_by('created_date', 'desc')
    ->get('tbl_payments')
    ->result();
$all_tickets_info = $this->client_model->get_permission('tbl_tickets');
$total_tickets = 0;
if (!empty($all_tickets_info)) {
    foreach ($all_tickets_info as $v_tickets_info) {
        if (!empty($v_tickets_info)) {
            $profile_info = $this->db->where(array('user_id' => $v_tickets_info->reporter))->get('tbl_account_details')->row();
            if ($profile_info->company == $client_details->client_id) {
                $total_tickets += count($v_tickets_info->tickets_id);
            }
        }
    }
}

$client_outstanding = $this->invoice_model->client_outstanding($client_details->client_id);
$client_payments = $this->invoice_model->get_sum('tbl_payments', 'amount', $array = array('paid_by' => $client_details->client_id));
$client_payable = $client_payments + $client_outstanding;
$client_currency = $this->invoice_model->client_currency_sambol($client_details->client_id);
if (!empty($client_currency)) {
    $cur = $client_currency->symbol;
} else {
    $currency = $this->db->where(array('code' => config_item('default_currency')))->get('tbl_currencies')->row();
    $cur = $currency->symbol;
}
if ($client_payable > 0 AND $client_payments > 0) {
    $perc_paid = round(($client_payments / $client_payable) * 100, 1);
    if ($perc_paid > 100) {
        $perc_paid = '100';
    }
} else {
    $perc_paid = 0;
}
$client_transactions = $this->db->where('paid_by', $client_details->client_id)->get('tbl_transactions')->result();
?>
<div class="row">
    <div class="col-md-3">
        <div class="panel widget mb0 b0">
            <div class="row-table row-flush">
                <div class="col-xs-4 bg-info text-center">
                    <em class="fa fa-money fa-2x"></em>
                </div>
                <div class="col-xs-8">
                    <div class="text-center">
                        <h4 class="mb-sm"><?php
                            if (!empty($client_payments)) {
                                echo display_money($client_payments, $cur);
                            } else {
                                echo '0.00';
                            }
                            ?></h4>
                        <p class="mb0 text-muted"><?= lang('paid_amount') ?></p>
                        <a href="<?= base_url() ?>admin/invoice/all_payments"
                           class="small-box-footer"><?= lang('more_info') ?> <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel widget mb0 b0">
            <div class="row-table row-flush">
                <div class="col-xs-4 bg-danger text-center">
                    <em class="fa fa-usd fa-2x"></em>
                </div>
                <div class="col-xs-8">
                    <div class="text-center">
                        <h4 class="mb-sm"><?php
                            if ($client_outstanding > 0) {
                                echo display_money($client_outstanding, $cur);
                            } else {
                                echo '0.00';
                            }
                            ?></h4>
                        <p class="mb0 text-muted"><?= lang('due_amount') ?></p>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice"
                           class="small-box-footer"><?= lang('more_info') ?>
                            <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel widget mb0 b0">
            <div class="row-table row-flush">
                <div class="col-xs-4 bg-inverse text-center">
                    <em class="fa fa-usd fa-2x"></em>
                </div>
                <div class="col-xs-8">
                    <div class="text-center">
                        <h4 class="mb-sm">
                            <?php
                            if ($client_payable > 0) {
                                echo display_money($client_payable, $cur);
                            } else {
                                echo '0.00';
                            }
                            ?></h4>
                        <p class="mb0 text-muted"><?= lang('invoice_amount') ?></p>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice"
                           class="small-box-footer"><?= lang('more_info') ?>
                            <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel widget mb0 b0">
            <div class="row-table row-flush">
                <div class="col-xs-4 bg-purple text-center">
                    <em class="fa fa-usd fa-2x"></em>
                </div>
                <div class="col-xs-8">
                    <div class="text-center">
                        <h4 class="mb-sm">
                            <?= $perc_paid ?>%</h4>
                        <p class="mb0 text-muted"><?= lang('paid') . ' ' . lang('percentage') ?></p>
                        <a href="<?= base_url() ?>admin/invoice/all_payments"
                           class="small-box-footer"><?= lang('more_info') ?>
                            <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-lg">
    <div class="col-sm-3">
        <ul class="nav nav-pills nav-stacked navbar-custom-nav">
            <li class="<?= (empty($company) ? 'active' : null) ?>"><a href="#task_details" data-toggle="tab"
                                                                      aria-expanded="true"><?= lang('details') ?></a>
            </li>
            <li class="<?= (!empty($company) ? 'active' : null) ?>"><a href="#contacts" data-toggle="tab"
                                                                       aria-expanded="false"><?= lang('contacts') ?>
                    <strong
                        class="pull-right"><?= (!empty($client_contacts) ? count($client_contacts) : null) ?></strong></a>
            </li>
            <li class=""><a href="#invoices" data-toggle="tab" aria-expanded="false"><?= lang('invoices') ?><strong
                        class="pull-right"><?= (!empty($client_invoices) ? count($client_invoices) : null) ?></strong></a>
            </li>
            <li class=""><a href="#estimates" data-toggle="tab" aria-expanded="false"><?= lang('estimates') ?><strong
                        class="pull-right"><?= (!empty($client_estimates) ? count($client_estimates) : null) ?></strong></a>
            </li>
            <li class=""><a href="#payments" data-toggle="tab" aria-expanded="false"><?= lang('payments') ?><strong
                        class="pull-right"><?= (!empty($recently_paid) ? count($recently_paid) : null) ?></strong></a>
            </li>
            <li class=""><a href="#transaction" data-toggle="tab" aria-expanded="false"><?= lang('transactions') ?>
                    <strong
                        class="pull-right"><?= (!empty($client_transactions) ? count($client_transactions) : null) ?></strong></a>
            </li>

            <li class=""><a href="#ticket" data-toggle="tab" aria-expanded="false"><?= lang('tickets') ?><strong
                        class="pull-right"><?= (!empty($total_tickets) ? count($total_tickets) : null) ?></strong></a>
            </li>
        </ul>
    </div>
    <div class="col-sm-9">
        <div class="tab-content" style="border: 0;padding:0;">
            <!-- Task Details tab Starts -->
            <div class="tab-pane <?= (empty($company) ? 'active' : null) ?>" id="task_details"
                 style="position: relative;">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <div class="panel-title"><strong><?= $client_details->name ?> - <?= lang('details') ?> </strong>
                            <div class="pull-right">
                                <?php
                                if ($client_details->leads_id != 0) {
                                    echo lang('converted_from')
                                    ?>
                                    <a href="<?= base_url() ?>admin/leads/leads_details/<?= $client_details->leads_id ?>"><?= lang('leads') ?></a>
                                <?php }
                                ?>
                                <a href="<?php echo base_url() ?>admin/client/manage_client/<?= $client_details->client_id ?>"
                                   class="btn-xs "><i class="fa fa-edit"></i> <?= lang('edit') ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- Details START -->
                        <div class="col-md-6">
                            <div class="group">
                                <h4 class="subdiv text-muted"><?= lang('contact_details') ?></h4>
                                <div class="row inline-fields">
                                    <div class="col-md-4"><?= lang('name') ?></div>
                                    <div class="col-md-6"><?= $client_details->name ?></div>
                                </div>
                                <div class="row inline-fields">
                                    <div class="col-md-4"><?= lang('contact_person') ?></div>
                                    <div class="col-md-6">
                                        <?php
                                        if ($client_details->primary_contact != 0) {
                                            $contacts = $client_details->primary_contact;
                                        } else {
                                            $contacts = NULL;
                                        }
                                        $primary_contact = $this->client_model->check_by(array('account_details_id' => $contacts), 'tbl_account_details');
                                        if ($primary_contact) {
                                            echo $primary_contact->fullname;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row inline-fields">
                                    <div class="col-md-4"><?= lang('email') ?></div>
                                    <div class="col-md-6"><?= $client_details->email ?></div>
                                </div>
                            </div>

                            <div class="row inline-fields">
                                <div class="col-md-4"><?= lang('city') ?></div>
                                <div class="col-md-6"><?= $client_details->city ?></div>
                            </div>
                            <div class="row inline-fields">
                                <div class="col-md-4"><?= lang('country') ?></div>
                                <div class="col-md-6 text-success"><?= $client_details->country ?></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-lg">
                            <div class="group">
                                <div class="row" style="margin-top: 5px">
                                    <div class="rec-pay col-md-12">
                                        <h4 class="subdiv text-muted"><?= lang('received_amount') ?></h4>
                                        <h3 class="amount text-danger cursor-pointer"><strong>
                                                <?php
                                                $get_curency = $this->client_model->check_by(array('client_id' => $client_details->client_id), 'tbl_client');
                                                $curency = $this->client_model->check_by(array('code' => $get_curency->currency), 'tbl_currencies');
                                                ?><?= display_money($this->client_model->client_paid($client_details->client_id), $curency->symbol); ?>
                                            </strong></h3>
                                        <div class="row inline-fields">
                                            <div class="col-md-4"><?= lang('address') ?></div>
                                            <div class="col-md-6"><?= $client_details->address ?></div>
                                        </div>
                                        <div class="row inline-fields">
                                            <div class="col-md-4"><?= lang('phone') ?></div>
                                            <div class="col-md-6"><a
                                                    href="tel:<?= $client_details->phone ?>"><?= $client_details->phone ?></a>
                                            </div>
                                        </div>
                                        <div class="row inline-fields">
                                            <div class="col-md-4"><?= lang('website') ?></div>
                                            <div class="col-md-6"><a href="<?= $client_details->website ?>"
                                                                     class="text-info"
                                                                     target="_blank"><?= $client_details->website ?></a>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center block mt">
                            <div style="display: inline-block">
                                <div id="easypie3" data-percent="<?= $perc_paid ?>" class="easypie-chart">
                                    <span class="h2"><?= $perc_paid ?>%</span>
                                    <div class="easypie-text"><?= lang('paid') ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Details END -->
                    </div>
                    <div class="panel-footer">
                        <span><?= lang('invoice_amount') ?>: <strong
                                class="label label-primary">
                                <?= display_money($client_payable, $curency->symbol); ?>
                            </strong></span>
                        <span class="text-danger pull-right">
                            <?= lang('outstanding') ?>
                            :<strong
                                class="label label-danger"> <?= display_money($client_outstanding, $curency->symbol) ?></strong>
                        </span>
                    </div>
                </div>
            </div>

            <!--            *************** contact tab start ************-->
            <div class="tab-pane <?= (!empty($company) ? 'active' : null) ?>" id="contacts" style="position: relative;">
                <?php if (!empty($company)): ?>
                    <?php include_once 'asset/admin-ajax.php'; ?>
                    <?php
                    $eeror_message = $this->session->userdata('error');

                    if (!empty($eeror_message)):foreach ($eeror_message as $key => $message):
                        ?>
                        <div class="alert alert-danger">
                            <?php echo $message; ?>
                        </div>
                        <?php
                    endforeach;
                    endif;
                    $this->session->unset_userdata('error');
                    ?>
                    <form role="form" enctype="multipart/form-data" id="form"
                          action="<?php echo base_url(); ?>admin/client/save_contact/<?php
                          if (!empty($account_details)) {
                              echo $account_details->user_id;
                          }
                          ?>" method="post" class="form-horizontal  ">
                        <div class="panel panel-custom">
                            <!-- Default panel contents -->
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <?= lang('add_contact') ?>.
                                    <a href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id ?>"
                                       class="btn-sm pull-right">Return to Details</a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <input type="hidden" name="r_url"
                                       value="<?= base_url() ?>admin/client/client_details/<?= $company ?>">
                                <input type="hidden" name="company" value="<?= $company ?>">
                                <input type="hidden" name="role_id" value="2">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?= lang('full_name') ?> <span
                                            class="text-danger"> *</span></label>
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" value="<?php
                                        if (!empty($account_details)) {
                                            echo $account_details->fullname;
                                        }
                                        ?>" placeholder="E.g John Doe" name="fullname" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?= lang('email') ?><span
                                            class="text-danger"> *</span></label>
                                    <div class="col-lg-5">
                                        <input class="form-control" id='email' type="email" value="<?php
                                        if (!empty($user_info)) {
                                            echo $user_info->email;
                                        }
                                        ?>" placeholder="me@domin.com" name="email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?= lang('phone') ?> </label>
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" value="<?php
                                        if (!empty($account_details)) {
                                            echo $account_details->phone;
                                        }
                                        ?>" name="phone" placeholder="+52 782 983 434">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?= lang('mobile') ?> <span
                                            class="text-danger"> *</span></label>
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" value="<?php
                                        if (!empty($account_details)) {
                                            echo $account_details->mobile;
                                        }
                                        ?>" name="mobile" placeholder="+8801723611125">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?= lang('skype_id') ?> </label>
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" value="<?php
                                        if (!empty($account_details)) {
                                            echo $account_details->skype;
                                        }
                                        ?>" name="skype" placeholder="john">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?= lang('language') ?></label>
                                    <div class="col-lg-5">
                                        <select name="language" class="form-control">
                                            <?php foreach ($languages as $lang) : ?>
                                                <option value="<?= $lang->name ?>"<?php
                                                if (!empty($account_details->language) && $account_details->language == $lang->name) {
                                                    echo 'selected="selected"';
                                                } else {
                                                    echo($this->config->item('language') == $lang->name ? ' selected="selected"' : '');
                                                }
                                                ?>><?= ucfirst($lang->name) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?= lang('locale') ?></label>
                                    <div class="col-lg-5">
                                        <select class="  form-control" name="locale">
                                            <?php foreach ($locales as $loc) : ?>
                                                <option lang="<?= $loc->code ?>"
                                                        value="<?= $loc->locale ?>"<?= ($this->config->item('locale') == $loc->locale ? ' selected="selected"' : '') ?>><?= $loc->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <?php if (empty($account_details)): ?>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('username') ?> <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-5">
                                            <input class="form-control" id='username' type="text"
                                                   value="<?= set_value('username') ?>"
                                                   onchange="check_user_name(this.value)" placeholder="johndoe"
                                                   name="username" required>
                                            <div class="required" id="username_result"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('password') ?> <span
                                                class="text-danger"> *</span></label>
                                        <div class="col-lg-5">
                                            <input type="password" class="form-control" id="password"
                                                   value="<?= set_value('password') ?>" name="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('confirm_password') ?> <span
                                                class="text-danger"> *</span></label>
                                        <div class="col-lg-5">
                                            <input type="password" class="form-control"
                                                   value="<?= set_value('confirm_password') ?>" name="confirm_password">
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"></label>
                                    <div class="col-lg-5">
                                        <button type="submit" id="sbtn"
                                                class="btn btn-primary"><?= lang('add_contact') ?></button>
                                    </div>

                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                    </form>
                <?php else: ?>
                    <section class="panel panel-custom">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <strong><?= lang('contacts') ?></strong>
                                <a href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id ?>/add_contacts"
                                   class="btn-sm pull-right"><?= lang('add_contact') ?></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th><?= lang('full_name') ?></th>
                                    <th><?= lang('email') ?></th>
                                    <th><?= lang('phone') ?> </th>
                                    <th><?= lang('mobile') ?> </th>
                                    <th><?= lang('skype_id') ?></th>
                                    <th class="col-date"><?= lang('last_login') ?> </th>
                                    <th><?= lang('action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($client_contacts)) {
                                    foreach ($client_contacts as $key => $contact) {
                                        ?>
                                        <tr>
                                            <td><?= $contact->fullname ?></td>
                                            <td class="text-info"><?= $contact->email ?> </td>
                                            <td><a href="tel:<?= $contact->phone ?>"><?= $contact->phone ?></a></td>
                                            <td><a href="tel:<?= $contact->mobile ?>"><?= $contact->mobile ?></a></td>
                                            <td><a href="skype:<?= $contact->skype ?>?call"><?= $contact->skype ?></a>
                                            </td>
                                            <?php
                                            if ($contact->last_login == '0000-00-00 00:00:00') {
                                                $login_time = "-";
                                            } else {
                                                $login_time = strftime(config_item('date_format') . " %H:%M:%S", strtotime($contact->last_login));
                                            }
                                            ?>
                                            <td><?= $login_time ?> </td>
                                            <td>
                                                <a href="<?= base_url() ?>admin/client/make_primary/<?= $contact->user_id ?>/<?= $client_details->client_id ?>"
                                                   data-toggle="tooltip" class="btn <?php
                                                if ($client_details->primary_contact == $contact->user_id) {
                                                    echo "btn-success";
                                                } else {
                                                    echo "btn-default";
                                                }
                                                ?> btn-xs " title="<?= lang('primary_contact') ?>">
                                                    <i class="fa fa-chain"></i> </a>
                                                <a href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id . '/add_contacts/' . $contact->user_id ?>"
                                                   class="btn btn-primary btn-xs" title="<?= lang('edit') ?>">
                                                    <i class="fa fa-edit"></i> </a>
                                                <a href="<?= base_url() ?>admin/client/delete_contacts/<?= $client_details->client_id . '/' . $contact->user_id ?>"
                                                   class="btn btn-danger btn-xs" title="<?= lang('delete') ?>">
                                                    <i class="fa fa-trash-o"></i> </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>


                                </tbody>
                            </table>
                        </div>
                    </section>
                <?php endif ?>

            </div>
            <!--            *************** invoice tab start ************-->
            <div class="tab-pane" id="invoices" style="position: relative;">
                <section class="panel panel-custom">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <strong>
                                <?= lang('invoices') ?>
                            </strong>
                            <a href="<?= base_url() ?>admin/invoice/manage_invoice/create_invoice"
                               class="btn-sm pull-right"><?= lang('new_invoice') ?></a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th><?= lang('reference_no') ?></th>
                                <th><?= lang('date_issued') ?></th>
                                <th><?= lang('due_date') ?> </th>
                                <th class="col-currency"><?= lang('amount') ?> </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            setlocale(LC_ALL, config_item('locale') . ".UTF-8");
                            $total_invoice = 0;
                            if (!empty($client_invoices)) {
                                foreach ($client_invoices as $key => $invoice) {
                                    $total_invoice += $this->client_model->invoice_payable($invoice->invoices_id);
                                    ?>
                                    <tr>
                                        <td><a class="text-info"
                                               href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $invoice->invoices_id ?>"><?= $invoice->reference_no ?></a>
                                        </td>
                                        <td><?= strftime(config_item('date_format'), strtotime($invoice->date_saved)); ?> </td>
                                        <td><?= strftime(config_item('date_format'), strtotime($invoice->due_date)); ?> </td>
                                        <td>
                                            <?= display_money($this->client_model->invoice_payable($invoice->invoices_id), $cur); ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer">
                        <strong><?= lang('invoice') . ' ' . lang('amount') ?>:</strong> <strong
                            class="label label-success">
                            <?php
                            echo display_money($total_invoice, $cur);
                            ?>
                        </strong>
                    </div>
                </section>
            </div>
            <!--            *************** invoice tab start ************-->
            <div class="tab-pane" id="estimates" style="position: relative;">
                <section class="panel panel-custom">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <strong>
                                <?= lang('estimates') ?>
                            </strong>
                            <a href="<?= base_url() ?>admin/estimates/index/edit_estimates/"
                               class="btn-sm pull-right"><?= lang('new_estimate') ?></a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th><?= lang('reference_no') ?></th>
                                <th><?= lang('date_issued') ?></th>
                                <th><?= lang('due_date') ?> </th>
                                <th class="col-currency"><?= lang('amount') ?> </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            setlocale(LC_ALL, config_item('locale') . ".UTF-8");
                            $total_estimate = 0;
                            if (!empty($client_estimates)) {
                                foreach ($client_estimates as $key => $estimate) {
                                    $total_estimate += $this->estimates_model->estimate_calculation('estimate_amount', $estimate->estimates_id);
                                    ?>
                                    <tr>
                                        <td><a class="text-info"
                                               href="<?= base_url() ?>admin/estimates/index/estimates_details//<?= $estimate->estimates_id ?>"><?= $estimate->reference_no ?></a>
                                        </td>
                                        <td><?= strftime(config_item('date_format'), strtotime($estimate->date_saved)); ?> </td>
                                        <td><?= strftime(config_item('date_format'), strtotime($estimate->due_date)); ?> </td>
                                        <td>
                                            <?php echo display_money($this->estimates_model->estimate_calculation('estimate_amount', $estimate->estimates_id), $cur); ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer">
                        <strong><?= lang('estimate') . ' ' . lang('amount') ?>:</strong> <strong
                            class="label label-success">
                            <?= display_money($total_estimate, $cur); ?>
                        </strong>
                    </div>
                </section>
            </div>
            <!--            *************** invoice tab start ************-->
            <div class="tab-pane" id="payments" style="position: relative;">
                <section class="panel panel-custom">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?= lang('payments') ?></div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th><?= lang('payment_date') ?></th>
                                    <th><?= lang('invoice_date') ?></th>
                                    <th><?= lang('invoice') ?></th>
                                    <th><?= lang('amount') ?></th>
                                    <th><?= lang('payment_method') ?></th>
                                    <th><?= lang('action') ?></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $total_amount = 0;
                                if (!empty($recently_paid)) {
                                    foreach ($recently_paid as $key => $v_paid) {
                                        $invoice_info = $this->db->where(array('invoices_id' => $v_paid->invoices_id))->get('tbl_invoices')->row();
                                        $payment_method = $this->db->where(array('payment_methods_id' => $v_paid->payment_method))->get('tbl_payment_methods')->row();

                                        if ($v_paid->payment_method == '1') {
                                            $label = 'success';
                                        } elseif ($v_paid->payment_method == '2') {
                                            $label = 'danger';
                                        } else {
                                            $label = 'dark';
                                        }
                                        $total_amount += $v_paid->amount;
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?= $v_paid->payments_id ?>"> <?= strftime(config_item('date_format'), strtotime($v_paid->payment_date)); ?></a>
                                            </td>
                                            <td><?= strftime(config_item('date_format'), strtotime($invoice_info->date_saved)) ?></td>
                                            <td><a class="text-info"
                                                   href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_paid->invoices_id ?>"><?= $invoice_info->reference_no; ?></a>
                                            </td>
                                            <?php $currency = $this->invoice_model->client_currency_sambol($invoice_info->client_id); ?>
                                            <td><?= display_money($v_paid->amount, $currency->symbol) ?></td>
                                            <td><span
                                                    class="label label-<?= $label ?>"><?= $payment_method->method_name ?></span>
                                            </td>
                                            <td>
                                                <?= btn_edit('admin/invoice/all_payments/' . $v_paid->payments_id) ?>
                                                <?= btn_view('admin/invoice/manage_invoice/payments_details/' . $v_paid->payments_id) ?>
                                                <?= btn_delete('admin/invoice/delete/delete_payment/' . $v_paid->payments_id) ?>
                                                <a data-toggle="tooltip" data-placement="top"
                                                   href="<?= base_url() ?>admin/invoice/send_payment/<?= $v_paid->payments_id . '/' . $v_paid->amount ?>"
                                                   title="<?= lang('send_email') ?>"
                                                   class="btn btn-xs btn-success">
                                                    <i class="fa fa-envelope"></i> </a>
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
                    <div class="panel-footer">
                        <strong><?= lang('paid_amount') ?>:</strong> <strong class="label label-success">
                            <?= display_money($total_amount, $cur); ?>
                        </strong>
                    </div>
                </section>
            </div>
            <!--            *************** Transactions tab start ************-->
            <div class="tab-pane" id="transaction" style="position: relative;">
                <section class="panel panel-custom">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?= lang('transactions') ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th><?= lang('date') ?></th>
                                <th><?= lang('account') ?></th>
                                <th><?= lang('type') ?> </th>
                                <th><?= lang('amount') ?> </th>
                                <th><?= lang('action') ?> </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $total_income = 0;
                            $total_expense = 0;
                            $curency = $this->client_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                            if (!empty($client_transactions)):foreach ($client_transactions as $v_transactions) :
                                $account_info = $this->client_model->check_by(array('account_id' => $v_transactions->account_id), 'tbl_accounts');
                                ?>
                                <tr>
                                    <td><?= strftime(config_item('date_format'), strtotime($v_transactions->date)); ?></td>
                                    <td><?= $account_info->account_name ?></td>
                                    <td><?= $v_transactions->type ?></td>
                                    <td><?= display_money($v_transactions->amount, $curency->symbol); ?></td>
                                    <td>
                                        <?php

                                        if ($v_transactions->type == 'Income') {
                                            $total_income += $v_transactions->amount;
                                            ?>
                                            <?= btn_edit('admin/transactions/deposit/' . $v_transactions->transactions_id) ?>
                                            <?= btn_delete('admin/transactions/delete_deposit/' . $v_transactions->transactions_id) ?>
                                            <?php
                                        } else {
                                            $total_expense += $v_transactions->amount;
                                            ?>
                                            <?= btn_edit('admin/transactions/expense/' . $v_transactions->transactions_id) ?>
                                            <?= btn_delete('admin/transactions/delete_expense/' . $v_transactions->transactions_id) ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                                ?>

                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer">
                        <small><strong><?= lang('total_income') ?>:</strong><strong
                                class="label label-success"><?= display_money($total_income, $curency->symbol); ?></strong>
                        </small>
                        <small class="text-danger pull-right">
                            <strong><?= lang('total_expense') ?>:</strong>
                            <strong
                                class="label label-danger"><?= display_money($total_expense, $curency->symbol); ?></strong>
                        </small>
                    </div>
                </section>
            </div>

            <!--            *************** Tickets tab start ************-->
            <div class="tab-pane" id="ticket" style="position: relative;">
                <section class="panel panel-custom">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?= lang('tickets') ?>
                            <a href="<?= base_url() ?>admin/tickets/index/edit_tickets/"
                               class="btn-sm pull-right"><?= lang('new_ticket') ?></a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th><?= lang('subject') ?></th>
                                    <th class="col-date"><?= lang('date') ?></th>
                                    <?php if ($this->session->userdata('user_type') == '1') { ?>
                                        <th><?= lang('reporter') ?></th>
                                    <?php } ?>
                                    <th><?= lang('status') ?></th>
                                    <th><?= lang('action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($all_tickets_info)) {
                                    foreach ($all_tickets_info as $v_tickets_info) {
                                        $profile_info = $this->db->where(array('user_id' => $v_tickets_info->reporter))->get('tbl_account_details')->row();
                                        if ($profile_info->company == $client_details->client_id) {
                                            if ($v_tickets_info->status == 'open') {
                                                $s_label = 'danger';
                                            } elseif ($v_tickets_info->status == 'closed') {
                                                $s_label = 'success';
                                            } else {
                                                $s_label = 'default';
                                            }
                                            ?>
                                            <tr>
                                                <td><a class="text-info"
                                                       href="<?= base_url() ?>admin/tickets/index/tickets_details/<?= $v_tickets_info->tickets_id ?>"><?= $v_tickets_info->subject ?></a>
                                                </td>
                                                <td><?= strftime(config_item('date_format'), strtotime($v_tickets_info->created)); ?></td>
                                                <?php if ($this->session->userdata('user_type') == '1') { ?>

                                                    <td>
                                                        <a class="pull-left recect_task  ">
                                                            <?php if (!empty($profile_info)) {
                                                                ?>
                                                                <img style="width: 30px;margin-left: 18px;
                                                         height: 29px;
                                                         border: 1px solid #aaa;"
                                                                     src="<?= base_url() . $profile_info->avatar ?>"
                                                                     class="img-circle">
                                                            <?php } ?>

                                                            <?=
                                                            ($profile_info->fullname)
                                                            ?>
                                                        </a>
                                                    </td>

                                                <?php } ?>
                                                <?php
                                                if ($v_tickets_info->status == 'in_progress') {
                                                    $status = 'In Progress';
                                                } else {
                                                    $status = $v_tickets_info->status;
                                                }
                                                ?>
                                                <td><span
                                                        class="label label-<?= $s_label ?>"><?= ucfirst($status) ?></span>
                                                </td>
                                                <td>
                                                    <?= btn_edit('admin/tickets/index/edit_tickets/' . $v_tickets_info->tickets_id) ?>
                                                    <?= btn_delete('admin/tickets/delete/delete_tickets/' . $v_tickets_info->tickets_id) ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

