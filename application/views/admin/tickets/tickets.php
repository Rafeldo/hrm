<?= message_box('success'); ?>
<?= message_box('error'); ?>
<?php
$answered = 0;
$closed = 0;
$open = 0;
$in_progress = 0;

$progress_tickets_info = $this->tickets_model->get_permission('tbl_tickets');
// 30 days before

for ($iDay = 30; $iDay >= 0; $iDay--) {
    $date = date('Y-m-d', strtotime('today - ' . $iDay . 'days'));
    $where = array('created >=' => $date . " 00:00:00", 'created <=' => $date . " 23:59:59");

    $tickets_result[$date] = count($this->db->where($where)->get('tbl_tickets')->result());
}

if (!empty($progress_tickets_info)):foreach ($progress_tickets_info as $v_tickets):
    if ($v_tickets->status == 'answered') {
        $answered += count($v_tickets->status);
    }
    if ($v_tickets->status == 'closed') {
        $closed += count($v_tickets->status);
    }
    if ($v_tickets->status == 'open') {
        $open += count($v_tickets->status);
    }
    if ($v_tickets->status == 'in_progress') {
        $in_progress += count($v_tickets->status);
    }
endforeach;
endif;
if ($this->session->userdata('user_type') == 1) {
    $margin = 'margin-bottom:30px';
    ?>
    <div class="col-sm-12 bg-white p0" style="<?= $margin ?>">
        <div class="col-md-4">
            <div class="row row-table pv-lg">
                <div class="col-xs-6">
                    <p class="m0 lead"><?= $answered ?></p>
                    <p class="m0">
                        <small><?= lang('answered') . ' ' . lang('tickets') ?></small>
                    </p>
                </div>
                <div class="col-xs-6">
                    <p class="m0 lead"><?= $in_progress ?></p>
                    <p class="m0">
                        <small><?= lang('in_progress') . ' ' . lang('tickets') ?></small>
                    </p>
                </div>


            </div>
        </div>
        <div class="col-md-3">
            <div class="row row-table pv-lg">
                <div class="col-xs-6">
                    <p class="m0 lead"><?= $open ?></p>
                    <p class="m0">
                        <small><?= lang('open') . ' ' . lang('tickets') ?></small>
                    </p>
                </div>
                <div class="col-xs-6">
                    <p class="m0 lead"><?= $closed ?></p>
                    <p class="m0">
                        <small><?= lang('close') . ' ' . lang('tickets') ?></small>
                    </p>
                </div>

            </div>
        </div>
        <div class="col-md-5">
            <div class="row row-table text-center pt">
                <div data-sparkline="" data-bar-color="#23b7e5" data-height="60" data-bar-width="7"
                     data-bar-spacing="6" data-chart-range-min="0"
                     values="<?php
                     if (!empty($tickets_result)) {
                         foreach ($tickets_result as $v_tickets_result) {
                             echo $v_tickets_result . ',';
                         }
                     }
                     ?>">
                </div>

                <span class="easypie-text "><strong><?= lang('last_30_days') ?></strong></span>

            </div>
        </div>
    </div>
<?php } ?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="#manage"
                                                            data-toggle="tab"><?= lang('tickets') ?></a>
        </li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="#new"
                                                            data-toggle="tab"><?= lang('new_ticket') ?></a>
        </li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <div class="table-responsive">
                <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                    <thead>
                    <tr>

                        <th><?= lang('ticket_code') ?></th>
                        <th><?= lang('subject') ?></th>
                        <th class="col-date"><?= lang('date') ?></th>
                        <?php if ($this->session->userdata('user_type') == '1') { ?>
                            <th><?= lang('reporter') ?></th>
                        <?php } ?>
                        <th><?= lang('department') ?></th>
                        <th><?= lang('status') ?></th>
                        <th><?= lang('action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    if (!empty($all_tickets_info)) {
                        foreach ($all_tickets_info as $v_tickets_info) {
                            $can_edit = $this->tickets_model->can_action('tbl_tickets', 'edit', array('tickets_id' => $v_tickets_info->tickets_id));
                            $can_delete = $this->tickets_model->can_action('tbl_tickets', 'delete', array('tickets_id' => $v_tickets_info->tickets_id));
                            if ($v_tickets_info->status == 'open') {
                                $s_label = 'danger';
                            } elseif ($v_tickets_info->status == 'closed') {
                                $s_label = 'success';
                            } else {
                                $s_label = 'default';
                            }
                            $profile_info = $this->db->where(array('user_id' => $v_tickets_info->reporter))->get('tbl_account_details')->row();
                            $dept_info = $this->db->where(array('departments_id' => $v_tickets_info->departments_id))->get('tbl_departments')->row();
                            if (!empty($dept_info)) {
                                $dept_name = $dept_info->deptname;
                            } else {
                                $dept_name = '-';
                            }
                            if (!empty($ticket_status)) {
                                if ($ticket_status == $v_tickets_info->status) {
                                    ?>
                                    <tr>

                                        <td><span
                                                class="label label-success"><?= $v_tickets_info->ticket_code ?></span>
                                        </td>
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
                                        <td><?= $dept_name ?></td>
                                        <?php
                                        if ($v_tickets_info->status == 'in_progress') {
                                            $status = 'In Progress';
                                        } else {
                                            $status = $v_tickets_info->status;
                                        }
                                        ?>
                                        <td><span class="label label-<?= $s_label ?>"><?= ucfirst($status) ?></span>
                                        </td>
                                        <td>
                                            <?php if (!empty($can_edit)) { ?>
                                                <?= btn_edit('admin/tickets/index/edit_tickets/' . $v_tickets_info->tickets_id) ?>
                                            <?php }
                                            if (!empty($can_delete)) { ?>
                                                <?= btn_delete('admin/tickets/delete/delete_tickets/' . $v_tickets_info->tickets_id) ?>
                                            <?php } ?>
                                            <?= btn_view('admin/tickets/index/tickets_details/' . $v_tickets_info->tickets_id) ?>
                                            <?php if (!empty($can_edit)) { ?>
                                                <div class="btn-group">
                                                    <button class="btn btn-xs btn-success dropdown-toggle"
                                                            data-toggle="dropdown">
                                                        <?= lang('change_status') ?>
                                                        <span class="caret"></span></button>
                                                    <ul class="dropdown-menu animated zoomIn">
                                                        <?php
                                                        $status_info = $this->db->get('tbl_status')->result();
                                                        if (!empty($status_info)) {
                                                            foreach ($status_info as $v_status) {
                                                                ?>
                                                                <li><a data-toggle='modal' data-target='#myModal'
                                                                       href="<?= base_url() ?>admin/tickets/change_status/<?= $v_tickets_info->tickets_id ?>/<?= $v_status->status ?>"><?= ucfirst($v_status->status) ?></a>
                                                                </li>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else { ?>
                                <tr>

                                    <td><span class="label label-success"><?= $v_tickets_info->ticket_code ?></span>
                                    </td>
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


                                                <?=
                                                ($profile_info->fullname)
                                                ?>
                                                <?php }else{
                                                    echo '-';
                                                } ?>
                                            </a>
                                        </td>

                                    <?php } ?>
                                    <td><?= $dept_name ?></td>
                                    <?php
                                    if ($v_tickets_info->status == 'in_progress') {
                                        $status = 'In Progress';
                                    } else {
                                        $status = $v_tickets_info->status;
                                    }
                                    ?>
                                    <td><span class="label label-<?= $s_label ?>"><?= ucfirst($status) ?></span>
                                    </td>
                                    <td>
                                        <?php if (!empty($can_edit)) { ?>
                                            <?= btn_edit('admin/tickets/index/edit_tickets/' . $v_tickets_info->tickets_id) ?>
                                        <?php }
                                        if (!empty($can_delete)) { ?>
                                            <?= btn_delete('admin/tickets/delete/delete_tickets/' . $v_tickets_info->tickets_id) ?>
                                        <?php } ?>
                                        <?= btn_view('admin/tickets/index/tickets_details/' . $v_tickets_info->tickets_id) ?>
                                        <?php if (!empty($can_edit)) { ?>
                                            <div class="btn-group">
                                                <button class="btn btn-xs btn-success dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    <?= lang('change_status') ?>
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu animated zoomIn">
                                                    <?php
                                                    $status_info = $this->db->get('tbl_status')->result();
                                                    if (!empty($status_info)) {
                                                        foreach ($status_info as $v_status) {
                                                            ?>
                                                            <li><a data-toggle='modal' data-target='#myModal'
                                                                   href="<?= base_url() ?>admin/tickets/change_status/<?= $v_tickets_info->tickets_id ?>/<?= $v_status->status ?>"><?= ucfirst($v_status->status) ?></a>
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php }
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="new">
            <form method="post" action="<?= base_url() ?>admin/tickets/create_tickets/<?php
            if (!empty($tickets_info)) {
                echo $tickets_info->tickets_id;
            }
            ?>" enctype="multipart/form-data" class="form-horizontal">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('ticket_code') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" style="width:260px" value="<?php
                        $this->load->helper('string');
                        if (!empty($tickets_info)) {
                            echo $tickets_info->ticket_code;
                        } else {
                            echo strtoupper(random_string('alnum', 7));
                        }
                        ?>" name="ticket_code">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('subject') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <input type="text" value="<?php
                        if (!empty($tickets_info)) {
                            echo $tickets_info->subject;
                        }
                        ?>" class="form-control" placeholder="Sample Ticket Subject" name="subject" required>
                    </div>
                </div>
                <input type="hidden" value="<?php echo $this->uri->segment(3)?>" class="form-control"  name="status" >

                <?php if ($this->session->userdata('user_type') == '1') { ?>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('reporter') ?> <span
                                class="text-danger">*</span>
                        </label>
                        <div class="col-lg-5">
                            <div class=" ">
                                <select class="form-control select_box" style="width:100%" name="reporter">
                                    <?php
                                    $users = $this->db->get('tbl_users')->result();
                                    if (!empty($users)) {
                                        foreach ($users as $v_user):
                                            $all_client = $this->db->where(array("user_id" => $v_user->user_id))->get('tbl_account_details')->row();
                                            if ($v_user->role_id == 1) {
                                                $role = lang('admin');
                                            } elseif ($v_user->role_id == 2) {
                                                $role = lang('client');
                                            } else {
                                                $role = lang('staff');
                                            }
                                            ?>
                                            <option value="<?= $all_client->user_id ?>" <?php
                                            if (!empty($tickets_info) && $tickets_info->reporter == $all_client->user_id) {
                                                echo 'selected';
                                            }
                                            ?>><?= $all_client->fullname . ' (' . $role . ')'; ?></option>
                                            <?php
                                        endforeach;
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('priority') ?> <span class="text-danger">*</span>
                    </label>
                    <div class="col-lg-5">
                        <div class=" ">
                            <select name="priority" class="form-control">
                                <?php
                                $priorities = $this->db->get('tbl_priorities')->result();
                                if (!empty($priorities)) {
                                    foreach ($priorities as $v_priorities):
                                        ?>
                                        <option value="<?= $v_priorities->priority ?>" <?php
                                        if (!empty($tickets_info) && $tickets_info->priority == $v_priorities->priority) {
                                            echo 'selected';
                                        }
                                        ?>><?= lang(strtolower($v_priorities->priority)) ?></option>
                                        <?php
                                    endforeach;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('department') ?> <span
                            class="text-danger">*</span>
                    </label>
                    <div class="col-lg-5">
                        <div class=" ">
                            <select name="departments_id" class="form-control select_box" style="width: 100%">
                                <?php
                                $all_departments = $this->db->get('tbl_departments')->result();
                                if (!empty($all_departments)) {
                                    foreach ($all_departments as $v_dept):
                                        ?>
                                        <option value="<?= $v_dept->departments_id ?>" <?php
                                        if (!empty($tickets_info) && $tickets_info->departments_id == $v_dept->departments_id) {
                                            echo 'selected';
                                        }
                                        ?>><?= $v_dept->deptname ?></option>
                                        <?php
                                    endforeach;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('ticket_message') ?> </label>
                    <div class="col-lg-7">
                        <textarea name="body" class="form-control textarea" placeholder="<?= lang('message') ?>"><?php
                            if (!empty($tickets_info)) {
                                echo $tickets_info->body;
                            } else {
                                echo set_value('body');
                            }
                            ?></textarea>

                    </div>
                </div>
                <?php
                if (!empty($tickets_info)) {
                    $tickets_id = $tickets_info->tickets_id;
                } else {
                    $tickets_id = null;
                }
                ?>
                <?= custom_form_Fields(7, $tickets_id); ?>

                <div id="add_new">
                    <div class="form-group" style="margin-bottom: 0px">
                        <label for="field-1"
                               class="col-sm-3 control-label"><?= lang('attachment') ?></label>
                        <div class="col-sm-4">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
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
                                if (!empty($tickets_info->permission) && $tickets_info->permission == 'all') {
                                    echo 'checked';
                                } elseif (empty($tickets_info)) {
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
                                if (!empty($tickets_info->permission) && $tickets_info->permission != 'all') {
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
                if (!empty($tickets_info->permission) && $tickets_info->permission != 'all') {
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
                                            if (!empty($tickets_info->permission) && $tickets_info->permission != 'all') {
                                                $get_permission = json_decode($tickets_info->permission);
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

                                if (!empty($tickets_info->permission) && $tickets_info->permission != 'all') {
                                    $get_permission = json_decode($tickets_info->permission);

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

                                            if (!empty($tickets_info->permission) && $tickets_info->permission != 'all') {
                                                $get_permission = json_decode($tickets_info->permission);

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

                                            if (!empty($tickets_info->permission) && $tickets_info->permission != 'all') {
                                                $get_permission = json_decode($tickets_info->permission);
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
                    <div class="col-lg-6">
                        <button type="submit"
                                class="btn btn-sm btn-primary"></i> <?= lang('create_ticket') ?></button>
                    </div>
                </div>
            </form>
        </div>
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