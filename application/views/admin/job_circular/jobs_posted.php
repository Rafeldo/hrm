<div class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <strong><?= lang('job_posted_list') ?></strong>
            <div class="pull-right hidden-print" style="padding-top: 0px;padding-bottom: 8px">
                <a href="<?= base_url() ?>admin/job_circular/new_jobs_posted" class="btn btn-xs btn-info"
                   data-toggle="modal"
                   data-placement="top" data-target="#myModal_lg">
                    <i class="fa fa-plus "></i> <?= ' ' . lang('new') . ' ' . lang('jobs_posted') ?></a>
            </div>
        </div>
    </div>
    <!-- Table -->
    <div class="panel-body">
        <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th><?= lang('job_title') ?></th>
                <th><?= lang('designation') ?></th>
                <th><?= lang('vacancy_no') ?></th>
                <th><?= lang('last_date') ?></th>
                <th><?= lang('status') ?></th>
                <th><?= lang('action') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($job_post_info)): foreach ($job_post_info as $v_job_post):
                $design_info = $this->db->where('designations_id', $v_job_post->designations_id)->get('tbl_designations')->row();
                $can_edit = $this->job_circular_model->can_action('tbl_job_circular', 'edit', array('job_circular_id' => $v_job_post->job_circular_id));
                $can_delete = $this->job_circular_model->can_action('tbl_job_circular', 'delete', array('job_circular_id' => $v_job_post->job_circular_id));
                ?>
                <tr>
                    <td><?php echo $v_job_post->job_title; ?></td>
                    <td><?php echo $design_info->designations; ?></td>
                    <td><?php echo $v_job_post->vacancy_no; ?></td>
                    <td><?= strftime(config_item('date_format'), strtotime($v_job_post->last_date)) ?></td>
                    <td>

                        <?php

                        if ($v_job_post->status == 'unpublished') : ?>
                            <span class="label label-danger"><?= lang('unpublished') ?></span>
                        <?php else : ?>
                            <span class="label label-success"><?= lang('published') ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($can_edit)) { ?>
                            <?php
                            if ($v_job_post->status == 'unpublished') {
                                echo btn_publish('admin/job_circular/change_status/published/' . $v_job_post->job_circular_id);
                            } else {
                                echo btn_unpublish('admin/job_circular/change_status/unpublished/' . $v_job_post->job_circular_id);
                            }
                            ?>
                            <span data-toggle="tooltip" data-placement="top" title="<?= lang('edit') ?>">
                        <a href="<?= base_url() ?>admin/job_circular/new_jobs_posted/<?= $v_job_post->job_circular_id ?>"
                           class="btn btn-primary btn-xs"
                           data-toggle="modal"
                           data-placement="top" data-target="#myModal_lg">
                            <i class="fa fa-pencil-square-o"></i> </a>
                            </span>
                        <?php }
                        if (!empty($can_delete)) { ?>
                            <?php echo btn_delete('admin/job_circular/delete_jobs_posted/' . $v_job_post->job_circular_id); ?>
                        <?php } ?>
                        <?= btn_view_modal('admin/job_circular/view_circular_details/' . $v_job_post->job_circular_id) ?>
                    </td>
                </tr>
                <?php

            endforeach;
                ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


