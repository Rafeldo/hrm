<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<?php if (!empty($circular_details)):
$last_date = $circular_details->last_date;
$last_date = strtotime($last_date);
$current_time = time();
if ($current_time > $last_date) {
    $ribon = 'danger';
    $text = lang('expire');
} elseif ($current_time == $last_date) {
    $ribon = 'info';
    $text = lang('last_date');
} else {
    $lastdate = date('Y-m-d', strtotime($circular_details->last_date));
    $today = date('Y-m-d');
    $datetime1 = new DateTime($today);
    $datetime2 = new DateTime($lastdate);
    $interval = $datetime1->diff($datetime2);

    $ribon = 'success';
    $text = $interval->format('%R%a') . lang('days');
}
?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <strong><?= lang('view_circular_details') ?></strong>
            <div class="pull-right">
                <?= btn_pdf('frontend/jobs_posted_pdf/' . $circular_details->job_circular_id) ?>
            </div>
        </div>
    </div>
    <div class="panel-body form-horizontal">
        <?php
        $design_info = $this->db->where('designations_id', $circular_details->designations_id)->get('tbl_designations')->row();
        ?>
        <p>
            <strong
                style="font-size: 20px;: "><?= $circular_details->job_title . ' ( ' . $design_info->designations . ' ) ' ?></strong>
        </p>
        <div class="col-sm-8">

            <p class="m0">
                <strong><?= lang('vacancy_no') ?>: <?= $circular_details->vacancy_no ?></strong>

            </p>
            <p class="m0">
                <strong><?= lang('employment_type') ?>
                    : <?= lang($circular_details->employment_type) ?></strong>

            </p>
            <p class="m0">
                <strong> <?= lang('posted_date') ?>
                    : <?= strftime(config_item('date_format'), strtotime($circular_details->posted_date)) ?>
                </strong>
            </p>
            <p>

                <strong> <?= lang('last_date') ?>
                    : <?= strftime(config_item('date_format'), strtotime($circular_details->last_date)) ?>
                </strong>
            </p>

            <blockquote style="font-size: 12px"><?php echo $circular_details->description; ?></blockquote>
        </div>

        <div class="col-md-4">
            <div class="panel " style="border: none">
                <div class="panel-heading m0" style="border: none;background-color: #37474f;color: #fff">
                    <strong><?= lang('job_summery') ?></strong>
                </div>
                <div class="panel-body" style="background-color: #f5f5f5;">
                    <p class="m0">
                        <strong><?= lang('job_title') ?>: <?= $circular_details->job_title ?></strong>

                    </p>
                    <p class="m0">
                        <strong><?= lang('designation') ?>: <?= $design_info->designations ?></strong>

                    </p>
                    <p class="m0">
                        <strong><?= lang('vacancy_no') ?>: <?= $circular_details->vacancy_no ?></strong>
                    </p>
                    <p class="m0">
                        <strong><?= lang('employment_type') ?>
                            : <?= lang($circular_details->employment_type) ?></strong>

                    </p>
                    <p class="m0">
                        <strong> <?= lang('posted_date') ?>
                            : <?= strftime(config_item('date_format'), strtotime($circular_details->posted_date)) ?>
                        </strong>
                    </p>
                    <p>

                        <strong> <?= lang('last_date') ?>
                            : <?= strftime(config_item('date_format'), strtotime($circular_details->last_date)) ?>
                        </strong>
                    </p>
                </div>

            </div>

            <a href="<?= base_url() ?>frontend/apply_jobs/<?= $circular_details->job_circular_id ?>"
               class="btn btn-primary btn-block" data-toggle="modal"
               data-target="#myModal_lg"><?= lang('apply_now') ?></a>
        </div>
        <?php endif; ?>
    </div>
</div>
