<?php echo message_box('success') ?>
<?php echo message_box('error') ?>
<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <form action="<?php echo base_url() ?>admin/settings/save_theme" enctype="multipart/form-data"
              class="form-horizontal" method="post">
            <div class="panel panel-custom">
                <header class="panel-heading  "><?= lang('theme_settings') ?></header>
                <div class="panel-body">
                    <input type="hidden" name="settings" value="<?= $load_setting ?>">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('site_name') ?></label>
                        <div class="col-lg-7">
                            <input type="text" name="website_name" class="form-control"
                                   value="<?= config_item('website_name') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('logo') ?></label>
                        <div class="col-lg-4">
                            <select name="logo_or_icon" class="form-control">
                                <?php $logoicon = config_item('logo_or_icon'); ?>
                                <option
                                    value="logo_title"<?= ($logoicon == "logo_title" ? ' selected="selected"' : '') ?>><?= lang('logo') ?>
                                    & <?= lang('site_name') ?></option>
                                <option
                                    value="logo"<?= ($logoicon == "logo" ? ' selected="selected"' : '') ?>><?= lang('logo') ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('company_logo') ?></label>
                        <div class="col-lg-7">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 210px;">
                                    <?php if (config_item('company_logo') != '') : ?>
                                        <img src="<?php echo base_url() . config_item('company_logo'); ?>">
                                    <?php else: ?>
                                        <img src="http://placehold.it/350x260" alt="Please Connect Your Internet">
                                    <?php endif; ?>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="width: 210px;"></div>
                                <div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileinput-new">
                                            <input type="file" name="company_logo" value="upload"
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
                        <label class="col-lg-3 control-label"><?= lang('sidebar_theme') ?></label>
                        <div class="col-lg-9" id="app-settings">
                            <?php $theme = config_item('sidebar_theme'); ?>
                            <div class="table-grid mb">
                                <div class="col mb">
                                    <div class="setting-color">
                                        <label data-load-css="<?php echo base_url(); ?>assets/css/bg-info.css">
                                            <input type="radio"
                                                   name="sidebar_theme"
                                                   value="bg-info" <?= $theme == 'bg-info' ? 'checked' : null ?>>
                                            <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-info"></span>
                                       <span class="color bg-info-light"></span>
                                    </span>
                                            <span class="color bg-white"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col mb">
                                    <div class="setting-color">
                                        <label data-load-css="<?php echo base_url(); ?>assets/css/bg-green.css">
                                            <input type="radio" name="sidebar_theme"
                                                   value="bg-green" <?= $theme == 'bg-green' ? 'checked' : null ?>>
                                            <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-green"></span>
                                       <span class="color bg-green-light"></span>
                                    </span>
                                            <span class="color bg-white"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col mb">
                                    <div class="setting-color">
                                        <label data-load-css="<?php echo base_url(); ?>assets/css/bg-purple.css">
                                            <input type="radio" name="sidebar_theme"
                                                   value="bg-purple" <?= $theme == 'bg-purple' ? 'checked' : null ?>>
                                            <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-purple"></span>
                                       <span class="color bg-purple-light"></span>
                                    </span>
                                            <span class="color bg-white"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col mb">
                                    <div class="setting-color">
                                        <label data-load-css="<?php echo base_url(); ?>assets/css/bg-danger.css">
                                            <input type="radio" name="sidebar_theme"
                                                   value="bg-danger" <?= $theme == 'bg-danger' ? 'checked' : null ?>>
                                            <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-danger"></span>
                                       <span class="color bg-danger-light"></span>
                                    </span>
                                            <span class="color bg-white"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="table-grid mb">
                                <div class="col mb">
                                    <div class="setting-color">
                                        <label data-load-css="<?php echo base_url(); ?>assets/css/bg-info-dark.css">
                                            <input type="radio" name="sidebar_theme"
                                                   value="bg-info-dark" <?= $theme == 'bg-info-dark' ? 'checked' : null ?>>
                                            <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-info-dark"></span>
                                       <span class="color bg-info"></span>
                                    </span>
                                            <span class="color bg-gray-dark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col mb">
                                    <div class="setting-color">
                                        <label data-load-css="<?php echo base_url(); ?>assets/css/bg-green-dark.css">
                                            <input type="radio" name="sidebar_theme"
                                                   value="bg-green-dark" <?= $theme == 'bg-green-dark' ? 'checked' : null ?>>
                                            <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-green-dark"></span>
                                       <span class="color bg-green"></span>
                                    </span>
                                            <span class="color bg-gray-dark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col mb">
                                    <div class="setting-color">
                                        <label data-load-css="<?php echo base_url(); ?>assets/css/bg-purple-dark.css">
                                            <input type="radio" name="sidebar_theme"
                                                   value="bg-purple-dark" <?= $theme == 'bg-purple-dark' ? 'checked' : null ?>>
                                            <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-purple-dark"></span>
                                       <span class="color bg-purple"></span>
                                    </span>
                                            <span class="color bg-gray-dark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col mb">
                                    <div class="setting-color">
                                        <label data-load-css="<?php echo base_url(); ?>assets/css/bg-danger-dark.css">
                                            <input type="radio" name="sidebar_theme"
                                                   value="bg-danger-dark" <?= $theme == 'bg-danger-dark' ? 'checked' : null ?>>
                                            <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-danger-dark"></span>
                                       <span class="color bg-danger"></span>
                                    </span>
                                            <span class="color bg-gray-dark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('layout') ?></label>
                        <div class="col-lg-7">
                            <div class="p">
                                <div class="clearfix">
                                    <p class="pull-left">Fixed</p>
                                    <div class="pull-right">
                                        <label class="switch">
                                            <input id="chk-fixed" name="layout-fixed" value="layout-fixed"
                                                   type="checkbox"
                                                   data-toggle-state="layout-fixed" <?= config_item('layout-fixed') == 'layout-fixed' ? 'checked' : null ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="clearfix">
                                    <p class="pull-left">Boxed</p>
                                    <div class="pull-right">
                                        <label class="switch">
                                            <input id="chk-boxed" name="layout-boxed" value="layout-boxed"
                                                   type="checkbox"
                                                   data-toggle-state="layout-boxed" <?= config_item('layout-boxed') == 'layout-boxed' ? 'checked' : null ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="clearfix">
                                    <p class="pull-left">Collapsed</p>
                                    <div class="pull-right">
                                        <label class="switch">
                                            <input id="chk-collapsed" type="checkbox" name="aside-collapsed"
                                                   value="aside-collapsed"
                                                   data-toggle-state="aside-collapsed" <?= config_item('aside-collapsed') == 'aside-collapsed' ? 'checked' : null ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="clearfix">
                                    <p class="pull-left">Float</p>
                                    <div class="pull-right">
                                        <label class="switch">
                                            <input id="chk-float" type="checkbox" name="aside-float" value="aside-float"
                                                   data-toggle-state="aside-float" <?= config_item('aside-float') == 'aside-float' ? 'checked' : null ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
<!--                                <div class="clearfix">-->
<!--                                    <p class="text-danger pull-left">RTL</p>-->
<!--                                    <div class="pull-right">-->
<!--                                        <label class="switch">-->
<!--                                            <input id="chk-rtl"-->
<!--                                                   name="RTL" --><?//= config_item('RTL') == 'on' ? 'checked' : null ?>
<!--                                                   type="checkbox">-->
<!--                                            <span></span>-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-4">
                        <button type="submit" class="btn btn-sm btn-primary"><?= lang('save_changes') ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

