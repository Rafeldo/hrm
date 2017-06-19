<?php include_once 'asset/admin-ajax.php'; ?>
<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs" style="margin-top: 1px">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="<?= $active == 1 ? 'active' : '' ?>"><a href="#task_list"
                                                               data-toggle="tab"><?= lang('assign_stock_list') ?></a>
            </li>
            <li class="<?= $active == 2 ? 'active' : '' ?>"><a href="#assign_task"
                                                               data-toggle="tab"><?= lang('assign_stock') ?></a>
            </li>
        </ul>
        <div class="tab-content bg-white">
            <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="task_list" style="position: relative;">
                <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="col-sm-1"><?= lang('sl') ?></th>
                        <th><?= lang('item_name') ?></th>
                        <th><?= lang('stock_category') ?></th>
                        <th><?= lang('assign_quantity') ?></th>
                        <th><?= lang('assign_date') ?></th>
                        <th><?= lang('assigned_user') ?></th>
                        <th class="col-sm-1 hidden-print"><?= lang('action') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $all_assign_list = $this->stock_model->get_assign_stock_list();
                    if (!empty($all_assign_list)) {
                        foreach ($all_assign_list as $key => $v_assign_stock) { ?>

                            <tr>
                                <td><?php echo $key + 1 ?></td>
                                <td><?php echo $v_assign_stock->item_name ?></td>
                                <td><?php echo $v_assign_stock->stock_category . ' &succcurlyeq; ' . $v_assign_stock->stock_sub_category ?></td>
                                <td><?php echo $v_assign_stock->assign_inventory ?></td>
                                <td><?= strftime(config_item('date_format'), strtotime($v_assign_stock->assign_date)); ?></td>
                                <td><?= $v_assign_stock->fullname ?></td>
                                <td class="hidden-print">
                                    <?php echo btn_delete('admin/stock/delete_assign_stock/' . $v_assign_stock->assign_item_id); ?>
                                </td>

                            </tr>
                            <?php
                        };
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <!-- Add Stock Category tab Starts -->
            <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="assign_task" style="position: relative;">
                <form role="form" id="form" enctype="multipart/form-data"
                      action="<?php echo base_url() ?>admin/stock/set_assign_stock/<?php
                      if (!empty($assign_item->assign_item_id)) {
                          echo $assign_item->assign_item_id;
                      }
                      ?>" method="post" class="form-horizontal form-groups-bordered">
                    <div class="form-group ">
                        <label class="control-label col-sm-3"><?= lang('stock_category') ?><span
                                class="required">*</span></label>
                        <div class="col-sm-5">

                            <select name="stock_sub_category_id" style="width: 100%"
                                    class="form-control select_box"
                                    onchange="get_item_name_by_id(this.value)">
                                <option
                                    value=""><?= lang('select') . ' ' . lang('stock_category') ?></option>
                                <?php if (!empty($all_category_info)): foreach ($all_category_info as $cate_name => $v_category_info) : ?>
                                    <?php if (!empty($v_category_info)):
                                        if (!empty($cate_name)) {
                                            $cate_name = $cate_name;
                                        } else {
                                            $cate_name = lang('undefined_category');
                                        }
                                        ?>
                                        <optgroup label="<?php echo $cate_name; ?>">
                                            <?php foreach ($v_category_info as $sub_category) :
                                                if (!empty($sub_category->stock_sub_category)) {
                                                    ?>
                                                    <option
                                                        value="<?php echo $sub_category->stock_sub_category_id; ?>"
                                                        <?php
                                                        if (!empty($stock_info->stock_sub_category_id)) {
                                                            echo $sub_category->stock_sub_category_id == $stock_info->stock_sub_category_id ? 'selected' : '';
                                                        }
                                                        ?>><?php echo $sub_category->stock_sub_category ?></option>
                                                    <?php
                                                }
                                            endforeach;
                                            ?>
                                        </optgroup>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('item_name') ?><span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select class="form-control" name="stock_id" id="item_name">
                                <option value=""><?= lang('select') . ' ' . lang('item_name') ?></option>
                                <?php if (!empty($stock_info)): ?>
                                    <?php foreach ($stock_info as $v_stock_info): ?>
                                        <option value="<?php echo $v_stock_info->stock_id ?>" <?php
                                        if (!empty($assign_item->stock_id)) {
                                            echo $v_stock_info->stock_id == $assign_item->stock_id ? 'selected' : '';
                                        }
                                        ?>><?php echo $v_stock_info->item_name ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1"
                               class="col-sm-3 control-label"><?= lang('employee') . ' ' . lang('name') ?>
                            <span
                                class="required"> *</span></label>

                        <div class="col-sm-5">
                            <select class="form-control select_box" style="width: 100%" name="user_id">
                                <option value=""><?= lang('select_employee') ?>...</option>
                                <?php if (!empty($all_employee)): ?>
                                    <?php foreach ($all_employee as $dept_name => $v_all_employee) : ?>
                                        <optgroup label="<?php echo $dept_name; ?>">
                                            <?php if (!empty($v_all_employee)):foreach ($v_all_employee as $v_employee) : ?>
                                                <option value="<?php echo $v_employee->user_id; ?>"
                                                    <?php
                                                    if (!empty($assign_item->user_id)) {
                                                        echo $v_employee->user_id == $assign_item->user_id ? 'selected' : '';
                                                    }
                                                    ?>><?php echo $v_employee->fullname . ' ( ' . $v_employee->designations . ' )' ?></option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?= lang('assign_quantity') ?><span
                                class="required"> *</span></label>

                        <div class="col-sm-5">
                            <input type="text" name="assign_inventory"
                                   placeholder=" <?= lang('enter') . ' ' . lang('assign_quantity') ?>"
                                   class="form-control" value="<?php
                            if (!empty($assign_item->assign_inventory)) {
                                echo $assign_item->assign_inventory;
                            }
                            ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?= lang('assign_date') ?><span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" name="assign_date"
                                       placeholder="<?= lang('enter') . ' ' . lang('assign_date') ?>"
                                       class="form-control datepicker" value="<?php
                                if (!empty($assign_item->assign_date)) {
                                    echo $assign_item->assign_date;
                                }
                                ?>" data-format="dd-mm-yyyy">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"></label>
                        <div class="col-sm-5">
                            <button type="submit" id="sbtn"
                                    class="btn btn-primary"><?= lang('save') ?></button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </ul>
</div>