<?php echo message_box('success') ?>
<div class="row">
    <!-- Start Form test -->
    <div class="col-lg-12">
        <form method="post" action="<?php echo base_url() ?>admin/settings/update_email" class="form-horizontal">
            <div class="panel panel-custom">
                <header class="panel-heading "><?= lang('email_settings') ?></header>
                <div class="panel-body">
                    <input type="hidden" name="settings" value="<?= $load_setting ?>">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('company_email') ?> <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="email" required="" class="form-control"
                                   value="<?= $this->config->item('company_email') ?>" name="company_email"
                                   data-type="email" data-required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('use_postmark') ?></label>
                        <div class="col-lg-6">
                            <div class="checkbox c-checkbox">
                                <label class="needsclick">
                                    <input type="hidden" value="off" name="use_postmark"/>
                                    <input type="checkbox" <?php
                                    if (config_item('use_postmark') == 'TRUE') {
                                        echo "checked=\"checked\"";
                                    }
                                    ?> name="use_postmark" id="use_postmark">
                                    <span class="fa fa-check"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div
                        id="postmark_config" <?php echo (config_item('use_postmark') != 'TRUE') ? 'style="display:none"' : '' ?>>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('postmark_api_key') ?></label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="xxxxx" name="postmark_api_key"
                                       value="<?= config_item('postmark_api_key') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('postmark_from_address') ?></label>
                            <div class="col-lg-6">
                                <input type="email" class="form-control" placeholder="xxxxx"
                                       name="postmark_from_address" value="<?= config_item('postmark_from_address') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('email_protocol') ?> <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <select name="protocol" required="" class="form-control" id="protocol">
                                <?php $prot = config_item('protocol'); ?>
                                <option
                                    value="mail" <?= ($prot == "mail" ? ' selected="selected"' : '') ?>><?= lang('php_mail') ?></option>
                                <option
                                    value="smtp" <?= ($prot == "smtp" ? ' selected="selected"' : '') ?>><?= lang('smtp') ?></option>
                            </select>
                        </div>
                    </div>
                    <?php $prot = config_item('protocol'); ?>
                    <div id="smtp_config" style="<?= ($prot == "smtp" ? ' display:block' : 'display:none') ?>">
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('smtp_host') ?> </label>
                            <div class="col-lg-6">
                                <input type="text" required="" class="form-control"
                                       value="<?= $this->config->item('smtp_host') ?>" name="smtp_host">
                                <span class="help-block  ">SMTP Server Address</strong>.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('smtp_user') ?></label>
                            <div class="col-lg-6">
                                <input type="text" required="" class="form-control"
                                       value="<?= $this->config->item('smtp_user') ?>" name="smtp_user">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $this->load->library('encrypt'); ?>
                            <label class="col-lg-3 control-label"><?= lang('smtp_pass') ?></label>
                            <div class="col-lg-6">
                                <input type="password" required="" class="form-control"
                                       value="<?= $this->config->item('smtp_pass'); ?>" name="smtp_pass">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('smtp_port') ?></label>
                            <div class="col-lg-6">
                                <input type="text" required="" class="form-control"
                                       value="<?= $this->config->item('smtp_port') ?>" name="smtp_port">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"></label>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-sm btn-primary"><?= lang('save_changes') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- End Form -->
</div>