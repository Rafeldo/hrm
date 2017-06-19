<?php
$user_type = $this->session->userdata('user_type');
if ($user_type == '1') { ?>
    <div class="row">
        <div class="col-sm-12" data-offset="0">
            <div class="panel panel-custom" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <strong><?= lang('view_time_history') ?></strong>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="attendance-form" role="form" enctype="multipart/form-data"
                          action="<?php echo base_url(); ?>admin/attendance/time_history" method="post"
                          class="form-horizontal form-groups-bordered">
                        <div class="form-group" id="border-none">
                            <label for="field-1" class="col-sm-3 control-label"><?= lang('employee') ?> <span
                                    class="required">*</span></label>
                            <div class="col-sm-5">
                                <select name="user_id" style="width: 100%" id="employee"
                                        class="form-control select_box">
                                    <option value=""><?= lang('select_employee') ?>...</option>
                                    <?php if (!empty($all_employee)): ?>
                                        <?php foreach ($all_employee as $dept_name => $v_all_employee) : ?>
                                            <optgroup label="<?php echo $dept_name; ?>">
                                                <?php if (!empty($v_all_employee)):foreach ($v_all_employee as $v_employee) : ?>
                                                    <option value="<?php echo $v_employee->user_id; ?>"
                                                        <?php
                                                        if (!empty($user_id)) {
                                                            $user_id = $user_id;
                                                        } else {
                                                            $user_id = $this->session->userdata('user_id');
                                                        }
                                                        if (!empty($user_id)) {
                                                            echo $v_employee->user_id == $user_id ? 'selected' : '';
                                                        }
                                                        ?>><?php echo $v_employee->fullname . ' ( ' . $v_employee->designations . ' )' ?></option>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-sm-2 ">
                                <button type="submit" name="search" value="1"
                                        class="btn btn-primary"><?= lang('go') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php }
if (!empty($edit)) {
    $user_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
    $name = $user_info->fullname;
} else {
    $name = lang('my');
}

?>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <div class="panel-title">
                    <strong><?= $name . ' ' . lang('time_logs') ?></strong>
                </div>
            </div>
            <div class="custom-tabs" role="tabpanel">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <?php
                    define("SECONDS_PER_HOUR", 60 * 60);
                    if (!empty($mytime_info)):foreach ($mytime_info as $year => $v_time_info):
                        ?>
                        <?php if (!empty($v_time_info)): ?>
                        <li role="presentation" class="<?php
                        if ($year == $active) {
                            echo 'active';
                        }
                        ?>"><a href="#<?php echo $year ?>" aria-controls="home" role="tab"
                               data-toggle="tab"><?php echo $year ?></a></li>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content" id="custom-tab-content">
                    <?php if (!empty($mytime_info)) :
                    foreach ($mytime_info as $year => $v_time_info):
                        ?>
                    <div role="tabpanel" class="tab-pane <?php
                    if ($year == $active) {
                        echo 'active';
                    }
                    ?>" id="<?php echo $year ?>">
                        <?php if (!empty($v_time_info)): foreach ($v_time_info as $week => $time_info): ?>
                        <?php if (!empty($time_info)): ?>
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-custom">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title time-color">
                                            <a data-toggle="collapse" data-parent="#accordion"
                                               href="#<?php echo $week; ?>" aria-expanded="true"
                                               aria-controls="collapseOne">
                                                Week : <?php echo $week; ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="<?php echo $week; ?>" class="panel-collapse collapse" role="tabpanel"
                                         aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th><?= lang('clock_in_time') ?></th>
                                                    <th><?= lang('clock_out_time') ?></th>
                                                    <th><?= lang('hours') ?></th>
                                                    <th><?= lang('action') ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $total_hh = 0;
                                                $total_mm = 0;
                                                if (!empty($time_info)):foreach ($time_info as $key => $v_mytime):
                                                    ?>
                                                    <td colspan="4"
                                                        style="background: rgba(233, 237, 228, 0.73);font-weight: bold"><?php echo $key; ?></td>

                                                    <?php
                                                    foreach ($v_mytime as $mytime):?>
                                                        <tr>
                                                            <?php if ($mytime->attendance_status == 0) {
                                                                ?>
                                                                <td colspan="3"><span class="label label-danger"><?= lang('absent') ?></span>
                                                                </td>
                                                                <?php
                                                            } elseif ($mytime->attendance_status == 3) { ?>
                                                                <td colspan="3"><span
                                                                        class="label label-danger"><?= lang('on_leave') ?></span>
                                                                </td>
                                                            <?php } else { ?>


                                                                <td><?php echo date('h:i A', strtotime($mytime->clockin_time)); ?></td>
                                                                <td><?php
                                                                    if (empty($mytime->clockout_time)) {
                                                                        echo '<span class="text-danger">' . lang("currently_clock_in") . '<span>';
                                                                    } else {
                                                                        echo date('h:i A', strtotime($mytime->clockout_time));
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td><?php
                                                                    if (!empty($mytime->clockout_time)) {
                                                                        // calculate the start timestamp
                                                                        $startdatetime = strtotime($mytime->date_in . " " . $mytime->clockin_time);
                                                                        // calculate the end timestamp
                                                                        $enddatetime = strtotime($mytime->date_out . " " . $mytime->clockout_time);
                                                                        // calulate the difference in seconds
                                                                        $difference = $enddatetime - $startdatetime;
                                                                        // hours is the whole number of the division between seconds and SECONDS_PER_HOUR
                                                                        $hoursDiff = $difference / SECONDS_PER_HOUR;
                                                                        $total_hh += round($hoursDiff);
                                                                        // and the minutes is the remainder
                                                                        $minutesDiffRemainder = $difference % SECONDS_PER_HOUR / 60;
                                                                        $total_mm += round($minutesDiffRemainder) % 60;
                                                                        // output the result
                                                                        echo round($hoursDiff) . " : " . round($minutesDiffRemainder) . " m";
                                                                    }
                                                                    ?></td>
                                                                <?php if (!empty($mytime->clock_id)) { ?>
                                                                    <td><?php echo btn_edit_modal('admin/attendance/edit_mytime/' . $mytime->clock_id) ?></td>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php endforeach; ?>

                                                <?php endforeach; ?>
                                                    <table>
                                                        <tr>
                                                            <td colspan="2" class="text-right">
                                                                <strong style="margin-right: 10px; ">Total
                                                                    Working
                                                                    Hour: </strong>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if ($total_mm > 60) {
                                                                    $final_mm = $total_mm - 60;
                                                                    $final_hh = $total_hh + 1;
                                                                } else {
                                                                    $final_mm = $total_mm;
                                                                    $final_hh = $total_hh;
                                                                }
                                                                echo $final_hh . " : " . $final_mm . " m";
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="6">
                                                            <?= lang('nothing_to_display') ?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <?= lang('nothing_to_display') ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>