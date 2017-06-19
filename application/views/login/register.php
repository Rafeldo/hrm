<p class="text-center pv">SIGNUP TO GET INSTANT ACCESS.</p>
<form method="post" action="<?= base_url() ?>login/registered_user">
    <div class="form-group has-feedback">
        <label for="signupInputEmail1" class="text-muted"><?= lang('client_status') ?></label>
        <select class="form-control" name="client_status" id="client_stusus">
            <option value="1">Person</option>
            <option value="2">Company</option>
        </select>
    </div>
    <div class="person">
        <div class="form-group has-feedback">
            <label for="signupInputEmail1" class="text-muted"><?= lang('full_name') ?></label>
            <input type="text" name="name" required="true" class="form-control person"
                   placeholder="<?= lang('full_name') ?>">
            <span class="fa fa-male form-control-feedback text-muted"></span>
        </div>
        <div class="form-group has-feedback">
            <label for="signupInputEmail1" class="text-muted"><?= lang('email') ?></label>
            <input type="email" name="email" required="true" class="form-control person"
                   placeholder="<?= lang('email') ?>">
            <span class="fa fa-envelope form-control-feedback text-muted"></span>
        </div>
        <div class="form-group has-feedback">
            <label for="signupInputEmail1" class="text-muted"><?= lang('username') ?></label>
            <input type="text" name="username" required="true" class="form-control person"
                   placeholder="<?= lang('username') ?>">
            <span class="fa fa-user form-control-feedback text-muted"></span>
        </div>
        <div class="form-group has-feedback">
            <label for="signupInputPassword1" class="text-muted"><?= lang('password') ?></label>
            <input type="password" placeholder="<?= lang('password') ?>" required="true" class="form-control person"
                   name="password">
            <span class="fa fa-lock form-control-feedback text-muted"></span>
        </div>
        <div class="form-group has-feedback">
            <label for="signupInputRePassword1" class="text-muted"><?= lang('confirm_password') ?></label>
            <input id="signupInputRePassword1" type="password" placeholder="<?= lang('confirm_password') ?>"
                   required="true" class="form-control person" value="" name="confirm_password">
            <span class="fa fa-lock form-control-feedback text-muted"></span>
        </div>
    </div>
    <div class="company">
        <div class="form-group has-feedback">
            <label for="signupInputEmail1" class="text-muted"><?= lang('company_name') ?></label>
            <input type="text" name="name" required="true" class="form-control company"
                   placeholder="<?= lang('company_name') ?>">
            <span class="fa fa-male form-control-feedback text-muted"></span>
        </div>
        <div class="form-group has-feedback">
            <label for="signupInputEmail1" class="text-muted"><?= lang('company_email') ?></label>
            <input type="email" name="email" required="true" class="form-control company"
                   placeholder="<?= lang('company_email') ?>">
            <span class="fa fa-envelope form-control-feedback text-muted"></span>
        </div>
        <div class="form-group has-feedback">
            <label for="signupInputEmail1" class="text-muted"><?= lang('username') ?></label>
            <input type="text" name="username" required="true" class="form-control company"
                   placeholder="<?= lang('username') ?>">
            <span class="fa fa-user form-control-feedback text-muted"></span>
        </div>
        <div class="form-group has-feedback">
            <label for="signupInputPassword1" class="text-muted"><?= lang('password') ?></label>
            <input type="password" placeholder="<?= lang('password') ?>" required="true" class="form-control company"
                   name="password">
            <span class="fa fa-lock form-control-feedback text-muted"></span>
        </div>
        <div class="form-group has-feedback">
            <label for="signupInputRePassword1" class="text-muted"><?= lang('confirm_password') ?></label>
            <input id="signupInputRePassword1" type="password" placeholder="<?= lang('confirm_password') ?>"
                   required="true" class="form-control company" value="" name="confirm_password">
            <span class="fa fa-lock form-control-feedback text-muted"></span>
        </div>
    </div>
    <button type="submit" class="btn btn-block btn-primary mt-lg"><?= lang('sign_up') ?></button>
</form>
<p class="pt-lg text-center"><?= lang('already_have_an_account') ?></p><a href="<?= base_url() ?>login"
                                                                          class="btn btn-block btn-default"><?= lang('sign_in') ?></a>
