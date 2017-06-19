<?php include_once 'asset/admin-ajax.php'; ?>

<?= message_box('success'); ?>
<?= message_box('error'); ?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="#manage"
                                                            data-toggle="tab"><?= lang('all_users') ?></a></li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="#new" data-toggle="tab"><?= lang('new_user') ?></a>
        </li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th class="col-sm-1"><?= lang('photo') ?></th>
                    <th><?= lang('name') ?></th>
                    <th class="col-sm-2"><?= lang('username') ?></th>
                    <th class="col-sm-1"><?= lang('status') ?></th>
                    <th class="col-sm-1"><?= lang('user_type') ?></th>
                    <th class="col-sm-2"><?= lang('action') ?></th>

                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($all_user_info)): foreach ($all_user_info as $v_user) :
                    $account_info = $this->user_model->check_by(array('user_id' => $v_user->user_id), 'tbl_account_details');
                    if (!empty($account_info)) {
                        $can_edit = $this->user_model->can_action('tbl_users', 'edit', array('user_id' => $v_user->user_id));
                        $can_delete = $this->user_model->can_action('tbl_users', 'delete', array('user_id' => $v_user->user_id));
                        ?>

                        <tr>

                            <td><img style="width: 36px;margin-right: 10px;"
                                     src="<?= base_url() ?><?= $account_info->avatar ?>" class="img-circle"></td>
                            <td>
                                <?php if ($v_user->role_id != 2) { ?>
                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $v_user->user_id ?>"><?= $account_info->fullname ?></a>
                                <?php } else { ?>
                                    <?= $account_info->fullname ?>
                                <?php } ?>

                            </td>
                            <td><?= ($v_user->username) ?></td>
                            <td>

                                <?php if ($v_user->activated == 1): ?>
                                    <a data-toggle="tooltip" data-placement="top"
                                       title="<?= lang('click_to_deactive') ?>"
                                       href="
                                       <?php if (!empty($can_edit)) {
                                           echo base_url() ?>admin/user/change_status/0/<?php echo $v_user->user_id;
                                       } ?>

                                       "><?= lang('active') ?></a>
                                <?php else: ?>
                                    <a data-toggle="tooltip" data-placement="top" title="<?= lang('click_to_active') ?>"
                                       href="<?php if (!empty($can_edit)) {
                                           echo base_url() ?>admin/user/change_status/1/<?php echo $v_user->user_id;
                                       } ?>"><?= lang('deactive') ?></a>
                                <?php endif; ?>

                                <?php
                                if ($v_user->banned == 1) {
                                    ?>
                                    <span class="label label-danger" data-toggle='tooltip' data-placement='top'
                                          title="<?= $v_user->ban_reason ?>"><?= lang('banned') ?></span>
                                <?php }
                                ?></td>
                            <td><?php
                                if ($v_user->role_id == 1) {
                                    echo lang('admin');
                                } elseif ($v_user->role_id == 3) {
                                    echo lang('staff');
                                } else {
                                    echo lang('client');
                                }
                                ?></td>
                            <td><?php if ($v_user->user_id != $this->session->userdata('user_id')): ?>
                                    <?php if (!empty($can_edit)) { ?>
                                        <?php if ($v_user->banned == 1): ?>
                                            <a data-toggle="tooltip" data-placement="top" class="btn btn-success btn-xs"
                                               title="Click to <?= lang('unbanned') ?> "
                                               href="<?php echo base_url() ?>admin/user/set_banned/0/<?php echo $v_user->user_id; ?>"><span
                                                    class="fa fa-check"></span></a>
                                        <?php else: ?>
                                            <?php echo btn_banned_modal('admin/user/change_banned/' . $v_user->user_id); ?>
                                        <?php endif; ?>
                                    <?php } ?>

                                    <a data-toggle="tooltip" data-placement="top" class="btn btn-info btn-xs"
                                       title="<?= lang('send') . ' ' . lang('wellcome_email') ?> "
                                       href="<?php echo base_url() ?>admin/user/send_welcome_email/<?php echo $v_user->user_id; ?>"><span
                                            class="fa fa-envelope-o"></span></a>

                                    <?php if (!empty($can_edit)) { ?>
                                        <?php echo btn_edit('admin/user/user_list/edit_user/' . $v_user->user_id); ?>
                                    <?php }
                                    if (!empty($can_delete)) {
                                        ?>
                                        <?php echo btn_delete('admin/user/delete_user/' . $v_user->user_id); ?>
                                    <?php } ?>
                                <?php endif; ?>
                            </td>


                        </tr>
                        <?php
                    };
                endforeach;
                    ?>
                <?php else : ?>
                    <td colspan="3">
                        <strong><?= lang('nothing_to_display') ?></strong>
                    </td>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="new">
            <form role="form" id="userform" enctype="multipart/form-data"
                  action="<?php echo base_url(); ?>admin/user/save_user" method="post"
                  class="form-horizontal form-groups-bordered">

                <?php
                if (!empty($login_info->user_id)) {
                    $profile_info = $this->user_model->check_by(array('user_id' => $login_info->user_id), 'tbl_account_details');
                }
                ?>
                <input type="hidden" id="username_flag" value="">
                <input type="hidden" name="user_id" value="<?php
                if (!empty($login_info)) {
                    echo $login_info->user_id;
                }
                ?>">
                <input type="hidden" name="account_details_id" value="<?php
                if (!empty($profile_info)) {
                    echo $profile_info->account_details_id;
                }
                ?>">

                <div class="form-group">
                    <label class="col-sm-3 control-label"><strong><?= lang('full_name') ?> </strong><span
                            class="text-danger">*</span></label>
                    <div class="col-sm-5">
                        <input type="text" class="input-sm form-control" value="<?php
                        if (!empty($profile_info)) {
                            echo $profile_info->fullname;
                        }
                        ?>" placeholder="<?= lang('eg') ?> <?= lang('enter_your') . ' ' . lang('full_name') ?>"
                               name="fullname"
                               required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><strong><?= lang('employment_id') ?> </strong><span
                            class="text-danger">*</span></label>
                    <div class="col-sm-5">
                        <input type="text" class="input-sm form-control" value="<?php
                        if (!empty($profile_info)) {
                            echo $profile_info->employment_id;
                        }
                        ?>" placeholder="<?= lang('eg') ?> 15351" name="employment_id"
                               onchange="check_duplicate_emp_id(this.value)"
                        >
                    </div>
                    <div class="col-sm-4" id="id_duplicate_emp">

                    </div>
                </div>

                <?php if (empty($login_info->user_id)) { ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong> <?= lang('username'); ?></strong><span
                                class="text-danger">*</span></label>
                        <div class="col-sm-5">
                            <input type="text" name="username" onchange="check_user_name(this.value)"
                                   placeholder="<?= lang('eg') ?> <?= lang('enter_your') . ' ' . lang('username') ?>"
                                   value="<?php
                                   if (!empty($login_info)) {
                                       echo $login_info->username;
                                   }
                                   ?>" class="input-sm form-control" required>
                        </div>
                        <div class="col-sm-4" id="username_result">

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong><?= lang('password') ?> </strong><span
                                class="text-danger">*</span></label>
                        <div class="col-sm-5">
                            <input type="password" id="password" placeholder="<?= lang('password') ?>"
                                   name="password" class="input-sm form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label
                            class="col-sm-3 control-label"><strong><?= lang('confirm_password') ?> </strong><span
                                class="text-danger">*</span></label>
                        <div class="col-sm-5">
                            <input type="password" onchange="check_match_password(this.value)"
                                   placeholder="<?= lang('confirm_password') ?>"
                                   name="confirm_password" class="input-sm form-control">
                        </div>
                        <div class="col-sm-3" id="passqord_match" style="display: none">
                            <small
                                style="padding-left:10px;color:red;font-size:10px"><?= lang("password_does_not_match") ?></small>
                        </div>
                    </div>
                <?php } else { ?>
                    <input type="hidden" name="username"
                           placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_username') ?>" value="<?php
                    if (!empty($login_info)) {
                        echo $login_info->username;
                    }
                    ?>" class="input-sm form-control" required>
                <?php } ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><strong><?= lang('email') ?> </strong><span
                            class="text-danger">*</span></label>
                    <div class="col-sm-5">
                        <input type="email"
                               placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_email') ?>"
                               name="email" value="<?php
                        if (!empty($login_info)) {
                            echo $login_info->email;
                        }
                        ?>" class="input-sm form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><strong><?= lang('companies') ?> </strong></label>
                    <div class="col-sm-5">
                        <select class="form-control select_box" style="width: 100%" name="company">
                            <optgroup label="<?= lang('default_company') ?>">
                                <option value="-"><?= $this->config->item('company_name') ?></option>
                            </optgroup>
                            <optgroup label="<?= lang('other_companies') ?>">
                                <?php
                                if (!empty($all_client_info)) {
                                    foreach ($all_client_info as $v_client) {
                                        if ($v_client->client_status == 1) {
                                            $client_status = lang('person');
                                        } else {
                                            $client_status = lang('company');
                                        }
                                        ?>
                                        <option value="<?= $v_client->client_id ?>"
                                            <?php
                                            if (!empty($profile_info)) {
                                                if ($profile_info->company == $v_client->client_id) {
                                                    echo 'selected';
                                                }
                                            }
                                            ?>
                                        ><?= $v_client->name . ' ' . $client_status ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><strong><?= lang('locale') ?></strong></label>
                    <div class="col-lg-5">
                        <select class="  form-control select_box" style="width: 100%" name="locale">

                            <?php
                            $locales = $this->db->get('tbl_locales')->result();
                            foreach ($locales as $loc) :
                                ?>
                                <option lang="<?= $loc->code ?>" value="<?= $loc->locale ?>" <?php
                                if (!empty($profile_info)) {
                                    if ($profile_info->locale == $loc->locale) {
                                        echo 'selected';
                                    }
                                } else {
                                    echo($this->config->item('locale') == $loc->locale ? 'selected="selected"' : '');
                                }
                                ?>><?= $loc->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><strong><?= lang('language') ?></strong></label>
                    <div class="col-sm-5">
                        <select name="language" class="form-control select_box" style="width: 100%">
                            <?php foreach ($languages as $lang) : ?>
                                <option value="<?= $lang->name ?>"<?php
                                if (!empty($profile_info)) {
                                    if ($profile_info->language == $lang->name) {
                                        echo 'selected';
                                    }
                                } else {
                                    echo($this->config->item('language') == $lang->name ? ' selected="selected"' : '');
                                }
                                ?>><?= ucfirst($lang->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><strong><?= lang('phone') ?> </strong></label>
                    <div class="col-sm-5">
                        <input type="text" class="input-sm form-control" value="<?php
                        if (!empty($profile_info)) {
                            echo $profile_info->phone;
                        }
                        ?>" name="phone" placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_phone') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><strong><?= lang('mobile') ?> </strong></label>
                    <div class="col-sm-5">
                        <input type="text" class="input-sm form-control" value="<?php
                        if (!empty($profile_info)) {
                            echo $profile_info->mobile;
                        }
                        ?>" name="mobile"
                               placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_mobile') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><strong><?= lang('skype_id') ?> </strong></label>
                    <div class="col-sm-5">
                        <input type="text" class="input-sm form-control" value="<?php
                        if (!empty($profile_info)) {
                            echo $profile_info->skype;
                        }
                        ?>" name="skype" placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_skype') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><strong><?= lang('profile_photo') ?></strong><span
                            class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 210px;">
                                <?php
                                if (!empty($profile_info)) :
                                    ?>
                                    <img src="<?php echo base_url() . $profile_info->avatar; ?>">
                                <?php else: ?>
                                    <img src="http://placehold.it/350x260" alt="Please Connect Your Internet">
                                <?php endif; ?>
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail"
                                 style="width: 210px;"></div>
                            <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">
                                                <input type="file" name="avatar" value="upload"
                                                       data-buttonText="<?= lang('choose_file') ?>" id="myImg"/>
                                                <span class="fileinput-exists"><?= lang('change') ?></span>    
                                            </span>
                                            <a href="#" class="btn btn-default fileinput-exists"
                                               data-dismiss="fileinput"><?= lang('remove') ?></a>

                            </div>

                            <div id="valid_msg" style="color: #e11221"></div>

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-1"
                           class="col-sm-3 control-label"><strong><?= lang('user_type') ?></strong><span
                            class="required">*</span></label>
                    <div class="col-sm-5">
                        <select id="user_type" name="role_id" class="form-control" required>
                            <option value=""><?= lang('select_user_type') ?></option>
                            <option <?php
                            if (!empty($login_info)) {
                                echo $login_info->role_id == 1 ? 'selected' : '';
                            }
                            ?> value="1"><?= lang('admin') ?></option>
                            <option <?php
                            if (!empty($login_info)) {
                                echo $login_info->role_id == 3 ? 'selected' : '';
                            }
                            ?> value="3"><?= lang('staff') ?></option>
                            <option <?php
                            if (!empty($login_info)) {
                                echo $login_info->role_id == 2 ? 'selected' : '';
                            }
                            ?> value="2"><?= lang('client') ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="department">
                    <label class="col-sm-3 control-label"><strong><?= lang('designation') ?> </strong><span
                            class="text-danger">*</span></label>
                    <div class="col-sm-5">
                        <select class="form-control select_box department" style="width: 100%" name="designations_id">
                            <option value=""><?= lang('select') . ' ' . lang('designation'); ?></option>
                            <?php
                            if (!empty($all_designation_info)) {
                                foreach ($all_designation_info as $dept_name => $v_designation_info) {
                                    ?>
                                    <optgroup label="<?= $dept_name ?>">
                                        <?php if (!empty($v_designation_info)) {
                                            foreach ($v_designation_info as $v_designation) { ?>
                                                <option value="<?= $v_designation->designations_id ?>" <?php
                                                if (!empty($profile_info)) {
                                                    if ($profile_info->designations_id == $v_designation->designations_id) {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>><?= $v_designation->designations; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <div class="checkbox-inline c-checkbox">
                            <label class="needsclick">
                                <input name="department_head_id" value="1" type="checkbox"
                                       style="margin-right: 8px;" class="">
                                <span class="fa fa-check"></span>
                                <?= lang('is_he_department_head') ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="border-none">
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('permission') ?> <span
                            class="required">*</span></label>
                    <div class="col-sm-5">
                        <div class="checkbox c-radio needsclick">
                            <label class="needsclick">
                                <input id="" <?php
                                if (!empty($login_info->permission) && $login_info->permission == 'all') {
                                    echo 'checked';
                                } elseif (empty($login_info)) {
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
                                if (!empty($login_info->permission) && $login_info->permission != 'all') {
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
                if (!empty($login_info->permission) && $login_info->permission != 'all') {
                    echo 'show';
                }
                ?>" id="permission_user_1">
                    <label for="field-1"
                           class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('users') ?>
                        <span
                            class="required">*</span></label>
                    <div class="col-sm-5">
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
                                            if (!empty($login_info->permission) && $login_info->permission != 'all') {
                                                $get_permission = json_decode($login_info->permission);
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

                                if (!empty($login_info->permission) && $login_info->permission != 'all') {
                                    $get_permission = json_decode($login_info->permission);

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

                                            if (!empty($login_info->permission) && $login_info->permission != 'all') {
                                                $get_permission = json_decode($login_info->permission);

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

                                            if (!empty($login_info->permission) && $login_info->permission != 'all') {
                                                $get_permission = json_decode($login_info->permission);
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
                    <label class="col-sm-3"></label>
                    <div class="col-sm-5">
                        <button type="submit" id="sbtn"
                                class="btn btn-primary"><?php echo !empty($user_id) ? lang('update_user') : lang('create_user') ?></button>
                    </div>
                </div>


        </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#department').hide();
        var user_flag = document.getElementById("user_type").value;
        // on change user type select action
        $('#user_type').on('change', function () {
            if (this.value == '3' || this.value == '1') {
                $("#department").show();
                $(".department").removeAttr('disabled');
            }
            else {
                $("#department").hide();
                $(".department").attr('disabled', 'disabled');
            }
        });
    });</script>