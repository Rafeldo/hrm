<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title"
            id="myModalLabel"><?= lang('new') . ' ' . lang('training') ?></h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <form id="form_validation" enctype="multipart/form-data" action="<?php echo base_url() ?>admin/training/save_training/<?php
        if (!empty($training_info->training_id)) {
            echo $training_info->training_id;
        }
        ?>" method="post" class="form-horizontal">
            <div class="form-group" id="border-none">
                <label for="field-1" class="col-sm-3 control-label"><?= lang('employee') ?> <span
                        class="required">*</span></label>
                <div class="col-sm-5">
                    <select name="user_id" style="width: 100%" id="employee"
                            class="form-control select_box">
                        <option value=""><?= lang('select_employee') ?>...</option>
                        <?php if (!empty($all_employee)): ?>
                            <?php foreach ($all_employee as $dept_name => $v_all_employee) : ?>
                                <optgroup label="<?php echo $dept_name; ?>">
                                    <?php if (!empty($v_all_employee)):foreach ($v_all_employee as $v_employee) : ?>
                                        <option value="<?php echo $v_employee->user_id; ?>"
                                            <?php
                                            if (!empty($award_info->user_id)) {
                                                $user_id = $award_info->user_id;
                                            } else {
                                                $user_id = $this->session->userdata('user_id');
                                            }
                                            if (!empty($user_id)) {
                                                echo $v_employee->user_id == $user_id ? 'selected' : '';
                                            }
                                            ?>><?php echo $v_employee->fullname . ' ( ' . $v_employee->designations . ' )' ?></option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label"><?= lang('course_training') ?> <span
                        class="required">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="training_name" required class="form-control" value="<?php
                    if (!empty($training_info->training_name)) {
                        echo $training_info->training_name;
                    }
                    ?>"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label"><?= lang('vendor') ?> <span class="required">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="vendor_name" class="form-control" value="<?php
                    if (!empty($training_info->vendor_name)) {
                        echo $training_info->vendor_name;
                    }
                    ?>" required/>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3"><?= lang('start_date') ?><span
                        class="required">*</span></label>
                <div class="col-sm-5">
                    <div class="input-group ">
                        <input type="text" name="start_date" value="<?php
                        if (!empty($training_info->start_date)) {
                            echo $training_info->start_date;
                        }
                        ?>" class="form-control datepicker" required>
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3"><?= lang('finish_date') ?><span
                        class="required">*</span></label>
                <div class="col-sm-5">
                    <div class="input-group">
                        <input type="text" name="finish_date" value="<?php
                        if (!empty($training_info->finish_date)) {
                            echo $training_info->finish_date;
                        }
                        ?>" class="form-control datepicker" required>
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label"><?= lang('training_cost') ?></label>
                <div class="col-sm-5">
                    <input type="text" name="training_cost" class="form-control" value="<?php
                    if (!empty($training_info->training_cost)) {
                        echo $training_info->training_cost;
                    }
                    ?>"/>
                </div>
            </div>

            <div class="form-group" id="border-none">
                <label for="field-1" class="col-sm-3 control-label"><?= lang('status') ?> <span
                        class="required">*</span></label>
                <div class="col-sm-5">
                    <select name="status" class="form-control" required>
                        <option
                            value="0 <?php if (!empty($training_info->status)) echo $training_info->status == 0 ? 'selected' : '' ?>">
                            <?= lang('pending') ?>
                        </option>
                        <option
                            value="1 <?php if (!empty($training_info->status)) echo $training_info->status == 1 ? 'selected' : '' ?>">
                            <?= lang('started') ?>
                        </option>
                        <option
                            value="2 <?php if (!empty($training_info->status)) echo $training_info->status == 2 ? 'selected' : '' ?>">
                            <?= lang('completed') ?>
                        </option>
                        <option
                            value="3 <?php if (!empty($training_info->status)) echo $training_info->status == 3 ? 'selected' : '' ?>">
                            <?= lang('terminated') ?>

                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group" id="border-none">
                <label for="field-1" class="col-sm-3 control-label"><?= lang('performance') ?></label>
                <div class="col-sm-5">
                    <select name="performance" id="employee" class="form-control">
                        <option
                            value="0 <?php if (!empty($training_info->performance)) echo $training_info->performance == 0 ? 'selected' : '' ?>">
                            <?= lang('not_concluded') ?>
                        </option>
                        <option
                            value="1 <?php if (!empty($training_info->performance)) echo $training_info->performance == 1 ? 'selected' : '' ?>">
                            <?= lang('satisfactory') ?>

                        </option>
                        <option
                            value="2 <?php if (!empty($training_info->performance)) echo $training_info->performance == 2 ? 'selected' : '' ?>">
                            <?= lang('average') ?>
                        </option>
                        <option
                            value="3 <?php if (!empty($training_info->performance)) echo $training_info->performance == 3 ? 'selected' : '' ?>">
                            <?= lang('poor') ?>
                        </option>
                        <option
                            value="4 <?php if (!empty($training_info->performance)) echo $training_info->performance == 4 ? 'selected' : '' ?>">
                            <?= lang('excellent') ?>
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label"><?= lang('remarks') ?></label>
                <div class="col-sm-8">
                                        <textarea class="form-control textarea_2" name="remarks"><?php
                                            if (!empty($training_info->remarks)) {
                                                echo $training_info->remarks;
                                            }
                                            ?></textarea>
                </div>
            </div>
            <div id="add_new">
                <div class="form-group" style="margin-bottom: 0px">
                    <label for="field-1"
                           class="col-sm-3 control-label"><?= lang('attachment') ?></label>
                    <div class="col-sm-4">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <?php
                            if (!empty($training_info->upload_file)) {
                                $uploaded_file = json_decode($training_info->upload_file);
                            }
                            if (!empty($uploaded_file)):foreach ($uploaded_file as $v_files_image): ?>
                                <div class="">
                                    <input type="hidden" name="path[]"
                                           value="<?php echo $v_files_image->path ?>">
                                    <input type="hidden" name="fileName[]"
                                           value="<?php echo $v_files_image->fileName ?>">
                                    <input type="hidden" name="fullPath[]"
                                           value="<?php echo $v_files_image->fullPath ?>">
                                    <input type="hidden" name="size[]"
                                           value="<?php echo $v_files_image->size ?>">
                                    <input type="hidden" name="is_image[]"
                                           value="<?php echo $v_files_image->is_image ?>">
                                    <span class=" btn btn-default btn-file">
                                    <span class="fileinput-filename"> <?php echo $v_files_image->fileName ?></span>
                                    <a href="javascript:void(0);" class="remCFile" style="float: none;">×</a>
                                    </span>
                                    <strong>
                                        <a href="javascript:void(0);" class="RCF"><i
                                                class="fa fa-times"></i>&nbsp;<?= lang('remove') ?></a></strong>
                                    <p></p>
                                </div>

                            <?php endforeach; ?>
                            <?php else: ?>
                                <span class="btn btn-default btn-file"><span
                                        class="fileinput-new"><?= lang('select_file') ?></span>
                                                            <span class="fileinput-exists"><?= lang('change') ?></span>
                                                            <input type="file" name="upload_file[]">
                                                        </span>
                                <span class="fileinput-filename"></span>
                                <a href="#" class="close fileinput-exists" data-dismiss="fileinput"
                                   style="float: none;">&times;</a>
                            <?php endif; ?>
                        </div>
                        <div id="msg_pdf" style="color: #e11221"></div>
                    </div>
                    <div class="col-sm-2">
                        <strong><a href="javascript:void(0);" id="add_more" class="addCF "><i
                                    class="fa fa-plus"></i>&nbsp;<?= lang('add_more') ?>
                            </a></strong>
                    </div>
                </div>
            </div>
            <div class="form-group" id="border-none">
                <label for="field-1" class="col-sm-3 control-label"><?= lang('permission') ?> <span
                        class="required">*</span></label>
                <div class="col-sm-9">
                    <div class="checkbox c-radio needsclick">
                        <label class="needsclick">
                            <input id="" <?php
                            if (!empty($training_info->permission) && $training_info->permission == 'all') {
                                echo 'checked';
                            } elseif (empty($training_info)) {
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
                            if (!empty($training_info->permission) && $training_info->permission != 'all') {
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
            if (!empty($training_info->permission) && $training_info->permission != 'all') {
                echo 'show';
            }
            ?>" id="permission_user">
                <label for="field-1"
                       class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('users') ?>
                    <span
                        class="required">*</span></label>
                <div class="col-sm-9">
                    <?php
                    if (!empty($assign_user)) {
                        foreach ($assign_user as $key => $v_user) {

                            if ($v_user->role_id == 1) {
                                $disable = true;
                                $role = '<strong class="badge btn-danger">' . lang('admin') . '</strong>';
                            } else {
                                $disable = false;
                                $role = '<strong class="badge btn-primary">' . lang('staff') . '</strong>';
                            }

                            ?>
                            <div class="checkbox c-checkbox needsclick">
                                <label class="needsclick">
                                    <input type="checkbox"
                                        <?php
                                        if (!empty($training_info->permission) && $training_info->permission != 'all') {
                                            $get_permission = json_decode($training_info->permission);
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
                            <div class="action p
                                                <?php

                            if (!empty($training_info->permission) && $training_info->permission != 'all') {
                                $get_permission = json_decode($training_info->permission);

                                foreach ($get_permission as $user_id => $v_permission) {
                                    if ($user_id == $v_user->user_id) {
                                        echo 'show';
                                    }
                                }

                            }
                            ?>
                                                " id="action_<?= $v_user->user_id ?>">
                                <label class="checkbox-inline c-checkbox">
                                    <input id="<?= $v_user->user_id ?>" checked type="checkbox"
                                           name="action_<?= $v_user->user_id ?>[]"
                                           disabled
                                           value="view">
                                                        <span
                                                            class="fa fa-check"></span><?= lang('can') . ' ' . lang('view') ?>
                                </label>
                                <label class="checkbox-inline c-checkbox">
                                    <input <?php if (!empty($disable)) {
                                        echo 'disabled' . ' ' . 'checked';
                                    } ?> id="<?= $v_user->user_id ?>"
                                        <?php

                                        if (!empty($training_info->permission) && $training_info->permission != 'all') {
                                            $get_permission = json_decode($training_info->permission);

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
                                    <input <?php if (!empty($disable)) {
                                        echo 'disabled' . ' ' . 'checked';
                                    } ?> id="<?= $v_user->user_id ?>"
                                        <?php

                                        if (!empty($training_info->permission) && $training_info->permission != 'all') {
                                            $get_permission = json_decode($training_info->permission);
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
                <div class="col-sm-offset-3 col-sm-2">
                    <button type="submit" id="sbtn" class="btn btn-primary btn-block"><?= lang('save') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more").click(function () {

            var add_new = $('<div class="form-group" style="margin-bottom: 0px">\n\
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('upload_file') ?></label>\n\
        <div class="col-sm-4">\n\
        <div class="fileinput fileinput-new" data-provides="fileinput">\n\
<span class="btn btn-default btn-file"><span class="fileinput-new" ><?= lang('select_file') ?></span><span class="fileinput-exists" >Change</span><input type="file" name="upload_file[]" ></span> <span class="fileinput-filename"></span><a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none;">&times;</a></div></div>\n\<div class="col-sm-2">\n\<strong>\n\
<a href="javascript:void(0);" class="remCF"><i class="fa fa-times"></i>&nbsp;<?= lang('remove')?></a></strong></div>');
            maxAppend++;
            $("#add_new").append(add_new);

        });

        $("#add_new").on('click', '.remCF', function () {
            $(this).parent().parent().parent().remove();
        });
        $('a.RCF').click(function () {
            $(this).parent().parent().remove();
        });
    });
</script>
