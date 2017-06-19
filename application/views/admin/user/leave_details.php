<div class="panel panel-custom">
    <!-- Default panel contents -->
    <div class="panel-heading">
        <div class="panel-title">
            <strong><?= lang('leave') . ' ' . lang('details_of') . ' ' . $profile_info->fullname ?></strong>
        </div>
    </div>
    <table class="table">
        <tbody>
        <?php
        $all_leave_info = $this->db->get('tbl_leave_category')->result();

        $num_of_leave = 0;
        $total = 0;
        if (!empty($all_leave_info)):foreach ($all_leave_info as $key => $v_leave_info):
            $this->admin_model->_table_name = 'tbl_leave_application';
            $this->admin_model->_order_by = "user_id";
            $total_leave = $this->admin_model->get_by(array('user_id' => $profile_info->user_id, 'leave_category_id' => $v_leave_info->leave_category_id, 'application_status' => '2'), FALSE);
            $total_days = 0;
            if (!empty($total_leave)) {
                $ge_days = 0;
                $m_days = 0;
                foreach ($total_leave as $v_leave) {
                    $month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($v_leave->leave_start_date)), date('Y', strtotime($v_leave->leave_start_date)));

                    $datetime1 = new DateTime($v_leave->leave_start_date);

                    $datetime2 = new DateTime($v_leave->leave_end_date);
                    $difference = $datetime1->diff($datetime2);

                    if ($difference->m != 0) {
                        $m_days += $month;
                    } else {
                        $m_days = 0;
                    }
                    $ge_days += $difference->d + 1;
                    $total_days = $m_days + $ge_days;
                }
            }
            $num_of_leave += $v_leave_info->leave_quota;
            ?>
            <tr>
                <td><strong> <?= $v_leave_info->leave_category ?></strong>:</td>
                <td>
                    <?php
                    if (empty($total_days)) {
                        $total_days = 0;
                    } else {
                        $total_days = $total_days;
                    }
                    $total += $total_days;
                    ?>
                    <?= $total_days ?>/<?= $v_leave_info->leave_quota; ?> </td>
            </tr>
            <?php
        endforeach;
        endif;
        ?>
        <tr>
            <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;">
                <strong> <?= lang('total') ?></strong>:
            </td>
            <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;"> <?= $total; ?>
                /<?= $num_of_leave; ?> </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="panel panel-custom">
    <div class="panel-heading"><?= lang('leave_report') ?></div>
    <div class="panel-body">
        <div id="panelChart5">
            <div class="chart-pie-my flot-chart"></div>
        </div>
    </div>
</div>