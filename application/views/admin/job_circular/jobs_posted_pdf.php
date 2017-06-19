<!DOCTYPE html>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>

<body style="width: 100%;">
<br/>
<div style="width: 100%; border-bottom: 2px solid black;">
    <table style="width: 100%; vertical-align: middle;">
        <tr>
            <td style="width: 50px; border: 0px;">
                <img style="width: 50px;height: 50px;margin-bottom: 5px;"
                     src="<?= base_url() . config_item('company_logo') ?>" alt="" class="img-circle"/>
            </td>

            <td style="border: 0px;">
                <p style="margin-left: 10px; font: 14px lighter;"><?= config_item('company_name') ?></p>
            </td>
        </tr>
    </table>
</div>
<br/>
<div style="width: 100%;">
    <div>
        <div style="width: 100%; background: #E3E3E3;padding: 1px 0px 1px 10px; color: black; vertical-align: middle; ">
            <p style="margin-left: 10px; font-size: 15px; font-weight: lighter;">
                <strong><?= lang('view_circular_details') ?></strong></p>
        </div>

        <table style="width: 100%; font-size: 13px;margin-top: 20px">
            <tr>
                <td style="width: 30%;text-align: right"><strong><?= lang('job_title') ?> :</strong>
                </td>

                <td style="">&nbsp; <?php
                    echo $job_posted->job_title;
                    ?></td>
            </tr>
            <tr>
                <td style="width: 30%;text-align: right"><strong><?= lang('designation') ?> :</strong></td>

                <td style="">&nbsp; <?php
                    $design_info = $this->db->where('designations_id', $job_posted->designations_id)->get('tbl_designations')->row();
                    echo $design_info->designations;
                    ?></td>
            </tr>
            <tr>
                <td style="width: 30%;text-align: right"><strong><?= lang('vacancy_no') ?> :</strong>
                </td>

                <td style="">&nbsp; <?php
                    echo $job_posted->vacancy_no;
                    ?></td>
            </tr>
            <tr>
                <td style="width: 30%;text-align: right"><strong><?= lang('posted_date') ?> :</strong></td>

                <td style="">&nbsp; <?php
                    echo strftime(config_item('date_format'), strtotime($job_posted->posted_date));
                    ?></td>
            </tr>
            <tr>
                <td style="width: 30%;text-align: right"><strong><?= lang('last_date_to_apply') ?> :</strong>
                </td>

                <td style="">&nbsp; <?php
                    echo strftime(config_item('date_format'), strtotime($job_posted->last_date));
                    ?></td>
            </tr>
            <tr>
                <td style="width: 30%;text-align: right"><strong><?= lang('status') ?> :</strong>
                </td>

                <td style="">&nbsp; <?php
                    if ($job_posted->status == 'unpublished') : ?>
                        <span class="label label-danger"><?= lang('unpublished') ?></span>
                    <?php else : ?>
                        <span class="label label-success"><?= lang('published') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <span style="word-wrap: break-word;"><?php echo $job_posted->description; ?></span>
            </tr>

        </table>

    </div>
</div><!-- ***************** Salary Details  Ends *********************-->

</body>
</html>