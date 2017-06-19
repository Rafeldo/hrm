<?php echo message_box('success') ?>
<div class="row" xmlns="http://www.w3.org/1999/html">
    <!-- Start Form -->
    <section class="col-lg-12">
        <form role="form" id="form" action="<?php echo base_url(); ?>admin/settings/save_tickets" method="post"
              class="form-horizontal  ">
            <section class="panel panel-custom">
                <header class="panel-heading  "><?= lang('tickets_settings') ?></header>
                <div class="panel-body">
                    <?php echo validation_errors(); ?>
                    <input type="hidden" name="settings" value="<?= $load_setting ?>">

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('default_department') ?></label>
                        <div class="col-lg-5">
                            <select name="default_department" style="width: 100%" class="form-control select_box">
                                <?php
                                $department_info = $this->db->get('tbl_departments')->result();
                                if (!empty($department_info)) {
                                    foreach ($department_info as $v_department) : ?>
                                        <option
                                            value="<?= $v_department->departments_id ?>"<?= (config_item('default_department') == $v_department->departments_id ? ' selected="selected"' : '') ?>><?= $v_department->deptname ?></option>
                                    <?php endforeach;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">

                        <label class="col-lg-3 control-label"><?= lang('default_status') ?></label>
                        <div class="col-lg-5">
                            <select name="default_status" class="form-control">
                                <?php
                                $status_info = $this->db->get('tbl_status')->result();
                                if (!empty($status_info)) {
                                    foreach ($status_info as $v_status) {
                                        ?>
                                        <option
                                            value="<?= $v_status->status ?>"<?= (config_item('default_status') == $v_status->status ? ' selected="selected"' : '') ?>><?= lang($v_status->status) ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>

                        </div>
                    </div>
                    <div class="form-group">

                        <label class="col-lg-3 control-label"><?= lang('default_priority') ?></label>
                        <div class="col-lg-3">

                            <?php $options = array(
                                'high' => lang('high'),
                                'medium' => lang('medium'),
                                'low' => lang('low'),
                            );
                            echo form_dropdown('default_priority', $options, config_item('default_priority'), 'style="width:100%" class="form-control"'); ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg-3 control-label"></label>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-sm btn-primary"><?= lang('save_changes') ?></button>
                        </div>
                    </div>
            </section>
        </form>
</div>
