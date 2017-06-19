<p class="text-center pv">SIGN IN TO CONTINUE.</p>
<form role="form" action="<?php echo base_url() ?>login" method="post">
    <div class="form-group has-feedback">
        <input type="text" name="user_name" required="true" class="form-control" placeholder="<?= lang('username') ?>" />
        <span class="fa fa-envelope form-control-feedback text-muted"></span>
    </div>
    <div class="form-group has-feedback">
        <input type="password" name="password" required="true" class="form-control" placeholder="<?= lang('password') ?>" />
        <span class="fa fa-lock form-control-feedback text-muted"></span>
    </div>
    <div class="clearfix">
        <div class="checkbox c-checkbox pull-left mt0">
            <label>
                <input type="checkbox" value="" name="remember">
                <span class="fa fa-check"></span>Remember Me</label>
        </div>
        <div class="pull-right"><a href="<?= base_url() ?>login/forgot_password" class="text-muted"><?= lang('forgot_password') ?></a>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-block btn-flat"><?= lang('sign_in') ?> <i class="fa fa-arrow-right"></i></button>
</form>
<?php if (config_item('allow_client_registration') == 'TRUE') { ?>
<p class="pt-lg text-center"><?= lang('do_not_have_an_account') ?></p><a href="<?= base_url() ?>login/register" class="btn btn-block btn-default"><i class="fa fa-sign-in"></i> <?= lang('get_your_account') ?></a>
<?php } ?>