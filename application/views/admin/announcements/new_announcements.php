<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title"
            id="myModalLabel"><?= lang('new') . ' ' . lang('announcements') ?></h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <form role="form" id="form"
              action="<?php echo base_url(); ?>admin/announcements/save_announcements/<?= (!empty($announcements->announcements_id) ? $announcements->announcements_id : ''); ?>"
              method="post" class="form-horizontal form-groups-bordered">

            <div class="form-group">
                <label for="field-1" class="col-sm-3 control-label"><?= lang('title') ?> <span
                        class="required">*</span></label>

                <div class="col-sm-8">
                    <input type="text" name="title"
                           value="<?= (!empty($announcements->title) ? $announcements->title : ''); ?>"
                           class="form-control"
                           requried/>
                </div>
            </div>
            <div class="form-group">
                <label for="field-1" class="col-sm-3 control-label"><?= lang('description') ?></label>

                <div class="col-sm-8">
                    <textarea name="description"
                              class="form-control textarea"><?= (!empty($announcements->description) ? $announcements->description : ''); ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?= lang('start_date') ?> <span
                        class="required">*</span></label>

                <div class="col-sm-5">
                    <div class="input-group">
                        <input type="text" name="start_date"
                               placeholder="<?= lang('enter') . ' ' . lang('start_date') ?>"
                               class="form-control datepicker" value="<?php
                        if (!empty($announcements->start_date)) {
                            echo $announcements->start_date;
                        }
                        ?>">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?= lang('end_date') ?> <span
                        class="required">*</span></label>

                <div class="col-sm-5">
                    <div class="input-group">
                        <input type="text" name="end_date"
                               placeholder="<?= lang('enter') . ' ' . lang('end_date') ?>"
                               class="form-control datepicker" value="<?php
                        if (!empty($announcements->end_date)) {
                            echo $announcements->end_date;
                        }
                        ?>">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="field-1" class="col-sm-3 control-label"><?= lang('share_with') ?></label>

                <div class="col-sm-8">
                    <div class="checkbox c-checkbox">
                        <label>
                            <input <?= (!empty($announcements->all_client) ? 'checked' : ''); ?> type="checkbox"
                                                                                                 name="all_client"
                                                                                                 value="1">
                            <span class="fa fa-check"></span> <?= lang('all_client') ?>
                        </label>
                    </div>


                </div>
            </div>
            <div class="form-group">
                <label for="field-1" class="col-sm-3 control-label"><?= lang('status') ?></label>

                <div class="col-sm-8">
                    <div class="col-sm-4 row">
                        <div class="checkbox-inline c-checkbox">
                            <label>
                                <input <?= (!empty($announcements->status) && $announcements->status == 'published' ? 'checked' : ''); ?>
                                    class="select_one" type="checkbox" name="status" value="published">
                                <span class="fa fa-check"></span> <?= lang('published') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="checkbox-inline c-checkbox">
                            <label>
                                <input <?= (!empty($announcements->status) && $announcements->status == 'unpublished' ? 'checked' : ''); ?>
                                    class="select_one" type="checkbox" name="status" value="unpublished">
                                <span class="fa fa-check"></span> <?= lang('unpublished') ?>
                            </label>
                        </div>
                    </div>


                </div>
            </div>


            <!--hidden input values -->

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-2">
                    <button type="submit" id="sbtn" class="btn btn-primary btn-block"><?= lang('save')?></button>
                </div>
            </div>
        </form>
    </div>
</div>
