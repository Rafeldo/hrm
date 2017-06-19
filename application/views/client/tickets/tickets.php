<?= message_box('success'); ?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="#manage" data-toggle="tab"><?= lang('tickets') ?></a>
        </li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="#new" data-toggle="tab"><?= lang('new_ticket') ?></a>
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
                        <th><?= lang('department') ?></th>
                        <th><?= lang('status') ?></th>
                        <th><?= lang('action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($all_tickets_info)) {
                        foreach ($all_tickets_info as $v_tickets_info) {
                            if ($v_tickets_info->status == 'open') {
                                $s_label = 'danger';
                            } elseif ($v_tickets_info->status == 'closed') {
                                $s_label = 'success';
                            } else {
                                $s_label = 'default';
                            }
                            $profile_info = $this->db->where(array('user_id' => $v_tickets_info->reporter))->get('tbl_account_details')->row();
                            $dept_info = $this->db->where(array('departments_id' => $v_tickets_info->departments_id))->get('tbl_departments')->row();
                            ?>
                            <tr>

                                <td><span class="label label-success"><?= $v_tickets_info->ticket_code ?></span></td>
                                <td><a class="text-info"
                                       href="<?= base_url() ?>client/tickets/index/tickets_details/<?= $v_tickets_info->tickets_id ?>"><?= $v_tickets_info->subject ?></a>
                                </td>
                                <td><?= strftime(config_item('date_format'), strtotime($v_tickets_info->created)); ?></td>
                                <?php if ($role == '1') { ?>

                                    <td>
                                        <a class="pull-left recect_task  ">
                                            <?php if (!empty($profile_info)) {
                                                ?>
                                                <img style="width: 30px;margin-left: 18px;
                                                         height: 29px;
                                                         border: 1px solid #aaa;"
                                                     src="<?= base_url() . $profile_info->avatar ?>" class="img-circle">
                                            <?php } ?>

                                            <?=
                                            ($profile_info->fullname)
                                            ?>
                                        </a>
                                    </td>

                                <?php } ?>
                                <td><?= $dept_info->deptname; ?></td>
                                <?php
                                if ($v_tickets_info->status == 'in_progress') {
                                    $status = 'In Progress';
                                } else {
                                    $status = $v_tickets_info->status;
                                }
                                ?>
                                <td><span class="label label-<?= $s_label ?>"><?= ucfirst($status) ?></span></td>
                                <td>
                                    <?= btn_view('client/tickets/index/tickets_details/' . $v_tickets_info->tickets_id) ?>

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
            <form method="post" action="<?= base_url() ?>client/tickets/create_tickets/<?php
            if (!empty($tickets_info)) {
                echo $tickets_info->tickets_id;
            }
            ?>" enctype="multipart/form-data" class="form-horizontal">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('ticket_code') ?> <span class="text-danger">*</span></label>
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
                    <label class="col-lg-3 control-label"><?= lang('department') ?> <span class="text-danger">*</span>
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
                <div class="form-group">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-sm btn-primary"></i> <?= lang('create_ticket') ?></button>
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
            if (maxAppend >= 4) {
                alert("Maximum 5 File is allowed");
            } else {
                var add_new = $('<div class="form-group" style="margin-bottom: 0px">\n\
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('upload_file') ?></label>\n\
        <div class="col-sm-4">\n\
        <div class="fileinput fileinput-new" data-provides="fileinput">\n\
<span class="btn btn-default btn-file"><span class="fileinput-new" >Select file</span><span class="fileinput-exists" >Change</span><input type="file" name="upload_file[]" ></span> <span class="fileinput-filename"></span><a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none;">&times;</a></div></div>\n\<div class="col-sm-2">\n\<strong>\n\
<a href="javascript:void(0);" class="remCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div>');
                maxAppend++;
                $("#add_new").append(add_new);
            }
        });

        $("#add_new").on('click', '.remCF', function () {
            $(this).parent().parent().parent().remove();
        });
        $('a.RCF').click(function () {
            $(this).parent().parent().remove();
        });
    });
</script>
