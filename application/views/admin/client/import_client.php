<?= message_box('success'); ?>
<?= message_box('error'); ?>
<div class="panel panel-custom">
    <header class="panel-heading">
        <div class="panel-title"><strong><?= lang('import') . ' ' . lang('client') ?></strong>
            <div class="pull-right hidden-print">
                <div class="pull-right "><a href="<?php echo base_url() ?>assets/sample/client_sample.xlsx"
                                            class="btn btn-primary"><i
                            class="fa fa-download"> <?= lang('download_sample') ?></i></a>
                </div>
            </div>

        </div>
    </header>
    <div class="panel-body">
        <form role="form" enctype="multipart/form-data" id="form"
              action="<?php echo base_url(); ?>admin/client/save_imported" method="post"
              class="form-horizontal  ">
            <div class="panel-body">
                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label">
                        <?= lang('choose_file') ?><span class="required">*</span></label>
                    <div class="col-sm-5">
                        <div style="display: inherit;margin-bottom: inherit" class="fileinput fileinput-new"
                             data-provides="fileinput">
                    <span class="btn btn-default btn-file"><span
                            class="fileinput-new"><?= lang('select_file') ?></span>
                                                            <span class="fileinput-exists"><?= lang('change') ?></span>
                                                            <input type="file" name="upload_file">
                                                        </span>
                            <span class="fileinput-filename"></span>
                            <a href="#" class="close fileinput-exists" data-dismiss="fileinput"
                               style="float: none;">&times;</a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3"><?= lang('select_type') ?></label>
                    <div class="col-lg-2">
                        <select class="form-control" name="client_status" id="get_company">
                            <option value="1" <?php
                            if (!empty($client_info) && $client_info->client_status == 1) {
                                echo 'selected';
                            }
                            ?>>Person
                            </option>
                            <option value="2" <?php
                            if (!empty($client_info) && $client_info->client_status == 2) {
                                echo 'selected';
                            }
                            ?>>Company
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group company_vat" >
                    <label
                        class="col-lg-3 control-label"><?= lang('company_vat') ?></label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control company_vat" value="<?php
                        if (!empty($client_info->vat)) {
                            echo $client_info->vat;
                        }
                        ?>" name="vat">
                    </div>
                </div>
                <div class="form-group">
                    <label
                        class="col-sm-3 control-label"><?= lang('language') ?></label>
                    <div class="col-sm-5">
                        <select name="language" class="form-control person select_box"
                                style="width: 100%">
                            <?php foreach ($languages as $lang) : ?>
                                <option
                                    value="<?= $lang->name ?>"<?php
                                if (!empty($client_info->language) && $client_info->language == $lang->name) {
                                    echo 'selected';
                                } elseif (empty($client_info->language) && $this->config->item('language') == $lang->name) {
                                    echo 'selected';
                                } ?>
                                ><?= ucfirst($lang->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label
                        class="col-lg-3 control-label"><?= lang('currency') ?></label>
                    <div class="col-lg-5">
                        <select name="currency" class="form-control person select_box"
                                style="width: 100%">

                            <?php if (!empty($currencies)): foreach ($currencies as $currency): ?>
                                <option
                                    value="<?= $currency->code ?>"
                                    <?php
                                    if (!empty($client_info->currency) && $client_info->currency == $currency->code) {
                                        echo 'selected';
                                    } elseif (empty($client_info->currency) && $this->config->item('default_currency') == $currency->code) {
                                        echo 'selected';
                                    } ?>
                                ><?= $currency->name ?></option>
                                <?php
                            endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('country') ?></label>
                    <div class="col-lg-5">
                        <select name="country" class="form-control person select_box"
                                style="width: 100%">
                            <optgroup label="Default Country">
                                <option
                                    value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>
                            </optgroup>
                            <optgroup label="<?= lang('other_countries') ?>">
                                <?php if (!empty($countries)): foreach ($countries as $country): ?>
                                    <option
                                        value="<?= $country->value ?>" <?= (!empty($client_info->country) && $client_info->country == $country->value ? 'selected' : NULL) ?>><?= $country->value ?>
                                    </option>
                                    <?php
                                endforeach;
                                endif;
                                ?>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-sm btn-primary"></i> <?= lang('upload') ?></button>
                    </div>
                </div>
            </div>
    </div>
</div>
