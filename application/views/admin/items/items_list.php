<?= message_box('success'); ?>
<?= message_box('error'); ?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="#manage"
                                                            data-toggle="tab"><?= lang('all_items') ?></a></li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="#create"
                                                            data-toggle="tab"><?= lang('new_items') ?></a></li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">

            <div class="table-responsive">
                <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th><?= lang('qty') ?></th>
                        <th><?= lang('item_name') ?></th>
                        <th><?= lang('description') ?></th>
                        <th><?= lang('unit_price') ?></th>
                        <th class="col-options no-sort"><?= lang('action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $all_items = $this->db->get('tbl_saved_items')->result();

                    $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                    $total_balance = 0;
                    foreach ($all_items as $v_items):
                        ?>
                        <tr>
                            <td><?= $v_items->quantity ?></td>
                            <td><?= $v_items->item_name ?></td>
                            <td><?= $v_items->item_desc ?></td>
                            <td><?=
                                display_money($v_items->unit_cost, $currency->symbol);
                                ?></td>
                            <td>
                                <?= btn_edit('admin/items/items_list/' . $v_items->saved_items_id) ?>
                                <?= btn_delete('admin/items/delete_items/' . $v_items->saved_items_id) ?>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
            <form role="form" enctype="multipart/form-data" id="form"
                  action="<?php echo base_url(); ?>admin/items/saved_items/<?php
                  if (!empty($items_info)) {
                      echo $items_info->saved_items_id;
                  }
                  ?>" method="post" class="form-horizontal  ">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('item_name') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" value="<?php
                        if (!empty($items_info)) {
                            echo $items_info->item_name;
                        }
                        ?>" name="item_name" required="">
                    </div>

                </div>
                <!-- End discount Fields -->
                <div class="form-group terms">
                    <label class="col-lg-3 control-label"><?= lang('description') ?> </label>
                    <div class="col-lg-5">
                        <textarea name="item_desc" class="form-control"><?php
                            if (!empty($items_info)) {
                                echo $items_info->item_desc;
                            }
                            ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('unit_price') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <input type="number" class="form-control" value="<?php
                        if (!empty($items_info)) {
                            echo $items_info->unit_cost;
                        }
                        ?>" name="unit_cost" required="">
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('quantity') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <input type="number" class="form-control" value="<?php
                        if (!empty($items_info)) {
                            echo $items_info->quantity;
                        }
                        ?>" name="quantity" required="">
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('tax_rate') ?> </label>
                    <div class="col-lg-5">
                        <select name="item_tax_rate" class="form-control">
                            <option value="0.00"><?= lang('none') ?></option>
                            <?php
                            $tax_rates = $this->db->get('tbl_tax_rates')->result();
                            if (!empty($tax_rates)) {
                                foreach ($tax_rates as $v_tax) {
                                    ?>
                                    <option value="<?= $v_tax->tax_rate_percent ?>" <?php
                                    if (!empty($item_info) && $item_info->item_tax_rate == $v_tax->tax_rate_percent) {
                                        echo 'selected';
                                    }
                                    ?>><?= $v_tax->tax_rate_name ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-5">
                        <button type="submit" class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>