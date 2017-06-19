<?= message_box('success'); ?>
<?= message_box('error'); ?>
<div class="panel panel-custom">
    <header class="panel-heading "><?= lang('leave_category') ?></header>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped ">
                <thead>
                <tr>
                    <th><?= lang('leave_category') ?></th>
                    <th><?= lang('quota') ?></th>
                    <th><?= lang('action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $all_leave_category = $this->db->get('tbl_leave_category')->result();

                if (!empty($all_leave_category)) {
                    foreach ($all_leave_category as $leave_category) {
                        ?>
                        <tr>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $leave_category->leave_category_id) { ?>
                                <form method="post"
                                      action="<?= base_url() ?>admin/settings/leave_category/update_leave_category/<?php
                                      if (!empty($leave_category_info)) {
                                          echo $leave_category_info->leave_category_id;
                                      }
                                      ?>" class="form-horizontal">
                                    <input type="text" name="leave_category" value="<?php
                                    if (!empty($leave_category_info)) {
                                        echo $leave_category_info->leave_category;
                                    }
                                    ?>" class="form-control" placeholder="<?= lang('leave_category') ?>" required>
                                <?php } else {
                                    echo $leave_category->leave_category;
                                }
                                ?></td>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $leave_category->leave_category_id) { ?>
                                    <input type="text" name="leave_quota" class="form-control" value="<?php
                                    if (!empty($leave_category_info)) {
                                        echo $leave_category_info->leave_quota;
                                    }
                                    ?>"/>
                                <?php } else {
                                    echo $leave_category->leave_quota;
                                }
                                ?></td>
                            <td>
                                <?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $leave_category->leave_category_id) { ?>
                                    <?= btn_update() ?>
                                    </form>
                                    <?= btn_cancel('admin/settings/leave_category/') ?>
                                <?php } else { ?>
                                    <?= btn_edit('admin/settings/leave_category/edit_leave_category/' . $leave_category->leave_category_id) ?>
                                    <?= btn_delete('admin/settings/delete_leave_category/' . $leave_category->leave_category_id) ?>
                                <?php }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                <form method="post" action="<?= base_url() ?>admin/settings/leave_category/update_leave_category"
                      class="form-horizontal">
                    <tr>
                        <td><input type="text" name="leave_category" class="form-control"
                                   placeholder="<?= lang('leave_category') ?>" required></td>
                        <td>
                            <input name="leave_quota" placeholder="<?= lang('days') . ' / ' . lang('years') ?>"
                                   class="form-control"/>
                        </td>
                        <td><?= btn_add() ?></td>
                    </tr>
                </form>
                </tbody>
            </table>
        </div>
    </div>
</div>