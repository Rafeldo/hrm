<?php echo message_box('success'); ?>
<?php echo message_box('error');
?>
<div class="panel panel-custom" style="border: none;" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title">
            <?= lang('all') . ' ' . lang('announcements') ?>
            <div class="pull-right hidden-print" style="padding-top: 0px;padding-bottom: 8px">
                <a href="<?= base_url() ?>admin/announcements/new_announcements" class="btn btn-xs btn-info"
                   data-toggle="modal"
                   data-placement="top" data-target="#myModal_lg">
                    <i class="fa fa-plus "></i> <?= ' ' . lang('new') . ' ' . lang('announcements') ?></a>
            </div>
        </div>
    </div>
    <!-- Table -->
    <div class="panel-body">
        <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th><?= lang('title') ?></th>
                <th><?= lang('created_by') ?></th>
                <th><?= lang('start_date') ?></th>
                <th><?= lang('end_date') ?></th>
                <th><?= lang('status') ?></th>
                <th><?= lang('action') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($all_announcements)): foreach ($all_announcements as $v_announcements): ?>
                <tr>
                    <td><?php echo $v_announcements->title; ?></td>
                    <td><?= strftime(config_item('date_format'), strtotime($v_announcements->created_date)) ?></td>
                    <td><?= strftime(config_item('date_format'), strtotime($v_announcements->start_date)) ?></td>
                    <td><?= strftime(config_item('date_format'), strtotime($v_announcements->end_date)) ?></td>

                    <td>
                        <?php if ($v_announcements->status == 'unpublished') : ?>
                            <span class="label label-danger"><?= lang('unpublished') ?></span>
                        <?php else : ?>
                            <span class="label label-success"><?= lang('published') ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo btn_view_modal('admin/announcements/announcements_details/' . $v_announcements->announcements_id); ?>

                        <a href="<?= base_url() ?>admin/announcements/new_announcements/<?= $v_announcements->announcements_id ?>"
                           class="btn btn-primary btn-xs" title="<?= lang('edit') ?>" data-toggle="modal"
                           data-placement="top"
                           data-target="#myModal_lg"><span class="fa fa-pencil-square-o"></span></a>
                        <?php echo btn_delete('admin/announcements/delete_announcements/' . $v_announcements->announcements_id); ?>
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
