<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong><?= lang('generate_payslip') ?></strong>
                </div>
            </div>
            <form id="form" role="form" enctype="multipart/form-data"
                  action="<?php echo base_url() ?>admin/payroll/generate_payslip" method="post"
                  class="form-horizontal form-groups-bordered">
                <div class="panel-body">
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('select_department') ?>
                            <span
                                class="required"> *</span></label>

                        <div class="col-sm-5">
                            <select name="departments_id" class="form-control select_box">
                                <option value=""><?= lang('select_department') ?></option>
                                <?php if (!empty($all_department_info)): foreach ($all_department_info as $v_department_info) :
                                    if (!empty($v_department_info->deptname)) {
                                        $deptname = $v_department_info->deptname;
                                    } else {
                                        $deptname = lang('undefined_department');
                                    }
                                    ?>
                                    <option value="<?php echo $v_department_info->departments_id; ?>"
                                        <?php
                                        if (!empty($departments_id)) {
                                            echo $v_department_info->departments_id == $departments_id ? 'selected' : '';
                                        }
                                        ?>><?php echo $deptname ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('month') ?> <span
                                class="required"> *</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" value="<?php
                                if (!empty($payment_month)) {
                                    echo $payment_month;
                                }
                                ?>" class="form-control monthyear" name="payment_month"
                                       data-format="yyyy/mm/dd">

                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="border-none">
                        <label for="field-1" class="col-sm-3 control-label"></label>
                        <div class="col-sm-5">
                            <button id="submit" type="submit" name="flag" value="1"
                                    class="btn btn-primary btn-block"><?= lang('go') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php if (!empty($flag)): ?>
    <div class="row">
        <div class="col-sm-12" data-offset="0">
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="panel-title">
                        <span>
                            <strong><?= lang('generate_payslip_for') ?><?php
                                if (!empty($payment_month)) {
                                    echo ' <span class="text-danger">' . date('F Y', strtotime($payment_month)) . '</span>';
                                }
                                ?></strong>
                        </span>
                    </div>
                </div>
                <!-- Table -->

                <table class="table table-striped " id="Transation_DataTables" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="col-sm-1"><?= lang('emp_id') ?></th>
                        <th><strong><?= lang('name') ?></strong></th>
                        <th><strong><?= lang('salary_type') ?></strong></th>
                        <th><strong><?= lang('basic_salary') ?></strong></th>
                        <th><strong><?= lang('net_salary') ?></strong></th>
                        <th><strong><?= lang('details') ?></strong></th>
                        <th><strong><?= lang('status') ?></strong></th>
                        <th><?= lang('action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($employee_info)):foreach ($employee_info as $key => $v_emp_info): ?>
                        <?php if (!empty($v_emp_info)):foreach ($v_emp_info as $v_employee): ?>
                            <tr>
                                <td><?php echo $v_employee->employment_id; ?></td>
                                <td>
                                    <?php if (!empty($salary_info[$key]) && $salary_info[$key]->user_id == $v_employee->user_id) { ?>
                                        <a href="<?php echo base_url() ?>admin/payroll/salary_payment_details/<?php echo $salary_info[$key]->salary_payment_id ?>"
                                           title="View" data-toggle="modal"
                                           data-target="#myModal_lg"><?php echo $v_employee->fullname; ?></a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url() ?>admin/payroll/view_payment_details/<?php echo $v_employee->user_id . '/' . $payment_month ?>"
                                           title="View" data-toggle="modal"
                                           data-target="#myModal_lg"><?php echo $v_employee->fullname; ?></a>
                                    <?php } ?>

                                </td>
                                <td><?php
                                    if (!empty($v_employee->salary_grade)) {
                                        echo $v_employee->salary_grade . ' <small>(' . lang('monthly') . ')</small>';
                                    } else {
                                        echo $v_employee->hourly_grade . ' <small>(' . lang('hourly') . ')</small>';
                                    }
                                    ?></td>
                                <td><?php
                                    if (!empty($v_employee->basic_salary)) {
                                        echo $v_employee->basic_salary;
                                    } else {
                                        echo $v_employee->hourly_rate . ' <small>(' . lang('per_hour') . ')</small>';
                                    }
                                    ?></td>
                                <td><?php
                                    if (!empty($total_hours)) {
                                        foreach ($total_hours as $index => $v_total_hours) {
                                            if ($index == $v_employee->user_id) {
                                                if (!empty($v_total_hours)) {
                                                    $total_hour = $v_total_hours['total_hours'];
                                                    $total_minutes = $v_total_hours['total_minutes'];
                                                    if ($total_hour > 0) {
                                                        $hours_ammount = $total_hour * $v_employee->hourly_rate;
                                                    } else {
                                                        $hours_ammount = 0;
                                                    }
                                                    if ($total_minutes > 0) {
                                                        $amount = 60 / $v_employee->hourly_rate;
                                                        $minutes_ammount = $total_minutes * $amount;
                                                    } else {
                                                        $minutes_ammount = 0;
                                                    }
                                                    if (!empty($advance_salary[$index])) {
                                                        $advance_amount = $advance_salary[$index];
                                                    } else {
                                                        $advance_amount = 0;
                                                    }
                                                    if (!empty($award_info[$index])) {
                                                        $total_award = $award_info[$index]['award_amount'];
                                                    } else {
                                                        $total_award = 0;
                                                    }
                                                    $total_amount = $hours_ammount + $minutes_ammount + $total_award - $advance_amount;
                                                    echo round($total_amount, 2);
                                                }
                                            }
                                        }
                                    }
                                    if (!empty($v_employee->basic_salary)) {
                                        if (!empty($allowance_info)) {
                                            foreach ($allowance_info as $al_index => $v_allowance) {
                                                if ($al_index == $v_employee->user_id) {
                                                    $total_allowance = $v_allowance;
                                                }
                                            }
                                        }
                                        if (!empty($deduction_info)) {
                                            foreach ($deduction_info as $dd_index => $v_deduction) {
                                                if ($dd_index == $v_employee->user_id) {
                                                    $total_deduction = $v_deduction;
                                                }
                                            }
                                        }
                                        if (!empty($advance_salary)) {
                                            foreach ($advance_salary as $add_index => $v_advance) {
                                                if ($add_index == $v_employee->user_id) {
                                                    $total_advance = $v_advance['advance_amount'];
                                                }
                                            }
                                        }
                                        if (!empty($award_info)) {
                                            foreach ($award_info as $aw_index => $v_award_info) {
                                                if ($aw_index == $v_employee->user_id) {
                                                    $total_award = $v_award_info['award_amount'];
                                                }
                                            }
                                        }
                                        if (!empty($overtime_info) && !empty($v_employee->overtime_salary)) {
                                            foreach ($overtime_info as $over_index => $v_overtime) {
                                                if ($over_index == $v_employee->user_id) {
                                                    $total_hour = $v_overtime['overtime_hours'];
                                                    $total_minutes = $v_overtime['overtime_minutes'];
                                                    if ($total_hour > 0) {
                                                        $hours_ammount = $total_hour * $v_employee->overtime_salary;
                                                    } else {
                                                        $hours_ammount = 0;
                                                    }
                                                    if ($total_minutes > 0) {
                                                        $amount = 60 / $v_employee->overtime_salary;
                                                        $minutes_ammount = $total_minutes * $amount;
                                                    } else {
                                                        $minutes_ammount = 0;
                                                    }
                                                    $total_amount = $hours_ammount + $minutes_ammount;
                                                }
                                            }
                                        }
                                        if (empty($total_advance)) {
                                            $total_advance = 0;
                                        }
                                        if (empty($total_deduction)) {
                                            $total_deduction = 0;
                                        }
                                        if (empty($total_award)) {
                                            $total_award = 0;
                                        }
                                        if (empty($total_allowance)) {
                                            $total_allowance = 0;
                                        }
                                        if (empty($total_amount)) {
                                            $total_amount = 0;
                                        }
                                        if (!empty($total_allowance) || !empty($total_deduction) || !empty($total_advance) || !empty($total_award)) {
                                            echo $v_employee->basic_salary + $total_allowance + $total_amount + $total_award - $total_deduction - $total_advance;
                                        }
                                    }
                                    ?></td>

                                <td><?php if (!empty($salary_info[$key]) && $salary_info[$key]->user_id == $v_employee->user_id) { ?>
                                        <a href="<?php echo base_url() ?>admin/payroll/salary_payment_details/<?php echo $salary_info[$key]->salary_payment_id ?>"
                                           class="btn btn-info btn-xs" title="View" data-toggle="modal"
                                           data-target="#myModal_lg"><span class="fa fa-list-alt"></span></a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url() ?>admin/payroll/view_payment_details/<?php echo $v_employee->user_id . '/' . $payment_month ?>"
                                           class="btn btn-info btn-xs" title="View" data-toggle="modal"
                                           data-target="#myModal_lg"><span class="fa fa-list-alt"></span></a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (!empty($salary_info[$key]) && $salary_info[$key]->user_id == $v_employee->user_id) { ?>

                                        <span class="label label-success"><?= lang('paid') ?></span>
                                        <?php
                                    } else {
                                    ?>
                                    <span class="label label-danger"><?= lang('unpaid') ?>
                                        <?php } ?></span></td>
                                <td>
                                    <?php if (!empty($salary_info[$key]) && $salary_info[$key]->user_id == $v_employee->user_id) { ?>
                                        <a class="text-success"
                                           href="<?php echo base_url() ?>admin/payroll/receive_generated/<?php echo $salary_info[$key]->salary_payment_id; ?>"><?= lang('generate_payslip') ?></a>
                                    <?php } else { ?>
                                        <a class="text-danger"
                                           href="<?php echo base_url() ?>admin/payroll/make_payment/<?php echo $v_employee->user_id . '/' . $v_employee->departments_id . '/' . $payment_month; ?>"><?= lang('make_payment') ?></a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#Transation_DataTables').dataTable({
            paging: false,
            "bSort": false
        });
    });
</script>
<!-- end -->
