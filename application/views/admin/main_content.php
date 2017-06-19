<link href="<?php echo base_url() ?>asset/css/fullcalendar.css" rel="stylesheet" type="text/css">
<style type="text/css">
    .datepicker {
        z-index: 1151 !important;
    }

    .mt-sm {
        font-size: 14px;
    }
</style>
<?php
echo message_box('success');
echo message_box('error');
$curency = $this->admin_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');

?>
<div class="dashboard row">

    <div class="">
        <!--        ******** transactions ************** -->
        <?php if ($this->session->userdata('user_type') == 1) { ?>
            <div class="col-sm-4">
                <div class="panel widget">
                    <div class="row row-table row-flush">
                        <div class="col-xs-6 bb br">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-info">
                                    <em class="fa fa-plus fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?php
                                            if (!empty($today_income)) {
                                                $today_income = $today_income;
                                            } else {
                                                $today_income = '0';
                                            }
                                            echo display_money($today_income, $curency->symbol);
                                            ?>
                                        </h4>
                                        <p class="mb0 text-muted"><?= lang('income_today') ?></p>
                                        <small><a href="<?= base_url() ?>admin/transactions/deposit"
                                                  class="mt0 mb0"><?= lang('more_info') ?> <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6 bb">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-danger">
                                    <em class="fa fa-minus fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"> <?php
                                            if (!empty($today_expense)) {
                                                $today_expense = $today_expense;
                                            } else {
                                                $today_expense = '0';
                                            }
                                            echo display_money($today_expense, $curency->symbol);
                                            ?></h4>
                                        <p class="mb0 text-muted"><?= lang('expense_today') ?></p>
                                        <small><a href="<?= base_url() ?>admin/transactions/expense"
                                                  class=" small-box-footer"><?= lang('more_info') ?>
                                                <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-table row-flush">
                        <div class="col-xs-6 br">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-info">
                                    <em class="fa fa-plus fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?php
                                            if (!empty($total_income)) {
                                                $total_income = $total_income;
                                            } else {
                                                $total_income = '0';
                                            }

                                            echo display_money($total_income, $curency->symbol);
                                            ?></h4>
                                        <p class="mb0 text-muted"><?= lang('total_income') ?></p>
                                        <small><a href="<?= base_url() ?>admin/transactions/deposit"
                                                  class="mt0 mb0"><?= lang('more_info') ?> <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-danger">
                                    <em class="fa fa-minus fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"> <?php
                                            if (!empty($total_expense)) {
                                                $total_expense = $total_expense;
                                            } else {
                                                $total_expense = '0';
                                            }
                                            echo display_money($total_expense, $curency->symbol);
                                            ?></h4>
                                        <p class="mb0 text-muted"><?= lang('total_expense') ?></p>
                                        <small><a href="<?= base_url() ?>admin/transactions/expense"
                                                  class="small-box-footer"><?= lang('more_info') ?>
                                                <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--        ******** Sales ************** -->
            <div class="col-sm-4">
                <div class="panel widget">
                    <div class="row row-table row-flush">
                        <div class="col-xs-6 bb br">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center ">
                                    <em class="fa fa-shopping-cart fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?php
                                            $date = date('Y-m-d');
                                            $all_items = $this->db->get('tbl_items')->result();
                                            $today_invoice = 0;
                                            if (!empty($all_items)) {
                                                foreach ($all_items as $in_items) {
                                                    $invoice_date = date('Y-m-d', strtotime($in_items->date_saved));
                                                    if ($invoice_date == $date) {
                                                        $today_invoice += $in_items->total_cost;
                                                    }
                                                }

                                            }
                                            echo display_money($today_invoice, $curency->symbol);

                                            ?></h4>
                                        <p class="mb0 text-muted"><?= lang('invoice_today') ?></p>
                                        <small><a href="<?= base_url() ?>admin/invoice/manage_invoice"
                                                  class="mt0 mb0"><?= lang('more_info') ?> <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6 bb">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-purple">
                                    <em class="fa fa-money fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"> <?php
                                            echo display_money($this->db->select_sum('amount')->where('payment_date', $date)->get('tbl_payments')->row()->amount, $curency->symbol);

                                            ?></h4>
                                        <p class="mb0 text-muted"><?= lang('payment_today') ?></p>
                                        <small><a href="<?= base_url() ?>admin/invoice/all_payments"
                                                  class=" small-box-footer"><?= lang('more_info') ?>
                                                <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-table row-flush">
                        <div class="col-xs-6 br">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-purple">
                                    <em class="fa fa-money fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?php
                                            if (!empty($invoce_total)) {
                                                if (!empty($invoce_total['paid'])) {
                                                    $paid = 0;
                                                    foreach ($invoce_total['paid'] as $cur => $total) {
                                                        $paid += $total;
                                                    }
                                                    echo display_money($paid, $curency->symbol);
                                                } else {
                                                    echo '0.00';
                                                }
                                            } else {
                                                echo '0.00';
                                            }
                                            ?></h4>
                                        <p class="mb0 text-muted"><?= lang('paid_amount') ?></p>
                                        <small><a href="<?= base_url() ?>admin/invoice/all_payments"
                                                  class="mt0 mb0"><?= lang('more_info') ?> <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-danger">
                                    <em class="fa fa-usd fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"> <?php
                                            if (!empty($invoce_total)) {
                                                $total_due = 0;
                                                if (!empty($invoce_total['due'])) {
                                                    foreach ($invoce_total['due'] as $cur => $d_total) {
                                                        $total_due += $d_total;
                                                    }
                                                    echo display_money($total_due, $curency->symbol);
                                                } else {
                                                    echo '0.00';
                                                }
                                            } else {
                                                echo '0.00';
                                            }
                                            ?></h4>
                                        <p class="mb0 text-muted"><?= lang('due_amount') ?></p>
                                        <small><a href="<?= base_url() ?>admin/invoice/manage_invoice"
                                                  class="small-box-footer"><?= lang('more_info') ?>
                                                <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--        ******** Ticket ************** -->
            <div class="col-sm-4">
                <div class="panel widget">
                    <div class="row row-table row-flush">
                        <div class="col-xs-6 bb br">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-danger">
                                    <em class="fa fa-tasks fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?php
                                            echo count($this->db->where('task_status', 'in_progress')->get('tbl_task')->result());
                                            ?></h4>
                                        <p class="mb0 text-muted"><?= lang('in_progress') . ' ' . lang('task') ?></p>
                                        <small><a href="<?= base_url() ?>admin/tasks/all_task"
                                                  class="mt0 mb0"><?= lang('more_info') ?> <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6 bb">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-danger">
                                    <em class="fa fa-ticket fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"> <?= count($this->db->where('status', 'open')->get('tbl_tickets')->result()); ?></h4>
                                        <p class="mb0 text-muted"><?= lang('open') . ' ' . lang('tickets') ?></p>
                                        <small><a href="<?= base_url() ?>admin/tickets/open"
                                                  class=" small-box-footer"><?= lang('more_info') ?>
                                                <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-table row-flush">
                        <div class="col-xs-6 br">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-danger">
                                    <em class="fa fa-folder-open fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?= count($this->db->where(array('date_in' => date('Y-m-d'), 'attendance_status' => '0'))->get('tbl_attendance')->result()) ?></h4>
                                        <p class="mb0 text-muted"><?= lang('absent') . ' ' . lang('today') ?></p>
                                        <small><a href="<?= base_url() ?>admin/attendance/attendance_report"
                                                  class="small-box-footer"><?= lang('more_info') ?>
                                                <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-danger">
                                    <em class="fa fa-file-text fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?php
                                            $awhere = array('date_in' => date('Y-m-d'), 'attendance_status' => '1');
                                            echo count($this->db->where($awhere)->get('tbl_attendance')->result());
                                            ?></h4>
                                        <p class="mb0 text-muted"><?= lang('today') . ' ' . lang('present') ?></p>
                                        <small><a href="<?= base_url() ?>admin/attendance/attendance_report"
                                                  class="small-box-footer"><?= lang('more_info') ?>
                                                <i class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="clearfix visible-sm-block "></div>
        <?php
        // tasks
        $task_all_info = $this->admin_model->get_permission('tbl_task');

        $task_overdue = 0;

        if (!empty($task_all_info)):
            foreach ($task_all_info as $v_task_info):
                $due_date = $v_task_info->due_date;
                $due_time = strtotime($due_date);
                $current_time = time();
                if ($current_time > $due_time && $v_task_info->task_progress < 100) {
                    $task_overdue += count($v_task_info->task_id);
                }
            endforeach;
        endif;

        // invoice
        $all_invoices_info = $this->admin_model->get_permission('tbl_invoices');
        $invoice_overdue = 0;
        if (!empty($all_invoices_info)) {
            foreach ($all_invoices_info as $v_invoices) {
                $payment_status = $this->invoice_model->get_payment_status($v_invoices->invoices_id);
                if (strtotime($v_invoices->due_date) < time() AND $payment_status != lang('fully_paid')) {
                    $invoice_overdue += count($v_invoices->invoices_id);
                }
            }
        }
        // estimate
        $all_estimates_info = $this->admin_model->get_permission('tbl_estimates');
        $estimate_overdue = 0;
        if (!empty($all_estimates_info)) {
            foreach ($all_estimates_info as $v_estimates) {
                if (strtotime($v_estimates->due_date) < time() AND $v_estimates->status == 'Pending') {
                    $estimate_overdue += count($v_estimates->estimates_id);
                }
            }
        }
        $m = date("m"); // Month value
        $y = date("Y"); // Year value
        $num = cal_days_in_month(CAL_GREGORIAN, $m, $y);
        $employee = $this->db->where('company', '-')->get('tbl_account_details')->result();

        for ($i = 0; $i < count($employee); $i++) {
            if (!empty($employee[$i]->date_of_birth)) {
                $mem_bod_explode = explode("-", $employee[$i]->date_of_birth);

                $m_bday = mktime(0, 0, 0, $mem_bod_explode[1], $mem_bod_explode[2], $y);

                $start_date = date('Y-m', $m_bday) . '-01';

                $end_date = date('Y-m', $m_bday) . '-' . $num;


                if (date('Y-m-d') == date('Y-m-d', $m_bday)) {
                    $present_bday[] = $employee[$i];
                    $date = date('Y-m-d', $m_bday);
                    $pdate[] = date('d M Y', strtotime($date));
                } else if (date('Y-m-d') > $start_date && date('Y-m-d') <= $end_date) {
                    $future_bday[] = $employee[$i];
                    $date = date('Y-m-d', $m_bday);
                    $fdate[] = date('d M Y', strtotime($date));
                }

                $last_date = date('Y-m-d', $m_bday);
                $current_time = date('Y-m-d');
                if ($current_time > $last_date) {
                    $ribon = 'danger';
                    $today = date('Y-m-d');
                    $datetime1 = new DateTime($last_date);
                    $datetime2 = new DateTime($today);
                    $interval = $datetime1->diff($datetime2);
                    $text = $interval->days . ' ' . lang('days') . ' ' . lang('ago');
                } elseif ($current_time == $last_date) {
                    $ribon = 'info';
                    $text = lang('today');
                } else {
                    $today = date('Y-m-d');
                    $datetime1 = new DateTime($today);
                    $datetime2 = new DateTime($last_date);
                    $interval = $datetime1->diff($datetime2);

                    $ribon = 'success';
                    $text = $interval->days . lang('days') . ' ' . lang('left');
                }
            }
            $designation = $this->db->where('designations_id', $employee[$i]->designations_id)->get('tbl_designations')->row();
            $department = $this->db->where('departments_id', $designation->departments_id)->get('tbl_departments')->row();

            $pending_leave = 0;
            $all_leave_application = $this->db->get('tbl_leave_application')->result();
            if (!empty($all_leave_application)) {
                foreach ($all_leave_application as $v_all_leave) {
                    if ($v_all_leave->application_status == '1') {
                        $pending_leave += count($v_all_leave);
                    }
                }
            }
        }

        ?>
        <div class="col-md-12 mt-lg">
            <section class="panel panel-custom">
                <aside class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class=""><a href="#birthday"
                                        data-toggle="tab"><?= lang('birthday') . '-' . date("F") ?>
                                <strong class="pull-right ">(<?php
                                    if (!empty($present_bday)) {
                                        $total_bday[] = count($present_bday);
                                    }
                                    if (!empty($future_bday)) {
                                        $total_bday[] = count($future_bday);
                                    }
                                    if (!empty($total_bday)) {
                                        echo count($total_bday);
                                    }
                                    ?>)</strong>
                            </a></li>
                        <li class=""><a href="#tasks" data-toggle="tab"><?= lang('overdue') . ' ' . lang('tasks') ?>
                                <strong class="pull-right ">(<?= $task_overdue ?>)</strong>
                            </a></li>
                        <li class=""><a href="#invoice"
                                        data-toggle="tab"><?= lang('overdue') . ' ' . lang('invoice') ?>
                                <strong class="pull-right ">(<?= $invoice_overdue ?>)</strong>
                            </a></li>
                        <li class=""><a href="#estimate"
                                        data-toggle="tab"><?= lang('expired') . ' ' . lang('estimate') ?>
                                <strong class="pull-right ">(<?= $estimate_overdue ?>)</strong>
                            </a></li>

                        <li class=""><a href="#recent_applications"
                                        data-toggle="tab"><?= lang('recent') . ' ' . lang('applications') ?>
                            </a></li>
                        <li class=""><a href="#pending_leave"
                                        data-toggle="tab"><?= lang('pending') . ' ' . lang('leave') ?>
                                <strong class="pull-right ">(<?= $pending_leave ?>)</strong>
                            </a></li>
                    </ul>
                    <section class="scrollable">
                        <div class="tab-content">
                            <div class="tab-pane " id="birthday">
                                <div class="table-responsive">
                                    <table id="table-ext-1" class="table table-striped m-b-none text-sm">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?= lang('photo') ?></th>
                                            <th><?= lang('fullname') ?></th>
                                            <th><?= lang('designation') ?></th>
                                            <th><?= lang('birthday') ?></th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (!empty($present_bday)):foreach ($present_bday as $key => $v_bday): ?>

                                            <tr>

                                                <td>
                                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $v_bday->user_id ?>"><?= $v_bday->employment_id ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $v_bday->user_id ?>">
                                                        <div class="media">
                                                            <img src="<?php echo base_url() . $v_bday->avatar ?>"
                                                                 alt="Image" class="img-responsive img-circle">
                                                        </div>
                                                    </a>
                                                </td>

                                                <td>
                                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $v_bday->user_id ?>">
                                                        <?php echo $v_bday->fullname ?>
                                                        </span>
                                                        <div
                                                            class="pull-right label label-<?= $ribon ?>"><?= $text ?></div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php echo "$department->deptname" . ' &rArr; ' . $designation->designations;
                                                    if (!empty($department->department_head_id) && $department->department_head_id == $v_bday->user_id) { ?>
                                                        <strong
                                                            class="label label-warning"><?= lang('department_head') ?></strong>
                                                    <?php }
                                                    ?>
                                                </td>
                                                <td><?php
                                                    echo $pdate[$key];
                                                    ?></td>
                                                </a>

                                            </tr>
                                        <?php endforeach; ?>
                                        <?php endif; ?>

                                        <?php if (!empty($future_bday)):foreach ($future_bday as $key => $v_fbday): ?>
                                            <tr>

                                                <td>
                                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $v_fbday->user_id ?>"><?= $v_fbday->employment_id ?></a>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $v_fbday->user_id ?>">
                                                        <div class="media">
                                                            <img src="<?php echo base_url() . $v_fbday->avatar ?>"
                                                                 alt="Image" class="img-responsive img-circle">
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $v_fbday->user_id ?>"><?php echo $v_fbday->fullname ?>
                                                        <div
                                                            class="pull-right label label-<?= $ribon ?>"><?= $text ?></div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php echo "$department->deptname" . ' &rArr; ' . $designation->designations;
                                                    if (!empty($department->department_head_id) && $department->department_head_id == $v_fbday->user_id) { ?>
                                                        <strong
                                                            class="label label-warning"><?= lang('department_head') ?></strong>
                                                    <?php }
                                                    ?>
                                                </td>
                                                <td>

                                                    <?php
                                                    echo $fdate[$key];
                                                    ?></td>
                                                </a>

                                            </tr>
                                        <?php endforeach; ?>
                                        <?php endif; ?>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tasks">
                                <table class="table table-striped m-b-none text-sm" id="DataTables">
                                    <thead>
                                    <tr>
                                        <th><?= lang('task_name') ?></th>
                                        <th><?= lang('end_date') ?></th>
                                        <th><?= lang('progress') ?></th>
                                        <th class="col-options no-sort col-md-1"><?= lang('action') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($task_all_info)):foreach ($task_all_info as $v_task):
                                        $due_date = $v_task->due_date;
                                        $due_time = strtotime($due_date);
                                        $current_time = time();
                                        if ($current_time > $due_time && $v_task->task_progress < 100) {
                                            ?>
                                            <tr>
                                                <td><a class="text-info" style="<?php
                                                    if ($v_task->task_progress >= 100) {
                                                        echo 'text-decoration: line-through;';
                                                    }
                                                    ?>"
                                                       href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
                                                </td>
                                                <td>
                                                    <?= strftime(config_item('date_format'), strtotime($due_date)) ?>
                                                    <?php if ($current_time > $due_time && $v_task->task_progress < 100) { ?>
                                                        <span
                                                            class="label label-danger"><?= lang('overdue') ?></span>
                                                    <?php } ?></td>
                                                <td>
                                                    <div class="inline ">
                                                        <div class="easypiechart text-success" style="margin: 0px;"
                                                             data-percent="<?= $v_task->task_progress ?>"
                                                             data-line-width="5" data-track-Color="#f0f0f0"
                                                             data-bar-color="#<?php
                                                             if ($v_task->task_progress == 100) {
                                                                 echo '8ec165';
                                                             } else {
                                                                 echo 'fb6b5b';
                                                             }
                                                             ?>" data-rotate="270" data-scale-Color="false"
                                                             data-size="50"
                                                             data-animate="2000">
                                                        <span class="small text-muted"><?= $v_task->task_progress ?>
                                                            %</span>
                                                        </div>
                                                    </div>

                                                </td>

                                                <td><?= btn_view('admin/tasks/view_task_details/' . $v_task->task_id) ?></td>
                                            </tr>
                                            <?php
                                        }
                                    endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="invoice">
                                <table class="table table-striped m-b-none text-sm" id="DataTables">
                                    <thead>
                                    <tr>
                                        <th><?= lang('invoice') ?></th>
                                        <th class="col-date"><?= lang('due_date') ?></th>
                                        <th><?= lang('client_name') ?></th>
                                        <th class="col-currency"><?= lang('due_amount') ?></th>
                                        <th><?= lang('status') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    if (!empty($all_invoices_info)) {
                                        foreach ($all_invoices_info as $v_invoices) {
                                            $payment_status = $this->invoice_model->get_payment_status($v_invoices->invoices_id);
                                            if (strtotime($v_invoices->due_date) < time() AND $payment_status != lang('fully_paid')) {
                                                if ($payment_status == lang('fully_paid')) {
                                                    $invoice_status = lang('fully_paid');
                                                    $label = "success";
                                                } elseif ($v_invoices->emailed == 'Yes') {
                                                    $invoice_status = lang('sent');
                                                    $label = "info";
                                                } else {
                                                    $invoice_status = lang('draft');
                                                    $label = "default";
                                                }
                                                ?>
                                                <tr>
                                                    <td><a class="text-info"
                                                           href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_invoices->invoices_id ?>"><?= $v_invoices->reference_no ?>

                                                        </a>
                                                    </td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_invoices->due_date)) ?>
                                                        <span
                                                            class="label label-danger "><?= lang('overdue') ?></span>
                                                    </td>
                                                    <?php
                                                    $client_info = $this->invoice_model->check_by(array('client_id' => $v_invoices->client_id), 'tbl_client');

                                                    if ($client_info->client_status == 1) {
                                                        $status = lang('person');
                                                    } else {
                                                        $status = lang('company');;
                                                    }
                                                    ?>
                                                    <td><?= $client_info->name . ' (' . $status . ')'; ?></td>
                                                    <?php $currency = $this->invoice_model->client_currency_sambol($v_invoices->client_id); ?>
                                                    <td><?= display_money($this->invoice_model->calculate_to('invoice_due', $v_invoices->invoices_id), $curency->symbol); ?></td>
                                                    <td><span
                                                            class="label label-<?= $label ?>"><?= $invoice_status ?></span>
                                                        <?php if ($v_invoices->recurring == 'Yes') { ?>
                                                            <span data-toggle="tooltip" data-placement="top"
                                                                  title="<?= lang('recurring') ?>"
                                                                  class="label label-primary"><i
                                                                    class="fa fa-retweet"></i></span>
                                                        <?php } ?>

                                                    </td>

                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>

                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="estimate">
                                <table class="table table-striped m-b-none text-sm" id="DataTables">
                                    <thead>
                                    <tr>
                                        <th><?= lang('estimate') ?></th>
                                        <th><?= lang('due_date') ?></th>
                                        <th><?= lang('client_name') ?></th>
                                        <th><?= lang('amount') ?></th>
                                        <th><?= lang('status') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($all_estimates_info)) {
                                        foreach ($all_estimates_info as $v_estimates) {
                                            if (strtotime($v_estimates->due_date) < time() AND $v_estimates->status == 'Pending') {
                                                if ($v_estimates->status == 'Pending') {
                                                    $label = "info";
                                                } elseif ($v_estimates->status == 'Accepted') {
                                                    $label = "success";
                                                } else {
                                                    $label = "danger";
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <a class="text-info"
                                                           href="<?= base_url() ?>admin/estimates/index/estimates_details/<?= $v_estimates->estimates_id ?>"><?= $v_estimates->reference_no ?></a>
                                                    </td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_estimates->due_date)) ?>
                                                        <?php
                                                        if (strtotime($v_estimates->due_date) < time() AND $v_estimates->status == 'Pending') { ?>
                                                            <span
                                                                class="label label-danger "><?= lang('expired') ?></span>
                                                        <?php }
                                                        ?>
                                                    </td>
                                                    <?php
                                                    $client_info = $this->estimates_model->check_by(array('client_id' => $v_estimates->client_id), 'tbl_client');
                                                    if ($client_info->client_status == 1) {
                                                        $status = lang('person');
                                                    } else {
                                                        $status = lang('company');;
                                                    }
                                                    ?>
                                                    <td><?= $client_info->name . ' (' . $status . ')'; ?></td>
                                                    <?php $currency = $this->estimates_model->client_currency_sambol($v_estimates->client_id); ?>
                                                    <td><?= display_money($this->estimates_model->estimate_calculation('estimate_amount', $v_estimates->estimates_id), $curency->symbol); ?></td>
                                                    <td><span
                                                            class="label label-<?= $label ?>"><?= lang(strtolower($v_estimates->status)) ?></span>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="recent_applications">
                                <div class="table-responsive">
                                    <table class="table table-striped DataTables " id="">
                                        <thead>
                                        <tr>
                                            <th><?= lang('name') ?></th>
                                            <th><?= lang('start_date') ?></th>
                                            <th><?= lang('end_date') ?></th>
                                            <th><?= lang('leave_category') ?></th>
                                            <th><?= lang('status') ?></th>
                                            <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                <th class="col-sm-2"><?= lang('action') ?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $all_leave_application = $this->db->limit(5)->get('tbl_leave_application')->result();

                                        if (!empty($all_leave_application)) {
                                            foreach ($all_leave_application as $v_all_leave):
                                                $my_profile = $this->db->where('user_id', $v_all_leave->user_id)->get('tbl_account_details')->row();
                                                $my_leave_category = $this->db->where('leave_category_id', $v_all_leave->leave_category_id)->get('tbl_leave_category')->row();
                                                ?>
                                                <tr>
                                                    <td><?= $my_profile->fullname ?></td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_all_leave->leave_start_date)) ?></td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_all_leave->leave_end_date)) ?></td>
                                                    <td><?= $my_leave_category->leave_category ?></td>
                                                    <td><?php
                                                        if ($v_all_leave->application_status == '1') {
                                                            echo '<span class="label label-warning">' . lang('pending') . '</span>';
                                                        } elseif ($v_all_leave->application_status == '2') {
                                                            echo '<span class="label label-success">' . lang('accepted') . '</span>';
                                                        } else {
                                                            echo '<span class="label label-danger">' . lang('rejected') . '</span>';
                                                        }
                                                        ?></td>
                                                    <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                        <td>
                                                            <?php echo btn_view('admin/leave_management/index/view_details/' . $v_all_leave->leave_application_id) ?>
                                                            <?php if ($v_all_leave->application_status != '2') { ?>
                                                                <?php echo btn_delete('admin/leave_management/delete_application/' . $v_all_leave->leave_application_id) ?>
                                                            <?php } ?>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                                <?php
                                            endforeach;
                                        }
                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="pending_leave">
                                <table class="table table-striped m-b-none text-sm" id="DataTables">
                                    <thead>
                                    <tr>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('start_date') ?></th>
                                        <th><?= lang('end_date') ?></th>
                                        <th><?= lang('leave_category') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    $all_leave_application = $this->db->get('tbl_leave_application')->result();
                                    if (!empty($all_leave_application)) {
                                        foreach ($all_leave_application as $v_all_leave):
                                            if ($v_all_leave->application_status == '1') {
                                                $my_profile = $this->db->where('user_id', $v_all_leave->user_id)->get('tbl_account_details')->row();
                                                $my_leave_category = $this->db->where('leave_category_id', $v_all_leave->leave_category_id)->get('tbl_leave_category')->row();
                                                ?>
                                                <tr>
                                                    <td><?= $my_profile->fullname ?></td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_all_leave->leave_start_date)) ?></td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_all_leave->leave_end_date)) ?></td>
                                                    <td><?= $v_all_leave->leave_category ?></td>
                                                </tr>
                                                <?php
                                            }
                                        endforeach;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </aside>
                <?php if ($this->session->userdata('user_type') == '1') { ?>
                    <footer class="panel-footer bg-white no-padder">
                        <div class="row text-center no-gutter">

                            <div class="col-xs-4 b-r b-light">
                                <span
                                    class="h4 font-bold m-t block"><?= count($this->db->where('task_status', 'completed')->get('tbl_task')->result()) ?>
                                </span>
                                <small class="text-muted m-b block"><?= lang('complete_tasks') ?></small>
                            </div>
                            <div class="col-xs-4">
                                <span
                                    class="h4 font-bold m-t block"><?=
                                    display_money($this->db->select_sum('total_cost')->get('tbl_items')->row()->total_cost, $curency->symbol);
                                    ?>
                                </span>
                                <small
                                    class="text-muted m-b block"><?= lang('total') . ' ' . lang('invoice_amount') ?></small>

                            </div>
                            <div class="col-xs-4">
                                <span
                                    class="h4 font-bold m-t block"><?=
                                    display_money($this->db->select_sum('total_cost')->get('tbl_estimate_items')->row()->total_cost, $curency->symbol);
                                    ?>
                                </span>
                                <small
                                    class="text-muted m-b block"><?= lang('total') . ' ' . lang('estimate') ?></small>

                            </div>
                        </div>
                    </footer>
                <?php } ?>
            </section>
        </div>
        <?php
        $my_task = $this->admin_model->my_permission('tbl_task', $this->session->userdata('user_id'));
        ?>
        <?php include_once 'assets/admin-ajax.php'; ?>
        <div class="col-md-6" style="margin-top: 20px;">

            <div class="panel panel-custom" style="height: 437px;overflow-y: scroll;">
                <header class="panel-heading mb0">
                    <h3 class="panel-title"><?= lang('my_tasks') ?></h3>
                </header>
                <div class="">
                    <table class="table table-striped m-b-none text-sm">
                        <thead>
                        <tr>
                            <th data-check-all>

                            </th>
                            <th><?= lang('task_name') ?></th>
                            <th><?= lang('end_date') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($my_task)):foreach ($my_task as $v_my_task):


                            if ($v_my_task->task_status == 'not_started' || $v_my_task->task_status == 'in_progress' || $v_my_task->task_progress < 100) {
                                $due_date = $v_my_task->due_date;
                                $due_time = strtotime($due_date);
                                $current_time = time();
                                ?>
                                <tr>
                                    <td class="col-sm-1">
                                        <div class="complete checkbox c-checkbox">
                                            <label>
                                                <input type="checkbox" data-id="<?= $v_my_task->task_id ?>"
                                                       style="position: absolute;" <?php
                                                if ($v_my_task->task_progress >= 100) {
                                                    echo 'checked';
                                                }
                                                ?>>
                                                <span class="fa fa-check"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <a class="text-info"
                                           href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_my_task->task_id ?>">
                                            <?php echo $v_my_task->task_name; ?></a>
                                        <?php if ($current_time > $due_time && $v_my_task->task_progress < 100) { ?>
                                            <span
                                                class="label label-danger pull-right"><?= lang('overdue') ?></span>
                                        <?php } ?>

                                        <div class="progress progress-xs progress-striped active">
                                            <div
                                                class="progress-bar progress-bar-<?php echo ($v_my_task->task_progress >= 100) ? 'success' : 'primary'; ?>"
                                                data-toggle="tooltip"
                                                data-original-title="<?= $v_my_task->task_progress ?>%"
                                                style="width: <?= $v_my_task->task_progress; ?>%"></div>
                                        </div>

                                    </td>

                                    <td>
                                        <?= strftime(config_item('date_format'), strtotime($due_date)) ?>
                                    </td>


                                </tr>
                                <?php
                            }
                        endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div><!-- ./box-body -->

            </div>
        </div>
        <div class="col-md-6" style="margin-top: 20px;">

            <div class="panel panel-custom" style="height: 437px;overflow-y: scroll;">
                <header class="panel-heading mb0">
                    <h3 class="panel-title"><?= lang('announcements') ?></h3>
                </header>

                <?php
                $all_announcements = $this->db->get('tbl_announcements')->result();
                if (!empty($all_announcements)):foreach ($all_announcements as $v_announcements):

                    ?>
                    <div class="notice-calendar-list panel-body">
                        <div class="notice-calendar">
                                    <span
                                        class="month"><?php echo date('M', strtotime($v_announcements->created_date)) ?></span>
                                    <span
                                        class="date"><?php echo date('d', strtotime($v_announcements->created_date)) ?></span>
                        </div>

                        <div class="notice-calendar-heading">
                            <h5 class="notice-calendar-heading-title">
                                <a href="<?php echo base_url() ?>admin/announcements/announcements_details/<?php echo $v_announcements->announcements_id; ?>"
                                   title="View" data-toggle="modal"
                                   data-target="#myModal_lg"><?php echo $v_announcements->title ?></a>
                            </h5>
                            <div class="notice-calendar-date">
                                <?php
                                $str = strlen($v_announcements->description);
                                if ($str > 90) {
                                    $ss = '<strong> ......</strong>';
                                } else {
                                    $ss = '&nbsp';
                                }
                                echo substr($v_announcements->description, 0, 90) . $ss;
                                ?>
                            </div>
                        </div>
                        <div style="margin-top: 5px; padding-top: 5px; padding-bottom: 10px;">
                                        <span style="font-size: 10px;" class="pull-right">
                                            <strong>
                                                <a href="<?php echo base_url() ?>admin/announcements/announcements_details/<?php echo $v_announcements->announcements_id; ?>"
                                                   title="View" data-toggle="modal"
                                                   data-target="#myModal_lg"><?= lang('view_details') ?></a></strong>
                                        </span>
                        </div>
                    </div>
                    <?php

                endforeach; ?>
                <?php endif; ?>

            </div><!-- ./box-body -->

        </div>
    </div>

    <?php if ($this->session->userdata('user_type') == '1') { ?>
        <div class="col-md-6" style="margin-top: 20px;">
            <div class="panel panel-custom">
                <header class="panel-heading">
                    <h3 class="panel-title"><?= lang('payments_report') ?></h3>
                </header>
                <div class="panel-body">
                    <div class="text-center">
                        <form role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard/index/payments"
                              method="post" class="form-horizontal form-groups-bordered">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('year') ?>
                                    <span
                                        class="required">*</span></label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <input type="text" name="yearly" value="<?php
                                        if (!empty($yearly)) {
                                            echo $yearly;
                                        }
                                        ?>" class="form-control years"><span class="input-group-addon"><a
                                                href="#"><i
                                                    class="fa fa-calendar"></i></a></span>
                                    </div>
                                </div>
                                <button type="submit" data-toggle="tooltip" data-placement="top" title="Search"
                                        class="btn btn-custom"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <canvas id="yearly_report" class="col-sm-12"></canvas>
                </div><!-- ./box-body -->
            </div>
        </div>
        <div class="col-md-6" style="margin-top: 20px;">
            <!-- DONUT CHART -->
            <div class="panel panel-custom">
                <header class="panel-heading">
                    <h3 class="panel-title"><?= lang('income_expense') ?></h3>
                </header>
                <div class="panel-body">
                    <p class="text-center">
                    <form role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard/index/month"
                          method="post" class="form-horizontal form-groups-bordered">
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('month') ?><span
                                    class="required">*</span></label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input type="text" name="month" value="<?php
                                    if (!empty($month)) {
                                        echo $month;
                                    }
                                    ?>" class="form-control monthyear"><span class="input-group-addon"><a
                                            href="#"><i
                                                class="fa fa-calendar"></i></a></span>
                                </div>
                            </div>
                            <button type="submit" data-toggle="tooltip" data-placement="top" title="Search"
                                    class="btn btn-custom"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                    </p>
                    <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
        <div class="col-md-6" style="margin-top: 20px;">
            <div class="panel panel-custom">
                <header class="panel-heading">
                    <h3 class="panel-title"><?= lang('income_report') ?></h3>
                </header>
                <div class="panel-body">
                    <p class="text-center">
                    <form role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard/index/Income"
                          method="post" class="form-horizontal form-groups-bordered">
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('year') ?><span
                                    class="required">*</span></label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input type="text" name="Income" value="<?php
                                    if (!empty($Income)) {
                                        echo $Income;
                                    }
                                    ?>" class="form-control years"><span class="input-group-addon"><a href="#"><i
                                                class="fa fa-calendar"></i></a></span>
                                </div>
                            </div>
                            <button type="submit" data-toggle="tooltip" data-placement="top" title="Search"
                                    class="btn btn-custom"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                    </p>
                    <!--End select input year -->
                    <div class="chart-responsive">
                        <!--Sales Chart Canvas -->
                        <canvas id="income" class="col-sm-12"></canvas>
                    </div><!-- /.chart-responsive -->
                </div><!-- ./box-body -->

            </div>
        </div>
        <div class="col-md-6" style="margin-top: 20px;">

            <div class="panel panel-custom">
                <header class="panel-heading">
                    <h3 class="panel-title"><?= lang('expense_report') ?></h3>
                </header>
                <div class="panel-body">
                    <p class="text-center">
                    <form role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard" method="post"
                          class="form-horizontal form-groups-bordered">
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('year') ?><span
                                    class="required">*</span></label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input type="text" name="year" value="<?php
                                    if (!empty($year)) {
                                        echo $year;
                                    }
                                    ?>" class="form-control years"><span class="input-group-addon"><a href="#"><i
                                                class="fa fa-calendar"></i></a></span>
                                </div>
                            </div>
                            <button type="submit" data-toggle="tooltip" data-placement="top" title="Search"
                                    class="btn btn-custom"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                    </p>
                    <!--End select input year -->
                    <div class="chart-responsive">
                        <!--Sales Chart Canvas -->
                        <canvas id="buyers" class="col-sm-12"></canvas>
                    </div><!-- /.chart-responsive -->
                </div><!-- ./box-body -->

            </div>
        </div>

        <div class="wrap-fpanel col-sm-6 " style="margin-top: 20px;">
            <section class="panel panel-custom">
                <header class="panel-heading">
                    <h3 class="panel-title"><?= lang('recently_paid_invoices') ?></h3>
                </header>
                <div class="panel-body inv-slim-scroll">
                    <div class="list-group bg-white">
                        <?php
                        $recently_paid = $this->db
                            ->order_by('created_date', 'desc')
                            ->get('tbl_payments', 5)
                            ->result();
                        if (!empty($recently_paid)) {
                            foreach ($recently_paid as $key => $v_paid) {

                                $invoices_info = $this->db->where(array('invoices_id' => $v_paid->invoices_id))->get('tbl_invoices')->row();

                                $payment_method = $this->db->where(array('payment_methods_id' => $v_paid->payment_method))->get('tbl_payment_methods')->row();

                                $currency = $this->admin_model->client_currency_sambol($invoices_info->client_id);

                                if ($v_paid->payment_method == '1') {
                                    $label = 'success';
                                } elseif ($v_paid->payment_method == '2') {
                                    $label = 'danger';
                                } else {
                                    $label = 'dark';
                                }
                                ?>
                                <a href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_paid->invoices_id ?>"
                                   class="list-group-item">
                                    <?= !empty($invoices_info->reference_no) ? $invoices_info->reference_no : '' ?>
                                    -
                                    <small
                                        class="text-muted"><?= display_money($v_paid->amount, $curency->symbol) ?>
                                        <span
                                            class="label label-<?= $label ?> pull-right"><?= $payment_method->method_name; ?></span>
                                    </small>
                                </a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="panel-footer">
                    <small><?= lang('total_receipts') ?>: <strong>
                            <?php
                            if (!empty($invoce_total)) {
                                if (!empty($invoce_total['paid'])) {
                                    foreach ($invoce_total['paid'] as $v_total) {
                                        $total_paid [] = display_money($v_total, $curency->symbol);
                                    }
                                    echo implode(", ", $total_paid);
                                } else {
                                    echo '0.00';
                                }
                            } else {
                                echo '0.00';
                            }
                            ?>
                        </strong></small>
                </div>
            </section>

        </div>
    <?php } ?>
    <div class="wrap-fpanel col-sm-6 " style="margin-top: 20px;">

        <div class="panel panel-custom">
            <header class="panel-heading">
                <h3 class="panel-title"><?= lang('recent_activities') ?></h3>
            </header>
            <div class="panel-body">
                <section class="comment-list block">
                    <section class="slim-scroll" style="height:400px;overflow-x: scroll">
                        <?php
                        $activities = $this->db
                            ->order_by('activity_date', 'desc')
                            ->get('tbl_activities', 10)
                            ->result();
                        if (!empty($activities)) {
                            foreach ($activities as $v_activities) {
                                $profile_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_account_details')->row();
                                ?>
                                <article id="comment-id-1" class="comment-item" style="font-size: 11px;">
                                    <div class="pull-left recect_task  ">
                                        <a class="pull-left recect_task  ">
                                            <?php if (!empty($profile_info)) {
                                                ?>
                                                <img style="width: 30px;margin-left: 18px;
                                                             height: 29px;
                                                             border: 1px solid #aaa;"
                                                     src="<?= base_url() . $profile_info->avatar ?>"
                                                     class="img-circle">
                                            <?php } ?>
                                        </a>
                                    </div>
                                    <section class="comment-body m-b-lg">
                                        <header class=" ">
                                            <strong>
                                                <?= $profile_info->fullname ?></strong>
                                                    <span class="text-muted text-xs"> <?php
                                                        $today = time();
                                                        $activity_day = strtotime($v_activities->activity_date);
                                                        echo $this->admin_model->get_time_different($today, $activity_day);
                                                        ?> <?= lang('ago') ?>
                                                    </span>
                                        </header>
                                        <div>
                                            <?= lang($v_activities->activity) ?>
                                            <strong> <?= $v_activities->value1 . ' ' . $v_activities->value2 ?></strong>
                                        </div>
                                        <hr/>
                                    </section>
                                </article>


                                <?php
                            }
                        }
                        ?>
                    </section>
            </div>
        </div>
    </div>
</div>


<!-- Morris.js charts -->
<script src="<?php echo base_url() ?>assets/plugins/raphael/raphael.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/morris/morris.js"></script>

<!--Calendar-->

<!-- / Chart.js Script -->
<script src="<?php echo base_url(); ?>asset/js/chart.min.js" type="text/javascript"></script>


<script>
    // line chart data
    var buyerData = {

        labels: [
            <?php
            // yearle result name = month name
            foreach ($all_income as $name => $v_income):
            $month_name = date('F', strtotime($year . '-' . $name)); // get full name of month by date query
            ?>
            "<?php echo $month_name; ?>", // echo the whole month of the year
            <?php endforeach; ?>
        ],
        datasets: [
            {
                fillColor: "rgba(172,194,132,0.4)",
                strokeColor: "#ACC26D",
                pointColor: "#fff",
                pointStrokeColor: "#9DB86D",
                data: [
                    <?php
                    // get monthly result report
                    foreach ($all_income as $v_income):
                    ?>
                    "<?php
                        if (!empty($v_income)) { // if the report result is exist
                            $total_income = 0;
                            foreach ($v_income as $income) {
                                $total_income += $income->amount;
                            }

                            echo $total_income; // view the total report in a  month
                        }
                        ?>",
                    <?php
                    endforeach;
                    ?>
                ]
            }
        ]
    }

    // get line chart canvas
    var buyers = document.getElementById('income').getContext('2d');
    // draw line chart
    new Chart(buyers).Line(buyerData);</script>
<script>
    // line chart data
    var buyerData = {

        labels: [
            <?php
            // yearle result name = month name
            foreach ($all_expense as $name => $v_expense):
            $month_name = date('F', strtotime($year . '-' . $name)); // get full name of month by date query
            ?>
            "<?php echo $month_name; ?>", // echo the whole month of the year
            <?php endforeach; ?>
        ],
        datasets: [
            {
                fillColor: "rgba(172,194,132,0.4)",
                strokeColor: "#ACC26D",
                pointColor: "#fff",
                pointStrokeColor: "#9DB86D",
                data: [
                    <?php
                    // get monthly result report
                    foreach ($all_expense as $v_expense):
                    ?>
                    "<?php
                        if (!empty($v_expense)) { // if the report result is exist
                            $total_expense = 0;
                            foreach ($v_expense as $exoense) {
                                $total_expense += $exoense->amount;
                            }
                            echo $total_expense; // view the total report in a  month
                        }
                        ?>",
                    <?php
                    endforeach;
                    ?>
                ]
            }
        ]
    }

    // get line chart canvas
    var buyers = document.getElementById('buyers').getContext('2d');
    // draw line chart
    new Chart(buyers).Line(buyerData);</script>
<script>
    // line chart data
    var buyerData = {

        labels: [
            <?php
            // yearle result name = month name
            for ($i = 1; $i <= 12; $i++) {
            $month_name = date('F', strtotime($year . '-' . $i)); // get full name of month by date query
            ?>
            "<?php echo $month_name; ?>", // echo the whole month of the year
            <?php }; ?>
        ],
        datasets: [
            {
                fillColor: "rgba(172,194,132,0.4)",
                strokeColor: "#ACC26D",
                pointColor: "#fff",
                pointStrokeColor: "#9DB86D",
                data: [
                    <?php
                    // get monthly result report
                    foreach ($yearly_overview as $v_overview):
                    ?>
                    "<?php
                        echo $v_overview; // view the total report in a  month
                        ?>",
                    <?php
                    endforeach;
                    ?>
                ]
            }
        ]
    }

    // get line chart canvas
    var buyers = document.getElementById('yearly_report').getContext('2d');
    // draw line chart
    new Chart(buyers).Line(buyerData);</script>
<script type="text/javascript">
    $(function () {

        "use strict";
        //DONUT CHART
        var donut = new Morris.Donut({
            element: 'sales-chart',
            resize: true,
            colors: ["#00a65a", "#f56954"],
            data: [
                {
                    label: "<?= lang('Income') ?>", value:
                    <?php
                    $total_vincome = 0;
                    if (!empty($income_expense)):foreach ($income_expense as $v_income_expense):
                    if ($v_income_expense->type == 'Income') {

                    $total_vincome += $v_income_expense->amount;
                    ?>

                    <?php
                    }
                    endforeach;
                    endif;
                    echo $total_vincome;
                    ?>
                },
                {
                    label: "<?= lang('Expense') ?>", value: <?php
                    $total_vexpense = 0;
                    if (!empty($income_expense)):foreach ($income_expense as $v_income_expense):
                    if ($v_income_expense->type == 'Expense') {
                    $total_vexpense += $v_income_expense->amount;
                    ?>

                    <?php
                    }
                    endforeach;
                    endif;
                    echo $total_vexpense;
                    ?>},
            ],
            hideHover: 'auto'
        });
    });
</script>