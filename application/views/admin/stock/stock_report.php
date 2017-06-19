<div class="well">
    <div class="row">
        <div class="col-sm-12">
            <form role="form" id="sales_report" action="<?php echo base_url() ?>admin/stock/report" method="post">
                <div class="col-sm-5">
                    <div class="form-group">
                        <label class="control-label"><?= lang('start_date') ?><span class="required"> *</span></label>
                        <div class="input-group">
                            <input type="text" value="<?php if(!empty($date['start_date'])){ echo $date['start_date'];}?>" class="form-control datepicker" name="start_date"
                                   data-format="yyyy-mm-dd">

                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="form-group">
                        <label class="control-label"><?= lang('end_date') ?><span class="required"> *</span></label>
                        <div class="input-group">
                            <input type="text" value="<?php if(!empty($date['end_date'])){ echo $date['end_date'];}?>" class="form-control datepicker" name="end_date"
                                   data-format="yyyy-mm-dd">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label"></label>
                        <div class="">
                            <button type="submit" name="flag" value="1" data-toggle="tooltip" data-placement="top"
                                    title="<?= lang('search') ?>" class="btn btn-primary"><i
                                    class="fa fa-search fa-2x"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<style type="text/css">
    .custom-bg{
        background: #f0f0f0;
    }
</style>
<?php

if (!empty($assign_report)): ?>
    <div class="panel panel-custom">
        <!-- Default panel contents -->
        <div class="panel-heading">
            <div class="panel-title">

                <strong class="hidden-print"><?= lang('report_list') ?></strong>

                <div class="pull-right hidden-print">
                    <span><?php echo btn_pdf('admin/stock/assign_report_pdf/' . $date['start_date'] . '/' . $date['end_date']); ?></span>
                </div>

            </div>
        </div>
        <table class="table table-bordered table-hover">
            <?php if (!empty($assign_report)): foreach ($assign_report as $item_name => $v_assign_report) :
            ?>
            <thead>
            <tr class="color-black heading_print">
                <th colspan="7"><strong><?php echo $item_name; ?></strong></th>
            </tr>

            <tr>
                <th><?= lang('assigned_user') ?></th>
                <th><?= lang('assign_date') ?></th>
                <th><?= lang('assign_quantity') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total_assign_inventory = 0;
            if (!empty($v_assign_report)): foreach ($v_assign_report as $v_report) :
                ?>

                <tr class="custom-tr custom-font-print">
                    <td class="vertical-td"><?php echo $v_report->fullname ?></td>
                    <td><?= strftime(config_item('date_format'), strtotime($v_report->assign_date)); ?></td>
                    <td class="vertical-td"><?php echo $v_report->assign_inventory; ?> </td>
                    <?php
                    $total_assign_inventory += $v_report->assign_inventory;
                    ?>

                </tr>
            <?php endforeach; ?>
                <tr class="custom-bg">
                    <th style="text-align: right;" colspan="2">
                        <strong><?= lang('total') ?> <?php echo $v_report->item_name ?>
                            :</strong>
                    </th>
                    <td align=""><?php
                        echo $total_assign_inventory;
                        ?>
                        <span class="pull-right"><?= lang('available_stock') ?>
                            :<strong> <?php echo $v_report->total_stock; ?></strong></span>
                    </td>
                </tr>
            <?php endif; ?>
            <?php endforeach; ?>
            <?php else : ?>
                <td colspan="12">
                    <strong><?= lang('nothing_to_display') ?></strong>
                </td>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
