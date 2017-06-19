<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= lang('from_items') ?></h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <form role="form" id="from_items"
              action="<?php echo base_url(); ?>admin/invoice/add_insert_items/<?= $invoices_id ?>" method="post"
              class="form-horizontal form-groups-bordered">

            <div class="form-group">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <!--                            <th class="col-sm-1"><input type="checkbox" id="parent_present"></th>-->
                        <th class="col-sm-1"><?= lang('select') ?></th>
                        <th class="col-sm-1"><?= lang('qty') ?></th>
                        <th><?= lang('item_name') ?></th>
                        <th><?= lang('description') ?></th>
                        <th><?= lang('tax_rate') ?> </th>
                        <th class="col-sm-2"><?= lang('unit_price') ?></th>
                        <th class="col-sm-1"><?= lang('tax') ?></th>
                        <th><?= lang('total') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $invoice_items = $this->db->get('tbl_saved_items')->result();

                    if (!empty($invoice_items)) :
                        foreach ($invoice_items as $v_item) :
                            ?>
                            <tr>
                                <td><input class="child_present" type="checkbox" name="saved_items_id[]"
                                           value="<?php echo $v_item->saved_items_id; ?>"/>
                                </td>
                                <td><?= $v_item->quantity ?></td>
                                <td><?= $v_item->item_name ?></td>
                                <td><?= nl2br($v_item->item_desc) ?></td>
                                <td><?= $v_item->item_tax_rate ?>%</td>
                                <td><?= display_money($v_item->unit_cost) ?></td>
                                <td><?= display_money($v_item->item_tax_total) ?></td>
                                <td><?= display_money($v_item->total_cost) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
                <button type="submit" class="btn btn-primary"><?= lang('upload') ?></button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    /*
     * Select All select
     */
    $(function () {
        $('#parent_present').on('change', function () {
            $('.child_present').prop('checked', $(this).prop('checked'));
        });
        $('.child_present').on('change', function () {
            $('.child_present').prop($('.child_present:checked').length ? true : false);
        });
    });
    $(document).ready(function () {
        $("#from_items").validate({
            rules: {
                saved_items_id: {
                    required: true,
                }
            }
        });
    });</script>
<script src="<?php echo base_url(); ?>asset/js/custom-validation.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/js/jquery.validate.js" type="text/javascript"></script>
