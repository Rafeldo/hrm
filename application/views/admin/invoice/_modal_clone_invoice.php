<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= lang('clone') . ' ' . lang('invoice') ?></h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <form role="form" id="from_items"
              action="<?php echo base_url(); ?>admin/invoice/cloned_invoice/<?= $invoice_info->invoices_id ?>"
              method="post"
              class="form-horizontal form-groups-bordered">

            <div class="form-group">
                <label class="col-lg-3 control-label"><?= lang('select') . ' ' . lang('client') ?> <span
                        class="text-danger">*</span>
                </label>
                <div class="col-lg-7">
                    <select class="form-control select_box" style="width: 100%" name="client_id" required>

                        <?php
                        if (!empty($all_client)) {
                            foreach ($all_client as $v_client) {
                                if ($v_client->client_status == 1) {
                                    $status = lang('person');
                                } else {
                                    $status =lang('company');
                                }
                                ?>
                                <option value="<?= $v_client->client_id ?>"
                                    <?php
                                    if (!empty($invoice_info)) {
                                        $invoice_info->client_id == $v_client->client_id ? 'selected' : '';
                                    }
                                    ?>
                                ><?= ucfirst($v_client->name) . ' <small>(' . $status . ')</small>' ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
                <button type="submit" class="btn btn-primary"><?= lang('clone') ?></button>
            </div>
        </form>
    </div>
</div>
