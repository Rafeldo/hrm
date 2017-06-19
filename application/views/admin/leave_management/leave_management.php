<?php include_once 'asset/admin-ajax.php'; ?>
<?= message_box('success'); ?>
<?= message_box('error'); ?>
<div class="row mt-lg">
    <div class="col-sm-3">
        <ul class="nav nav-pills nav-stacked navbar-custom-nav">
            <?php if (!empty($view)) { ?>
                <li class="<?= $active == 4 ? 'active' : '' ?>"><a href="#view_details"
                                                                   data-toggle="tab"><?= lang('application_details') ?></a>
                </li>
            <?php } ?>
            <li class="<?= $active == 1 ? 'active' : '' ?>"><a href="#my_leave"
                                                               data-toggle="tab"><?= lang('my_leave') ?></a></li>

            <?php if ($this->session->userdata('user_type') == 1) { ?>
                <li class="<?= $active == 2 ? 'active' : '' ?>"><a href="#all_leave"
                                                                   data-toggle="tab"><?= lang('all_leave') ?></a></li>
            <?php } ?>
            <li class="<?= $active == 3 ? 'active' : '' ?>"><a href="#leave_report"
                                                               data-toggle="tab"><?= lang('leave_report') ?></a>
        </ul>
    </div>
    <div class="col-sm-9">

        <div class="tab-content" style="border: 0;padding:0;">
            <?php if (!empty($view)) {
                $leave_cate_info = $this->db->where('leave_category_id', $application_info->leave_category_id)->get('tbl_leave_category')->row();
                $profile_info = $this->db->where('user_id', $application_info->user_id)->get('tbl_account_details')->row();
                $approve_by = $this->db->where('user_id', $application_info->approve_by)->get('tbl_account_details')->row();

                if ($application_info->application_status == '1') {
                    $text = lang('pending');
                    $ribbon = 'warning';
                } elseif ($application_info->application_status == '2') {
                    $text = lang('approved');
                    $ribbon = 'success';
                } else {
                    $text = lang('rejected');
                    $ribbon = 'danger';
                }

                ?>
                <div class="tab-pane <?= $active == 4 ? 'active' : '' ?>" id="view_details" style="position: relative;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="panel panel-custom">
                                <!-- Default panel contents -->
                                <div class="panel-heading">
                                    <div class="panel-title">
                                        <strong><?= $profile_info->fullname . ' ' . lang('leave_from') . '<span class="text-danger"> '
                                            . strftime(config_item('date_format'), strtotime($application_info->leave_start_date)) . '</span> ' . lang('leave_to') . '<span class="text-danger"> ' . strftime(config_item('date_format'), strtotime($application_info->leave_end_date)) . '</span>
                                        ' ?></strong>
                                    </div>
                                </div>
                                <div class="panel-body row form-horizontal task_details">
                                    <div class="r9 ribbon <?php
                                    if (!empty($ribbon)) {
                                        echo $ribbon;
                                    } else {
                                        echo 'primary';
                                    }
                                    ?>"><span><?= $text
                                            ?></span></div>
                                    <div class="form-group ">
                                        <label class="control-label col-sm-4"><strong><?= lang('leave_category') ?>
                                                :</strong></label>
                                        <div class="col-sm-8">
                                            <?php if (!empty($leave_cate_info)) { ?>
                                                <p class="form-control-static "><?= ($leave_cate_info->leave_category) ?></p>
                                            <?php }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label class="control-label col-sm-4"><strong><?= lang('start_date') ?>
                                                :</strong></label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static "><?= strftime(config_item('date_format'), strtotime($application_info->leave_start_date)) ?></p>

                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label class="control-label col-sm-4"><strong><?= lang('end_date') ?>
                                                :</strong></label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static "><?= strftime(config_item('date_format'), strtotime($application_info->leave_end_date)) ?></p>

                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label class="control-label col-sm-4"><strong><?= lang('applied_on') ?>
                                                :</strong></label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static "><?= strftime(config_item('date_format'), strtotime($application_info->application_date)) . lang('at') . date('H:i A', strtotime($application_info->application_date)); ?></p>
                                        </div>
                                    </div>
                                    <?php if (!empty($approve_by)) { ?>
                                        <div class="form-group ">
                                            <label class="control-label col-sm-4"><strong><?= lang('approved_by') ?>
                                                    :</strong></label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static "><?= $approve_by->fullname ?></p>

                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="form-group ">
                                        <label class="control-label col-sm-4"><strong><?= lang('reason') ?>
                                                :</strong></label>
                                        <div class="col-sm-8">
                                            <blockquote
                                                style="font-size: 12px; margin-top: 5px"><?= nl2br($application_info->reason) ?></blockquote>
                                        </div>
                                    </div>
                                    <?php if (!empty($approve_by)) { ?>
                                        <div class="form-group ">
                                            <label class="control-label col-sm-4"><strong><?= lang('comments') ?>
                                                    :</strong></label>
                                            <div class="col-sm-8">
                                                <blockquote
                                                    style="font-size: 12px; margin-top: 5px"><?= nl2br($application_info->comments) ?></blockquote>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($application_info->attachment)) {
                                        $fileinfo = json_decode($application_info->attachment);
                                        if (!empty($fileinfo)) {
                                            foreach ($fileinfo as $key => $v_files) { ?>

                                            <?php }
                                        }
                                        ?>
                                        <div class="form-group ">
                                            <label class="control-label col-sm-4"><strong><?= lang('attachment') ?>
                                                    :</strong></label>
                                            <div class="col-sm-8">
                                                <a class="btn btn-xs btn-dark"
                                                   data-toggle="tooltip"
                                                   data-placement="top"
                                                   title="Download"
                                                   href="<?= base_url() ?>admin/leave_management/download_files/<?= $application_info->leave_application_id ?>/<?= $v_files->fileName ?>">
                                                    <p class="form-control-static "><?= $key + 1 . '. ' . $v_files->fileName ?></p>
                                                </a>

                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($application_info->application_status != '2') { ?>

                                        <div class="form-group ">
                                            <label
                                                class="control-label col-sm-4"><strong><?= lang('change') . ' ' . lang('status') ?>
                                                    :</strong></label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static ">
                                                    <?php
                                                    if ($application_info->application_status == '1') { ?>
                                                        <span data-toggle="tooltip" data-placment="top"
                                                              title="<?= lang('approved_alert') ?>">
                                                    <a data-toggle="modal" data-target="#myModal"
                                                       href="<?= base_url() ?>admin/leave_management/change_status/2/<?= $application_info->leave_application_id; ?>"
                                                       class="btn btn-success ml"><i
                                                            class="fa fa-thumbs-o-up"></i> <?= lang('approved') ?></a>
                                                        </span>
                                                        <a data-toggle="modal" data-target="#myModal"
                                                           href="<?= base_url() ?>admin/leave_management/change_status/3/<?= $application_info->leave_application_id; ?>"
                                                           class="btn btn-danger ml"><i
                                                                class="fa fa-times"></i> <?= lang('reject') ?></a>
                                                    <?php } elseif ($application_info->application_status == '3') { ?>
                                                        <span data-toggle="tooltip" data-placment="top"
                                                              title="<?= lang('approved_alert') ?>">
                                                    <a data-toggle="modal" data-target="#myModal"
                                                       href="<?= base_url() ?>admin/leave_management/change_status/2/<?= $application_info->leave_application_id; ?>"
                                                       class="btn btn-success ml"><i
                                                            class="fa fa-thumbs-o-up"></i> <?= lang('approved') ?></a>
                                                        </span>
                                                        <a data-toggle="modal" data-target="#myModal"
                                                           href="<?= base_url() ?>admin/leave_management/change_status/1/<?= $application_info->leave_application_id; ?>"
                                                           class="btn btn-warning ml"><i
                                                                class="fa fa-times"></i> <?= lang('pending') ?></a>
                                                    <?php }
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="panel panel-custom">
                                <!-- Default panel contents -->
                                <div class="panel-heading">
                                    <div class="panel-title">
                                        <strong
                                            class="text-sm"><?= lang('details_of') . ' ' . $profile_info->fullname ?></strong>
                                    </div>
                                </div>
                                <table class="table">
                                    <tbody>
                                    <?php
                                    $all_leave_info = $this->db->get('tbl_leave_category')->result();
                                    $num_of_leave = 0;
                                    $total = 0;
                                    if (!empty($all_leave_info)):foreach ($all_leave_info as $key => $v_leave_info):
                                        $this->admin_model->_table_name = 'tbl_leave_application';
                                        $this->admin_model->_order_by = "user_id";
                                        $total_leave = $this->admin_model->get_by(array('user_id' => $application_info->user_id, 'leave_category_id' => $v_leave_info->leave_category_id, 'application_status' => '2'), FALSE);
                                        $total_days = 0;
                                        if (!empty($total_leave)) {
                                            $ge_days = 0;
                                            $m_days = 0;
                                            foreach ($total_leave as $v_leave) {
                                                $month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($v_leave->leave_start_date)), date('Y', strtotime($v_leave->leave_start_date)));

                                                $datetime1 = new DateTime($v_leave->leave_start_date);

                                                $datetime2 = new DateTime($v_leave->leave_end_date);
                                                $difference = $datetime1->diff($datetime2);

                                                if ($difference->m != 0) {
                                                    $m_days += $month;
                                                } else {
                                                    $m_days = 0;
                                                }
                                                $ge_days += $difference->d + 1;
                                                $total_days = $m_days + $ge_days;
                                            }
                                        }
                                        $num_of_leave += $v_leave_info->leave_quota;
                                        ?>
                                        <tr>
                                            <td><strong> <?= $v_leave_info->leave_category ?></strong>:</td>
                                            <td>
                                                <?php
                                                if (empty($total_days)) {
                                                    $total_days = 0;
                                                } else {
                                                    $total_days = $total_days;
                                                }
                                                $total += $total_days;
                                                ?>
                                                <?= $total_days ?>/<?= $v_leave_info->leave_quota; ?> </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                    endif;
                                    ?>
                                    <tr>
                                        <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;">
                                            <strong> <?= lang('total') ?></strong>:
                                        </td>
                                        <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;"> <?= $total; ?>
                                            /<?= $num_of_leave; ?> </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- Task Details tab Starts -->
            <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="my_leave" style="position: relative;">
                <div class="nav-tabs-custom">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs">
                        <li class="<?= $leave_active == 1 ? 'active' : ''; ?>">
                            <a href="#manage" data-toggle="tab"><?= lang('my_leave') ?></a>
                        </li>
                        <li class="<?= $leave_active == 2 ? 'active' : ''; ?>">
                            <a href="#create" data-toggle="tab"><?= lang('new_leave') ?></a>
                        </li>
                    </ul>
                    <div class="tab-content bg-white">
                        <!-- ************** general *************-->
                        <div class="tab-pane <?= $leave_active == 1 ? 'active' : ''; ?>" id="manage">

                            <div class="table-responsive">
                                <table class="table table-striped DataTables " id="">
                                    <thead>
                                    <tr>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('start_date') ?></th>
                                        <th><?= lang('end_date') ?></th>
                                        <th><?= lang('leave_category') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <?php if ($this->session->userdata('user_type') == 1) { ?>
                                            <th class="col-sm-2"><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $my_leave_application = $this->db->where('user_id', $this->session->userdata('user_id'))->get('tbl_leave_application')->result();
                                    if (!empty($my_leave_application)) {
                                        foreach ($my_leave_application as $v_my_leave):
                                            $my_profile = $this->db->where('user_id', $v_my_leave->user_id)->get('tbl_account_details')->row();
                                            $my_leave_category = $this->db->where('leave_category_id', $v_my_leave->leave_category_id)->get('tbl_leave_category')->row();
                                            ?>
                                            <tr>
                                                <td><?= $my_profile->fullname ?></td>
                                                <td><?= strftime(config_item('date_format'), strtotime($v_my_leave->leave_start_date)) ?></td>
                                                <td><?= strftime(config_item('date_format'), strtotime($v_my_leave->leave_end_date)) ?></td>
                                                <td><?= $v_my_leave->leave_category ?></td>
                                                <td><?php
                                                    if ($v_my_leave->application_status == '1') {
                                                        echo '<span class="label label-warning">' . lang('pending') . '</span>';
                                                    } elseif ($v_my_leave->application_status == '2') {
                                                        echo '<span class="label label-success">' . lang('accepted') . '</span>';
                                                    } else {
                                                        echo '<span class="label label-danger">' . lang('rejected') . '</span>';
                                                    }
                                                    ?></td>
                                                <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                    <td>
                                                        <?php echo btn_view('admin/leave_management/index/view_details/' . $v_my_leave->leave_application_id) ?>
                                                        <?php if ($v_my_leave->application_status != '2') { ?>
                                                            <?php echo btn_delete('admin/leave_management/delete_application/' . $v_my_leave->leave_application_id) ?>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                        endforeach;
                                    }
                                    ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane <?= $leave_active == 2 ? 'active' : ''; ?>" id="create">
                            <div class="row">
                                <div class="col-sm-8">
                                    <form id="form"
                                          action="<?php echo base_url() ?>admin/leave_management/save_leave_application"
                                          method="post" enctype="multipart/form-data" class="form-horizontal">
                                        <div class="panel_controls">
                                            <div class="form-group">
                                                <label for="field-1"
                                                       class="col-sm-4 control-label"><?= lang('leave_category') ?>
                                                    <span
                                                        class="required"> *</span></label>

                                                <div class="col-sm-8">
                                                    <select name="leave_category_id" style="width: 100%"
                                                            class="form-control select_box"
                                                            id="leave_category" required>
                                                        <option
                                                            value=""><?= lang('select') . ' ' . lang('leave_category') ?></option>
                                                        <?php
                                                        $all_leave_category = $this->db->get('tbl_leave_category')->result();
                                                        if (!empty($all_leave_category)) {
                                                            foreach ($all_leave_category as $v_category) : ?>
                                                                <option
                                                                    value="<?php echo $v_category->leave_category_id ?>">
                                                                    <?php echo $v_category->leave_category ?></option>
                                                            <?php endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <input type="hidden" id="user_id"
                                                       value="<?php echo $this->session->userdata('user_id') ?>">
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-10">
                                                    <div class="required" id="username_result"></div>
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"><?= lang('start_date') ?>
                                                    <span
                                                        class="required"> *</span></label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <input type="text" name="leave_start_date" id="start_date"
                                                               required class="form-control datepicker" value=""
                                                               data-format="dd-mm-yyyy">
                                                        <div class="input-group-addon">
                                                            <a href="#"><i class="fa fa-calendar"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"><?= lang('end_date') ?> <span
                                                        class="required"> *</span></label>

                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <input type="text" name="leave_end_date" id="end_date"
                                                               onchange="check_available_leave(this.value)" required
                                                               class="form-control datepicker" value=""
                                                               data-format="dd-mm-yyyy">
                                                        <div class="input-group-addon">
                                                            <a href="#"><i class="fa fa-calendar"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="field-1"
                                                       class="col-sm-4 control-label"><?= lang('reason') ?></label>

                                                <div class="col-sm-8">
                                                    <textarea id="present" name="reason" class="form-control"
                                                              rows="6"></textarea>
                                                </div>
                                            </div>
                                            <div id="add_new">
                                                <div class="form-group" style="margin-bottom: 0px">
                                                    <label for="field-1"
                                                           class="col-sm-4 control-label"><?= lang('attachment') ?></label>
                                                    <div class="col-sm-5">
                                                        <div class="fileinput fileinput-new"
                                                             data-provides="fileinput">
                                                            <?php
                                                            if (!empty($tickets_info->upload_file)) {
                                                                $uploaded_file = json_decode($tickets_info->upload_file);
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
                                                                                class="fa fa-times"></i>&nbsp;Remove</a></strong>
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
                                                                <a href="#" class="close fileinput-exists"
                                                                   data-dismiss="fileinput"
                                                                   style="float: none;">&times;</a>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div id="msg_pdf" style="color: #e11221"></div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <strong><a href="javascript:void(0);" id="add_more"
                                                                   class="addCF "><i
                                                                    class="fa fa-plus"></i>&nbsp;<?= lang('add_more') ?>
                                                            </a></strong>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <div class="col-sm-offset-4 col-sm-5">
                                                    <button type="submit" id="sbtn" name="sbtn" value="1"
                                                            class="btn btn-primary">Submit
                                                    </button>
                                                </div>
                                            </div>
                                            <br/>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-4">
                                    <div class="panel panel-custom">
                                        <!-- Default panel contents -->
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <strong><?= lang('my_leave') . ' ' . lang('details') ?></strong>
                                            </div>
                                        </div>
                                        <table class="table">
                                            <tbody>
                                            <?php
                                            $all_leave_info = $this->db->get('tbl_leave_category')->result();

                                            $num_of_leave = 0;
                                            $total = 0;
                                            if (!empty($all_leave_info)):foreach ($all_leave_info as $key => $v_leave_info):
                                                $this->admin_model->_table_name = 'tbl_leave_application';
                                                $this->admin_model->_order_by = "user_id";
                                                $total_leave = $this->admin_model->get_by(array('user_id' => $this->session->userdata('user_id'), 'leave_category_id' => $v_leave_info->leave_category_id, 'application_status' => '2'), FALSE);
                                                $total_days = 0;
                                                if (!empty($total_leave)) {
                                                    $ge_days = 0;
                                                    $m_days = 0;
                                                    foreach ($total_leave as $v_leave) {
                                                        $month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($v_leave->leave_start_date)), date('Y', strtotime($v_leave->leave_start_date)));

                                                        $datetime1 = new DateTime($v_leave->leave_start_date);

                                                        $datetime2 = new DateTime($v_leave->leave_end_date);
                                                        $difference = $datetime1->diff($datetime2);

                                                        if ($difference->m != 0) {
                                                            $m_days += $month;
                                                        } else {
                                                            $m_days = 0;
                                                        }
                                                        $ge_days += $difference->d + 1;
                                                        $total_days = $m_days + $ge_days;
                                                    }
                                                }
                                                $num_of_leave += $v_leave_info->leave_quota;
                                                ?>
                                                <tr>
                                                    <td><strong> <?= $v_leave_info->leave_category ?></strong>:</td>
                                                    <td>
                                                        <?php
                                                        if (empty($total_days)) {
                                                            $total_days = 0;
                                                        } else {
                                                            $total_days = $total_days;
                                                        }
                                                        $total += $total_days;
                                                        ?>
                                                        <?= $total_days ?>/<?= $v_leave_info->leave_quota; ?> </td>
                                                </tr>
                                                <?php
                                            endforeach;
                                            endif;
                                            ?>
                                            <tr>
                                                <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;">
                                                    <strong> <?= lang('total') ?></strong>:
                                                </td>
                                                <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;"> <?= $total; ?>
                                                    /<?= $num_of_leave; ?> </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <?php if ($this->session->userdata('user_type') == 1) { ?>
                <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="all_leave" style="position: relative;">
                    <div class="nav-tabs-custom">
                        <!-- Tabs within a box -->
                        <ul class="nav nav-tabs">
                            <li class="<?= $leave_active == 1 ? 'active' : ''; ?>">
                                <a href="#all_manage" data-toggle="tab"><?= lang('all_leave') ?></a>
                            </li>
                            <li class="<?= $leave_active == 2 ? 'active' : ''; ?>">
                                <a href="#all_create" data-toggle="tab"><?= lang('new_leave') ?></a>
                            </li>
                        </ul>
                        <div class="tab-content bg-white">
                            <!-- ************** general *************-->
                            <div class="tab-pane <?= $leave_active == 1 ? 'active' : ''; ?>" id="all_manage">

                                <div class="table-responsive">
                                    <table class="table table-striped DataTables " id="">
                                        <thead>
                                        <tr>
                                            <th><?= lang('name') ?></th>
                                            <th><?= lang('start_date') ?></th>
                                            <th><?= lang('end_date') ?></th>
                                            <th><?= lang('leave_category') ?></th>
                                            <th><?= lang('status') ?></th>
                                            <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                <th class="col-sm-2"><?= lang('action') ?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $all_leave_application = $this->db->get('tbl_leave_application')->result();
                                        if (!empty($all_leave_application)) {
                                            foreach ($all_leave_application as $v_all_leave):
                                                $my_profile = $this->db->where('user_id', $v_all_leave->user_id)->get('tbl_account_details')->row();
                                                $my_leave_category = $this->db->where('leave_category_id', $v_all_leave->leave_category_id)->get('tbl_leave_category')->row();
                                                ?>
                                                <tr>
                                                    <td><?= $my_profile->fullname ?></td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_all_leave->leave_start_date)) ?></td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_all_leave->leave_end_date)) ?></td>
                                                    <td><?= $v_all_leave->leave_category ?></td>
                                                    <td><?php
                                                        if ($v_all_leave->application_status == '1') {
                                                            echo '<span class="label label-warning">' . lang('pending') . '</span>';
                                                        } elseif ($v_all_leave->application_status == '2') {
                                                            echo '<span class="label label-success">' . lang('accepted') . '</span>';
                                                        } else {
                                                            echo '<span class="label label-danger">' . lang('rejected') . '</span>';
                                                        }
                                                        ?></td>
                                                    <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                        <td>
                                                            <?php echo btn_view('admin/leave_management/index/view_details/' . $v_all_leave->leave_application_id) ?>
                                                            <?php if ($v_all_leave->application_status != '2') { ?>
                                                                <?php echo btn_delete('admin/leave_management/delete_application/' . $v_all_leave->leave_application_id) ?>
                                                            <?php } ?>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                                <?php
                                            endforeach;
                                        }
                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane <?= $leave_active == 2 ? 'active' : ''; ?>" id="all_create">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <form id="form"
                                              action="<?php echo base_url() ?>admin/leave_management/save_leave_application"
                                              method="post" enctype="multipart/form-data" class="form-horizontal">
                                            <div class="panel_controls">
                                                <div class="form-group">
                                                    <label for="field-1"
                                                           class="col-sm-4 control-label"><?= lang('leave_category') ?>
                                                        <span
                                                            class="required"> *</span></label>

                                                    <div class="col-sm-8">
                                                        <select name="leave_category_id" style="width: 100%"
                                                                class="form-control select_box"
                                                                id="leave_category" required>
                                                            <option
                                                                value=""><?= lang('select') . ' ' . lang('leave_category') ?></option>
                                                            <?php
                                                            $all_leave_category = $this->db->get('tbl_leave_category')->result();
                                                            if (!empty($all_leave_category)) {
                                                                foreach ($all_leave_category as $v_category) : ?>
                                                                    <option
                                                                        value="<?php echo $v_category->leave_category_id ?>">
                                                                        <?php echo $v_category->leave_category ?></option>
                                                                <?php endforeach;
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <input type="hidden" id="user_id"
                                                           value="<?php echo $this->session->userdata('user_id') ?>">
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-10">
                                                        <div class="required" id="username_result"></div>
                                                    </div>

                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label"><?= lang('start_date') ?>
                                                        <span
                                                            class="required"> *</span></label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <input type="text" name="leave_start_date" id="start_date"
                                                                   required class="form-control datepicker" value=""
                                                                   data-format="dd-mm-yyyy">
                                                            <div class="input-group-addon">
                                                                <a href="#"><i class="fa fa-calendar"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label"><?= lang('end_date') ?> <span
                                                            class="required"> *</span></label>

                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <input type="text" name="leave_end_date" id="end_date"
                                                                   onchange="check_available_leave(this.value)" required
                                                                   class="form-control datepicker" value=""
                                                                   data-format="dd-mm-yyyy">
                                                            <div class="input-group-addon">
                                                                <a href="#"><i class="fa fa-calendar"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="field-1"
                                                           class="col-sm-4 control-label"><?= lang('reason') ?></label>

                                                    <div class="col-sm-8">
                                                    <textarea id="present" name="reason" class="form-control"
                                                              rows="6"></textarea>
                                                    </div>
                                                </div>
                                                <div id="add_new">
                                                    <div class="form-group" style="margin-bottom: 0px">
                                                        <label for="field-1"
                                                               class="col-sm-4 control-label"><?= lang('attachment') ?></label>
                                                        <div class="col-sm-5">
                                                            <div class="fileinput fileinput-new"
                                                                 data-provides="fileinput">
                                                                <?php
                                                                if (!empty($tickets_info->upload_file)) {
                                                                    $uploaded_file = json_decode($tickets_info->upload_file);
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
                                    </span><strong>
                                                                            <a href="javascript:void(0);" class="RCF"><i
                                                                                    class="fa fa-times"></i>&nbsp;Remove</a></strong>
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
                                                                    <a href="#" class="close fileinput-exists"
                                                                       data-dismiss="fileinput"
                                                                       style="float: none;">&times;</a>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div id="msg_pdf" style="color: #e11221"></div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <strong><a href="javascript:void(0);" id="add_more"
                                                                       class="addCF "><i
                                                                        class="fa fa-plus"></i>&nbsp;<?= lang('add_more') ?>
                                                                </a></strong>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <div class="col-sm-offset-4 col-sm-5">
                                                        <button type="submit" id="sbtn" name="sbtn" value="1"
                                                                class="btn btn-primary">Submit
                                                        </button>
                                                    </div>
                                                </div>
                                                <br/>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="panel panel-custom">
                                            <!-- Default panel contents -->
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <strong><?= lang('my_leave') . ' ' . lang('details') ?></strong>
                                                </div>
                                            </div>
                                            <table class="table">
                                                <tbody>
                                                <?php
                                                $all_leave_info = $this->db->get('tbl_leave_category')->result();
                                                $num_of_leave = 0;
                                                $total = 0;
                                                if (!empty($all_leave_info)):foreach ($all_leave_info as $key => $v_leave_info):
                                                    $this->admin_model->_table_name = 'tbl_leave_application';
                                                    $this->admin_model->_order_by = "user_id";
                                                    $total_leave = $this->admin_model->get_by(array('user_id' => $this->session->userdata('user_id'), 'leave_category_id' => $v_leave_info->leave_category_id, 'application_status' => '2'), FALSE);
                                                    $total_days = 0;
                                                    if (!empty($total_leave)) {
                                                        $ge_days = 0;
                                                        $m_days = 0;
                                                        foreach ($total_leave as $v_leave) {
                                                            $month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($v_leave->leave_start_date)), date('Y', strtotime($v_leave->leave_start_date)));

                                                            $datetime1 = new DateTime($v_leave->leave_start_date);

                                                            $datetime2 = new DateTime($v_leave->leave_end_date);
                                                            $difference = $datetime1->diff($datetime2);

                                                            if ($difference->m != 0) {
                                                                $m_days += $month;
                                                            } else {
                                                                $m_days = 0;
                                                            }
                                                            $ge_days += $difference->d + 1;
                                                            $total_days = $m_days + $ge_days;
                                                        }
                                                    }
                                                    $num_of_leave += $v_leave_info->leave_quota;
                                                    ?>
                                                    <tr>
                                                        <td><strong> <?= $v_leave_info->leave_category ?></strong>:</td>
                                                        <td>
                                                            <?php
                                                            if (empty($total_days)) {
                                                                $total_days = 0;
                                                            } else {
                                                                $total_days = $total_days;
                                                            }
                                                            $total += $total_days;
                                                            ?>
                                                            <?= $total_days ?>/<?= $v_leave_info->leave_quota; ?> </td>
                                                    </tr>
                                                    <?php
                                                endforeach;
                                                endif;
                                                ?>
                                                <tr>
                                                    <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;">
                                                        <strong> <?= lang('total') ?></strong>:
                                                    </td>
                                                    <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;"> <?= $total; ?>
                                                        /<?= $num_of_leave; ?> </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="tab-pane <?= $active == 3 ? 'active' : '' ?>" id="leave_report" style="position: relative;">
                <div class="col-lg-12">

                    <div class="panel panel-custom">
                        <div class="panel-heading"><?= lang('leave_report') ?></div>
                        <div class="panel-body">
                            <?php if ($this->session->userdata('user_type') == 1) { ?>
                                <div id="panelChart5">
                                    <div class="row panel-title pl-lg pb-sm"
                                         style="border-bottom: 1px solid #a0a6ad"><?= lang('all') . ' ' . lang('leave_report') ?></div>
                                    <div class="chart-pie flot-chart"></div>
                                </div>
                            <?php } ?>
                            <div id="panelChart5">
                                <div class="row panel-title pl-lg pb-sm"
                                     style="border-bottom: 1px solid #a0a6ad"><?= lang('my_leave') . ' ' . lang('report') ?></div>
                                <div class="chart-pie-my flot-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$color = array('37bc9b', '7266ba', 'f05050', 'ff902b', '7266ba', 'f532e5', '5d9cec', '7cd600', '91ca00', 'ff7400', '1cc200', 'bb9000', '40c400');
?>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.tooltip.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.resize.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.pie.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.time.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.categories.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.spline.min.js"></script>
<script type="text/javascript">
    // CHART PIE
    // -----------------------------------
    (function (window, document, $, undefined) {

        $(function () {

            var data = [
                <?php
                $all_category = $this->db->get('tbl_leave_category')->result();
                if(!empty($all_category)){
                foreach ($all_category as $key => $v_category) {
                if (!empty($my_leave_report[$v_category->leave_category_id])) {
                $result = $my_leave_report[$v_category->leave_category_id];
                ?>
                {
                    "label": "<?= $v_category->leave_category . ' (' . $v_category->leave_quota . ')'?>",
                    "color": "#<?=$color[$key] ?>",
                    "data": <?= $result?>
                },
                <?php }
                }
                }?>
            ];

            var options = {
                series: {
                    pie: {
                        show: true,
                        innerRadius: 0,
                        label: {
                            show: true,
                            radius: 0.8,
                            formatter: function (label, series) {
                                return '<div class="flot-pie-label">' +
                                        //label + ' : ' +
                                    Math.round(series.percent) +
                                    '%</div>';
                            },
                            background: {
                                opacity: 0.8,
                                color: '#222'
                            }
                        }
                    }
                }
            };

            var chart = $('.chart-pie-my');
            if (chart.length)
                $.plot(chart, data, options);

        });

    })(window, document, window.jQuery);
    // CHART PIE
    // -----------------------------------
    (function (window, document, $, undefined) {

        $(function () {

            var data = [
                <?php
                $all_category = $this->db->get('tbl_leave_category')->result();
                if(!empty($all_category)){
                foreach ($all_category as $key => $v_category) {
                if (!empty($leave_report[$v_category->leave_category_id])) {
                $all_report = $leave_report[$v_category->leave_category_id];
                ?>
                {
                    "label": "<?= $v_category->leave_category . ' (' . $v_category->leave_quota . ')'?>",
                    "color": "#<?=$color[$key] ?>",
                    "data": <?= $all_report ?>
                },
                <?php }
                }
                }?>
            ];

            var options = {
                series: {
                    pie: {
                        show: true,
                        innerRadius: 0,
                        label: {
                            show: true,
                            radius: 0.8,
                            formatter: function (label, series) {
                                return '<div class="flot-pie-label">' +
                                        //label + ' : ' +
                                    Math.round(series.percent) +
                                    '%</div>';
                            },
                            background: {
                                opacity: 0.8,
                                color: '#222'
                            }
                        }
                    }
                }
            };

            var chart = $('.chart-pie');
            if (chart.length)
                $.plot(chart, data, options);

        });

    })(window, document, window.jQuery);

    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more").click(function () {

            var add_new = $('<div class="form-group" style="margin-bottom: 0px">\n\
                    <label for="field-1" class="col-sm-4 control-label"><?= lang('attachment') ?></label>\n\
        <div class="col-sm-5">\n\
        <div class="fileinput fileinput-new" data-provides="fileinput">\n\
<span class="btn btn-default btn-file"><span class="fileinput-new" >Select file</span><span class="fileinput-exists" >Change</span><input type="file" name="upload_file[]" ></span> <span class="fileinput-filename"></span><a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none;">&times;</a></div></div>\n\<div class="col-sm-3">\n\<strong>\n\
<a href="javascript:void(0);" class="remCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div>');
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
<script>
    $(document).ready(function () {
        $('#leave_category').on('change', function () {
            $('#start_date').val('');
            $('#end_date').val('');
        });
        $('#start_date').on('change', function () {
            $('#end_date').val('');
        });
        $('.datepicker').datepicker({
            autoclose: true,
            format: "yyyy-mm-dd"
        });

    });
</script>