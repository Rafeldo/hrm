<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title"
            id="myModalLabel"><?= lang('announcements_details') ?></h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="panel-body form-horizontal">
            <div class="col-md-12 notice-details-margin">
                <div class="col-sm-4 text-right">
                    <label class="control-label"><strong><?= lang('title') ?> :</strong></label>
                </div>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php if (!empty($announcements_details->title)) echo $announcements_details->title; ?></p>
                </div>
            </div>
            <div class="col-md-12 notice-details-margin">
                <div class="col-sm-4 text-right">
                    <label class="control-label"><?= lang('start_date') ?> :</label>
                </div>

                <div class="col-sm-5">
                    <p class="form-control-static"><?= strftime(config_item('date_format'), strtotime($announcements_details->start_date)) ?></p>
                </div>
            </div>
            <div class="col-md-12 notice-details-margin">
                <div class="col-sm-4 text-right">
                    <label class="control-label"><?= lang('end_date') ?> :</label>
                </div>

                <div class="col-sm-5">
                    <p class="form-control-static"><?= strftime(config_item('date_format'), strtotime($announcements_details->end_date)) ?></p>
                </div>
            </div>

            <div class="col-md-12 notice-details-margin">
                <div class="col-sm-4 text-right">
                    <label class="control-label"><strong><?= lang('created_date') ?> :</strong></label>
                </div>
                <div class="col-sm-8">
                    <p class="form-control-static"><span
                            class="text-danger"><?= strftime(config_item('date_format'), strtotime($announcements_details->created_date)) ?></span>
                    </p>
                </div>
            </div>
            <div class="col-md-12 notice-details-margin">
                <div class="col-sm-4 text-right">
                    <label class="control-label"><strong><?= lang('status') ?> :</strong></label>
                </div>
                <div class="col-sm-8">
                    <p class="form-control-static">
                        <?php if ($announcements_details->status == 'unpublished') : ?>
                            <span class="label label-danger"><?= lang('unpublished') ?></span>
                        <?php else : ?>
                            <span class="label label-success"><?= lang('published') ?></span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <?php
            if (!empty($announcements_details->all_client)) {
                ?>
                <div class="col-md-12 notice-details-margin">
                    <div class="col-sm-4 text-right">
                        <label class="control-label"><strong><?= lang('share_with') ?> :</strong></label>
                    </div>
                    <div class="col-sm-8">
                        <p class="form-control-static">
                            <span class="label label-info"><?= lang('client') ?></span>
                        </p>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-12 notice-details-margin">
                <div class="col-sm-4 text-right">
                    <label class="control-label"><strong><?= lang('description') ?> :</strong></label>
                </div>
                <div class="col-sm-8">
                    <blockquote style="font-size: 12px"><?php echo $announcements_details->description; ?></blockquote>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
    </div>
</div>






