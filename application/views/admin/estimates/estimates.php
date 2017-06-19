<?= message_box('success'); ?>
<?= message_box('error'); ?>

<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="#manage"
                                                            data-toggle="tab"><?= lang('all_estimates') ?></a></li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="#new"
                                                            data-toggle="tab"><?= lang('create_estimate') ?></a></li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <div class="table-responsive">
                <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th><?= lang('estimate') ?></th>
                        <th><?= lang('created') ?></th>
                        <th><?= lang('due_date') ?></th>
                        <th><?= lang('client_name') ?></th>
                        <th><?= lang('amount') ?></th>
                        <th><?= lang('status') ?></th>
                        <th><?= lang('action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    if (!empty($all_estimates_info)) {
                        foreach ($all_estimates_info as $v_estimates) {
                            $can_edit = $this->estimates_model->can_action('tbl_estimates', 'edit', array('estimates_id' => $v_estimates->estimates_id));
                            $can_delete = $this->estimates_model->can_action('tbl_estimates', 'delete', array('estimates_id' => $v_estimates->estimates_id));

                            if ($v_estimates->status == 'Pending') {
                                $label = "info";
                            } elseif ($v_estimates->status == 'Accepted') {
                                $label = "success";
                            } else {
                                $label = "danger";
                            }
                            ?>
                            <tr>
                                <td>
                                    <a class="text-info"
                                       href="<?= base_url() ?>admin/estimates/index/estimates_details/<?= $v_estimates->estimates_id ?>"><?= $v_estimates->reference_no ?></a>
                                </td>
                                <td><?= strftime(config_item('date_format'), strtotime($v_estimates->date_saved)) ?></td>
                                <td><?= strftime(config_item('date_format'), strtotime($v_estimates->due_date)) ?>
                                    <?php
                                    if (strtotime($v_estimates->due_date) < time() AND $v_estimates->status == 'Pending') { ?>
                                        <span class="label label-danger "><?= lang('expired') ?></span>
                                    <?php }
                                    ?>
                                </td>
                                <?php
                                $client_info = $this->estimates_model->check_by(array('client_id' => $v_estimates->client_id), 'tbl_client');
                                if ($client_info->client_status == 1) {
                                    $status = lang('person');
                                } else {
                                    $status =lang('company');;
                                }
                                ?>
                                <td><?= $client_info->name . ' ' . $status . ''; ?></td>
                                <?php $currency = $this->estimates_model->client_currency_sambol($v_estimates->client_id); ?>
                                <td>
                                    <?= display_money($this->estimates_model->estimate_calculation('estimate_amount', $v_estimates->estimates_id), $currency->symbol); ?>
                                </td>
                                <td><span
                                        class="label label-<?= $label ?>"><?= lang(strtolower($v_estimates->status)) ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($can_edit)) { ?>
                                        <?= btn_edit('admin/estimates/index/edit_estimates/' . $v_estimates->estimates_id) ?>
                                    <?php }
                                    if (!empty($can_delete)) {
                                        ?>
                                        <?= btn_delete('admin/estimates/delete/delete_estimates/' . $v_estimates->estimates_id) ?>
                                    <?php } ?>
                                    <?php if (!empty($can_edit)) { ?>
                                        <div class="btn-group">
                                            <button class="btn btn-xs btn-default dropdown-toggle"
                                                    data-toggle="dropdown">
                                                <?= lang('change_status') ?>
                                                <span class="caret"></span></button>
                                            <ul class="dropdown-menu animated zoomIn">
                                                <li>
                                                    <a href="<?= base_url() ?>admin/estimates/index/email_estimates/<?= $v_estimates->estimates_id ?>"><?= lang('send_email') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url() ?>admin/estimates/index/estimates_details/<?= $v_estimates->estimates_id ?>"><?= lang('view_details') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url() ?>admin/estimates/index/estimates_history/<?= $v_estimates->estimates_id ?>"><?= lang('history') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url() ?>admin/estimates/change_status/declined/<?= $v_estimates->estimates_id ?>"><?= lang('declined') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url() ?>admin/estimates/change_status/accepted/<?= $v_estimates->estimates_id ?>"><?= lang('accepted') ?></a>
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
        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="new">
            <form role="form" enctype="multipart/form-data" id="form"
                  action="<?php echo base_url(); ?>admin/estimates/save_estimates/<?php
                  if (!empty($estimates_info)) {
                      echo $estimates_info->estimates_id;
                  }
                  ?>" method="post" class="form-horizontal  ">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('reference_no') ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <?php $this->load->helper('string'); ?>
                        <input type="text" class="form-control" value="<?php
                        if (!empty($estimates_info)) {
                            echo $estimates_info->reference_no;
                        } else {
                            echo config_item('estimate_prefix');
                            echo random_string('nozero', 5);
                        }
                        ?>" name="reference_no">
                    </div>

                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('client') ?> <span class="text-danger">*</span>
                    </label>
                    <div class="col-lg-5">
                        <select class="form-control select_box" style="width: 100%" name="client_id" required>

                            <?php
                            if (!empty($all_client)) {
                                foreach ($all_client as $v_client) {
                                    if ($v_client->client_status == 1) {
                                        $status = lang('person');
                                    } else {
                                        $status =lang('company');;
                                    }

                                    ?>
                                    <option value="<?= $v_client->client_id ?>"
                                        <?php
                                        if (!empty($estimates_info)) {
                                            $estimates_info->client_id == $v_client->client_id ? 'selected' : '';
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
                            if (!empty($estimates_info->due_date)) {
                                echo $estimates_info->due_date;
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
                    <div class="col-lg-4">
                        <div class="input-group  ">
                            <input class="form-control " value="<?php
                            if (!empty($estimates_info)) {
                                echo $estimates_info->tax;
                            } else {
                                echo $this->config->item('default_tax');
                            }
                            ?>" type="text" value="<?= $this->config->item('default_tax') ?>" name="tax">
                            <span class="input-group-addon">%</span>
                        </div>

                    </div>
                </div>

                <!-- Start discount fields -->

                <div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('discount') ?> </label>
                        <div class="col-lg-4">
                            <div class="input-group  ">
                                <input class="form-control " value="<?php
                                if (!empty($estimates_info)) {
                                    echo $estimates_info->discount;
                                } else {
                                    echo '0';
                                }
                                ?>" type="text" value="0" name="discount">
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End discount Fields -->


                <div class="form-group terms">
                    <label class="col-lg-3 control-label"><?= lang('notes') ?> </label>
                    <div class="col-lg-7">
                        <textarea name="notes" class="form-control textarea"><?php
                            if (!empty($estimates_info)) {
                                echo $estimates_info->notes;
                            } else {
                                echo $this->config->item('estimate_terms');
                            }
                            ?></textarea>
                    </div>
                </div>
                <?php
                if (!empty($estimates_info)) {
                    $estimates_id = $estimates_info->estimates_id;
                } else {
                    $estimates_id = null;
                }
                ?>
                <?= custom_form_Fields(10, $estimates_id); ?>

                <div class="form-group" id="border-none">
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('permission') ?> <span
                            class="required">*</span></label>
                    <div class="col-sm-9">
                        <div class="checkbox c-radio needsclick">
                            <label class="needsclick">
                                <input id="" <?php
                                if (!empty($estimates_info->permission) && $estimates_info->permission == 'all') {
                                    echo 'checked';
                                } elseif (empty($estimates_info)) {
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
                                if (!empty($estimates_info->permission) && $estimates_info->permission != 'all') {
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
                if (!empty($estimates_info->permission) && $estimates_info->permission != 'all') {
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
                                            if (!empty($estimates_info->permission) && $estimates_info->permission != 'all') {
                                                $get_permission = json_decode($estimates_info->permission);
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

                                if (!empty($estimates_info->permission) && $estimates_info->permission != 'all') {
                                    $get_permission = json_decode($estimates_info->permission);

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

                                            if (!empty($estimates_info->permission) && $estimates_info->permission != 'all') {
                                                $get_permission = json_decode($estimates_info->permission);

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

                                            if (!empty($estimates_info->permission) && $estimates_info->permission != 'all') {
                                                $get_permission = json_decode($estimates_info->permission);
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
                        <button type="submit" class="btn btn-sm btn-primary"><i
                                class="fa fa-plus"></i> <?= lang('create_estimate') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>