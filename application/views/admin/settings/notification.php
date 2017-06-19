<link href="<?php echo base_url() ?>asset/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="<?php echo base_url() ?>asset/js/bootstrap-toggle.min.js"></script>
<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php
$error_message = $this->session->userdata('error_message');
$error_type = $this->session->userdata('error_type');
if (!empty($error_message)) {
    foreach ($error_message as $key => $v_message) {
        ?>
        <div class="alert-<?php echo $error_type[$key] ?>" style="padding: 8px;margin-bottom: 21px;border: 1px solid transparent;}">
            <?php echo $v_message; ?>
        </div>
        <?php
    }
}
$this->session->unset_userdata('error_message');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="wrap-fpanel">
            <div class="panel panel-custom" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <strong>Notification Settings</strong>
                    </div>
                </div>
                <div class="panel-body">

                    <form id="form" action="<?php echo base_url() ?>admin/settings/set_noticifation" method="post"
                          class="form-horizontal form-groups-bordered">
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?= lang('email') ?>: <span
                                    class="required">*</span></label>

                            <div class="col-sm-5">
                                <input data-toggle="toggle" name="email" value="1" <?php
                                if (!empty($email) && $email->notify_me == 1) {
                                    echo 'checked';
                                }
                                ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success"
                                       data-offstyle="danger" type="checkbox">
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"></label>
                            <div class="col-sm-5">
                                <button type="submit" id="sbtn" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>