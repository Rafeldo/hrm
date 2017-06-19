<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <!-- =============== VENDOR STYLES ===============-->
    <!-- FONT AWESOME-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome/css/font-awesome.min.css">
    <!-- SIMPLE LINE ICONS-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/simple-line-icons/css/simple-line-icons.css">
    <!-- =============== BOOTSTRAP STYLES ===============-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css" id="bscss">
    <!-- =============== APP STYLES ===============-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.css" id="maincss">
</head>
<body>
<a class="pull-right btn btn-info btn-lg mt0" href="<?= base_url() ?>frontend"><?= lang('apply_jobs') ?></a>
<div class="wrapper " style="margin: 5% auto">


    <div class="block-center mt-xl wd-xl">
        <div class="text-center" style="margin-bottom: 20px">
            <?php if (config_item('logo_or_icon') == 'logo_title') { ?>
                <img style="width: 80px;height: 80px;border-radius: 50%"
                     src="<?= base_url() . config_item('company_logo') ?>" class="m-r-sm">
            <?php } elseif ($this->config->item('logo_or_icon') == 'icon') { ?>
                <i class="fa <?= $this->config->item('site_icon') ?>"></i>
            <?php } ?>
        </div>
        <?= message_box('success'); ?>
        <?= message_box('error'); ?>
        <div class="error_login">
            <?php echo validation_errors(); ?>
            <?php
            $error = $this->session->flashdata('error');
            $success = $this->session->flashdata('success');
            if (!empty($error)) {
                ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>
            <?php if (!empty($success)) { ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php } ?>
        </div>
        <!-- START panel-->
        <div class="panel panel-dark panel-flat">
            <div class="panel-heading text-center">
                <a href="#" style="color: #ffffff">
                   <span style="font-size: 15px;"><?= config_item('company_name') ?>
                </a>
            </div>
            <div class="panel-body">
                <?= $subview; ?>
            </div>
        </div>
        <!-- END panel-->
        <div class="p-lg text-center">
            <span>&copy;</span>
            <span>rafeldo.xyz</span>
            <br/>
            <span>2016-2017</span>
            <span>-</span>
            <span>Version 1.0</span>

        </div>
    </div>
</div>

<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="<?php echo base_url(); ?>assets/plugins/modernizr/modernizr.custom.js"></script>
<!-- JQUERY-->
<script src="<?php echo base_url(); ?>assets/plugins/jquery/dist/jquery.js"></script>
<!-- BOOTSTRAP-->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/dist/js/bootstrap.js"></script>
<!-- STORAGE API-->
<script src="<?php echo base_url(); ?>assets/plugins/jQuery-Storage-API/jquery.storageapi.js"></script>
<!-- PARSLEY-->
<script src="<?php echo base_url(); ?>assets/plugins/parsleyjs/dist/parsley.min.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo base_url(); ?>assets/js/app.js"></script>
<script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
</body>

</html>
