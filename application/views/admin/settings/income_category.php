<?= message_box('success'); ?>
<?= message_box('error'); ?>
<div class="panel panel-custom">
    <header class="panel-heading "><?= lang('income_category') ?></header>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped ">
                <thead>
                <tr>

                    <th><?= lang('income_category') ?></th>
                    <th><?= lang('description') ?></th>
                    <th><?= lang('action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($all_income_category)) {
                    foreach ($all_income_category as $income_category) {
                        ?>
                        <tr>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $income_category->income_category_id) { ?>
                                <form method="post"
                                      action="<?= base_url() ?>admin/settings/income_category/update_income_category/<?php
                                      if (!empty($income_category_info)) {
                                          echo $income_category_info->income_category_id;
                                      }
                                      ?>" class="form-horizontal">
                                    <input type="text" name="income_category" value="<?php
                                    if (!empty($income_category_info)) {
                                        echo $income_category_info->income_category;
                                    }
                                    ?>" class="form-control" placeholder="<?= lang('income_category') ?>" required>
                                <?php } else {
                                    echo $income_category->income_category;
                                }
                                ?></td>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $income_category->income_category_id) { ?>
                                    <textarea name="description" rows="1" class="form-control"><?php
                                        if (!empty($income_category_info)) {
                                            echo $income_category_info->description;
                                        }
                                        ?></textarea>
                                <?php } else {
                                    echo $income_category->description;
                                }
                                ?></td>
                            <td>
                                <?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $income_category->income_category_id) { ?>
                                    <?= btn_update() ?>
                                    </form>
                                    <?= btn_cancel('admin/settings/income_category/') ?>
                                <?php } else { ?>
                                    <?= btn_edit('admin/settings/income_category/edit_income_category/' . $income_category->income_category_id) ?>
                                    <?= btn_delete('admin/settings/delete_income_category/' . $income_category->income_category_id) ?>
                                <?php }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                <form method="post" action="<?= base_url() ?>admin/settings/income_category/update_income_category" class="form-horizontal">
                    <tr>
                        <td><input type="text" name="income_category" class="form-control" placeholder="<?= lang('income_category') ?>" required></td>
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