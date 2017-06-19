<?= message_box('success'); ?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="#manage"
                                                            data-toggle="tab"><?= lang('tax_rates') ?></a></li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="#new"
                                                            data-toggle="tab"><?= lang('new_tax_rate') ?></a></li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <div class="table-responsive">
                <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th><?= lang('tax_rate_name') ?></th>
                        <th><?= lang('tax_rate_percent') ?></th>
                        <th class="col-options no-sort"><?= lang('action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $all_tax_rates = $this->db->get('tbl_tax_rates')->result();
                    if (!empty($all_tax_rates)) {
                        foreach ($all_tax_rates as $v_tax_rates) {
                            $can_delete = $this->invoice_model->can_action('tbl_tax_rates', 'delete', array('tax_rates_id' => $v_tax_rates->tax_rates_id));
                            $can_edit = $this->invoice_model->can_action('tbl_tax_rates', 'edit', array('tax_rates_id' => $v_tax_rates->tax_rates_id));
                            ?>
                            <tr>
                                <td><?= $v_tax_rates->tax_rate_name ?></td>
                                <td><?= $v_tax_rates->tax_rate_percent ?> %</td>

                                <td>
                                    <?php if (!empty($can_edit)) { ?>
                                        <?= btn_edit('admin/invoice/tax_rates/edit_tax_rates/' . $v_tax_rates->tax_rates_id) ?>
                                    <?php }
                                    if (!empty($can_delete)) {
                                        ?>
                                        <?= btn_delete('admin/invoice/tax_rates/delete_tax_rates/' . $v_tax_rates->tax_rates_id) ?>
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
            <form method="post" action="<?= base_url() ?>admin/invoice/save_tax_rate/<?php
            if (!empty($tax_rates_info)) {
                echo $tax_rates_info->tax_rates_id;
            }
            ?>" class="form-horizontal">
                <div class="form-group">
                    <label class="col-lg-4 control-label"><?= lang('tax_rate_name') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" required value="<?php
                        if (!empty($tax_rates_info)) {
                            echo $tax_rates_info->tax_rate_name;
                        }
                        ?>" name="tax_rate_name">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label"><?= lang('tax_rate_percent') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" required value="<?php
                        if (!empty($tax_rates_info)) {
                            echo $tax_rates_info->tax_rate_percent;
                        }
                        ?>" name="tax_rate_percent">
                    </div>
                </div>
                <div class="form-group" id="border-none">
                    <label for="field-1" class="col-sm-4 control-label"><?= lang('permission') ?> <span
                            class="required">*</span></label>
                    <div class="col-sm-8">
                        <div class="checkbox c-radio needsclick">
                            <label class="needsclick">
                                <input id="" <?php
                                if (!empty($tax_rates_info) && $tax_rates_info->permission == 'all') {
                                    echo 'checked';
                                } elseif (empty($tax_rates_info)) {
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
                                if (!empty($tax_rates_info) && $tax_rates_info->permission != 'all') {
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
                if (!empty($tax_rates_info) && $tax_rates_info->permission != 'all') {
                    echo 'show';
                }
                ?>" id="permission_user_1">
                    <label for="field-1"
                           class="col-sm-4 control-label"><?= lang('select') . ' ' . lang('users') ?>
                        <span
                            class="required">*</span></label>
                    <div class="col-sm-8">
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
                                            if (!empty($tax_rates_info) && $tax_rates_info->permission != 'all') {
                                                $get_permission = json_decode($tax_rates_info->permission);
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

                                if (!empty($tax_rates_info) && $tax_rates_info->permission != 'all') {
                                    $get_permission = json_decode($tax_rates_info->permission);

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

                                            if (!empty($tax_rates_info) && $tax_rates_info->permission != 'all') {
                                                $get_permission = json_decode($tax_rates_info->permission);

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

                                            if (!empty($tax_rates_info) && $tax_rates_info->permission != 'all') {
                                                $get_permission = json_decode($tax_rates_info->permission);
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
                    <label class="col-lg-4 control-label"></label>
                    <div class="col-lg-5">
                        <button type="submit" class="btn btn-primary"><?= lang('save_changes') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>