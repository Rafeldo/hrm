<?= message_box('success'); ?>
<?= message_box('error'); ?>
<div class="panel panel-custom">
    <header class="panel-heading "><?= lang('expense_category') ?></header>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped ">
                <thead>
                <tr>

                    <th><?= lang('expense_category') ?></th>
                    <th><?= lang('description') ?></th>
                    <th><?= lang('action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($all_expense_category)) {
                    foreach ($all_expense_category as $expense_category) {
                        ?>
                        <tr>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $expense_category->expense_category_id) { ?>
                                <form method="post"
                                      action="<?= base_url() ?>admin/settings/expense_category/update_expense_category/<?php
                                      if (!empty($expense_category_info)) {
                                          echo $expense_category_info->expense_category_id;
                                      }
                                      ?>" class="form-horizontal">
                                    <input type="text" name="expense_category" value="<?php
                                    if (!empty($expense_category)) {
                                        echo $expense_category->expense_category;
                                    }
                                    ?>" class="form-control" placeholder="<?= lang('expense_category') ?>" required>
                                <?php } else {
                                    echo $expense_category->expense_category;
                                }
                                ?></td>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $expense_category->expense_category_id) { ?>
                                    <textarea name="description" rows="1" class="form-control"><?php
                                        if (!empty($expense_category)) {
                                            echo $expense_category->description;
                                        }
                                        ?></textarea>
                                <?php } else {
                                    echo $expense_category->description;
                                }
                                ?></td>
                            <td>
                                <?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $expense_category->expense_category_id) { ?>
                                    <?= btn_update() ?>
                                    </form>
                                    <?= btn_cancel('admin/settings/expense_category/') ?>
                                <?php } else { ?>
                                    <?= btn_edit('admin/settings/expense_category/edit_expense_category/' . $expense_category->expense_category_id) ?>
                                    <?= btn_delete('admin/settings/delete_expense_category/' . $expense_category->expense_category_id) ?>
                                <?php }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                <form method="post" action="<?= base_url() ?>admin/settings/expense_category/update_expense_category" class="form-horizontal">
                    <tr>
                        <td><input type="text" name="expense_category" class="form-control"
                                   placeholder="<?= lang('expense_category') ?>" required></td>
                        <td>
                            <textarea name="description" rows="1" class="form-control"></textarea>
                        </td>
                        <td><?= btn_add() ?></td>
                    </tr>
                </form>
                </tbody>
            </table>
        </div>
    </div>
</div>
