<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        th {
            padding: 10px 0px 5px 5px;
            text-align: left;
            font-size: 13px;;
        }

        td {
            padding: 5px 0px 0px 5px;
            text-align: left;
            font-size: 13px;
        }

        .notes {
            color: #777;
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
        }
    </style>

</head>
<body style="min-width: 98%; min-height: 100%; overflow: hidden; alignment-adjust: central;">

<?php
$client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');
$client_lang = $client_info->language;

unset($this->lang->is_loaded[5]);
$language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);

$payment_status = $this->invoice_model->get_payment_status($invoice_info->invoices_id);
?>
<br/>
<div style="width: 100%; border-bottom: 2px solid black;">
    <table style="width: 100%; vertical-align: middle;">
        <tr>

            <td style="width: 35px; border: 0px;padding-bottom: 10px;">
                <img style="width: 60px;width: 60px;margin-top: -10px;margin-right: 10px;"
                     src="<?= base_url() . config_item('invoice_logo') ?>">
            </td>
            <td style="border: 0px;">
                <p style="margin-left: 10px; font: 22px lighter;"><?= config_item('company_name') ?></p>
            </td>
            <td style="border: 0px;float: right;">
                <small style="float: right;"><?= $language_info['invoice_date'] ?>
                    : <?= strftime(config_item('date_format'), strtotime($invoice_info->date_saved)); ?></small>
            </td>
        </tr>
    </table>
</div>
<br/>
<div style="width: 100%;">
    <table style="width: 100%; font-family: Arial, Helvetica, sans-serif; border-collapse: collapse;">
        <thead>

        <tr style="width: 100%;margin-top: 15px;">
            <th>
                <strong><?= $language_info['received_from'] ?></strong>
            </th>
            <th>
                <strong><?= $language_info['bill_to'] ?>:</strong>
            </th>
            <th>

            </th>
        </tr>

        </thead>
        <tbody>
        <tr style="width: 100%;">
            <td>
                <address>
                    <strong><?= (config_item('company_legal_name_' . $client_lang) ? config_item('company_legal_name_' . $client_lang) : config_item('company_legal_name')) ?></strong><br/>
                    <?= (config_item('company_address_' . $client_lang) ? config_item('company_address_' . $client_lang) : config_item('company_address')) ?>
                    <br/>
                    <?= (config_item('company_city_' . $client_lang) ? config_item('company_city_' . $client_lang) : config_item('company_city')) ?>
                    <br/>
                    <?= config_item('company_zip_code') ?><br/>
                    <?= (config_item('company_country_' . $client_lang) ? config_item('company_country_' . $client_lang) : config_item('company_country')) ?>
                    <br/>
                    <?= $language_info['phone'] ?> :<?= config_item('company_phone') ?><br/>
                </address>
            </td>
            <td>
                <address>
                    <?php
                    if ($client_info->client_status == 1) {
                        $status = lang('person');
                    } else {
                        $status =lang('company');
                    }
                    ?>
                    <strong><?= ucfirst($client_info->name . ' ' . $status . ' ') ?></strong><br/>
                    <?= ucfirst($client_info->address) ?><br/>
                    <?= ucfirst($client_info->city) ?><br/>
                    <?= ucfirst($client_info->country) ?> <br/>
                    <?= $language_info['phone'] ?>: <a
                        href="tel:<?= ucfirst($client_info->phone) ?>"><?= ucfirst($client_info->phone) ?></a><br/>
                </address>
            </td>
            <td>
                <p><b>Invoice # <?= $invoice_info->reference_no ?></b><br
                <p></p>
                <p><b><?= $language_info['payment_due'] ?>
                        :</b> <?= strftime(config_item('date_format'), strtotime($invoice_info->due_date)); ?></p>
                <?php
                if ($payment_status == lang('fully_paid')) {
                    $label = 'success';
                } else {
                    $label = 'danger';
                }
                ?>
                <p><b><?= $language_info['payment_status'] ?>:</b> <?= $payment_status ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<br/>
<table style="width: 100%; font-family: Arial, Helvetica, sans-serif; border-collapse: collapse;">
    <thead>
    <tr style="width: 100%;margin-top: 15px;">
        <th style="width: 8.33333333%;"><?= $language_info['qty'] ?></th>
        <th style="width: 25%;"><?= $language_info['item_name'] ?></th>
        <th style="width: 25%;;"><?= $language_info['description'] ?></th>
        <th style=""><?= $language_info['tax_rate'] ?> </th>
        <th style=""><?= $language_info['unit_price'] ?></th>
        <th style="width: 8.33333333%;"><?= $language_info['tax'] ?></th>
        <th style=""><?= $language_info['total'] ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $invoice_items = $this->invoice_model->ordered_items_by_id($invoice_info->invoices_id);

    if (!empty($invoice_items)) :
        foreach ($invoice_items as $v_item) :
            $item_name = $v_item->item_name ? $v_item->item_name : $v_item->item_desc;
            ?>
            <tr>
                <td><?= $v_item->quantity ?></td>
                <td><?= $item_name ?></td>
                <td><?= nl2br($v_item->item_desc) ?></td>
                <td><?= $v_item->item_tax_rate ?>%</td>
                <td><?= display_money($v_item->unit_cost) ?></td>
                <td><?= display_money($v_item->item_tax_total) ?></td>
                <td><?= display_money($v_item->total_cost) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif ?>
    <tr>
        <td colspan="4"><p class="notes" style="margin-top: 10px;">
                <?= $invoice_info->notes ?>
            </p></td>
        <td colspan="2">
            <table class="table1" style="width: 100%;border:0px">
                <tr>
                    <th style="border:0px;text-align: right"><?= $language_info['sub_total'] ?> :</th>
                    <td style="border:0px"> <?= display_money($this->invoice_model->calculate_to('invoice_cost', $invoice_info->invoices_id)) ?></td>
                </tr>
                <?php if ($invoice_info->tax > 0.00): ?>
                    <tr>
                        <td style="text-align: right">
                            <strong><?= $language_info['tax'] ?> : <?php echo $invoice_info->tax; ?>%</strong></td>
                        <td><?= display_money($this->invoice_model->calculate_to('tax', $invoice_info->invoices_id)) ?> </td>
                    </tr>
                <?php endif ?>
                <?php if ($invoice_info->discount > 0) { ?>
                    <tr>
                        <td style="text-align: right">
                            <strong><?= $language_info['discount'] ?> : - <?php echo $invoice_info->discount; ?>
                                %</strong></td>
                        <td><?= display_money($this->invoice_model->calculate_to('discount', $invoice_info->invoices_id)) ?> </td>
                    </tr>
                    <?php
                }
                $paid_amount = display_money($this->invoice_model->calculate_to('paid_amount', $invoice_info->invoices_id));
                if ($paid_amount > 0.00) {
                    ?>
                    <tr>
                        <td style="text-align: right"><strong><?= $language_info['payment_made'] ?></strong></td>
                        <td><?= $paid_amount ?> </td>
                    </tr>
                    <?php
                }
                $currency = $this->invoice_model->client_currency_sambol($invoice_info->client_id);
                ?>
                <tr>
                    <td style="text-align: right"><strong><?= $language_info['total'] ?> :</strong></td>
                    <td><?= display_money($this->invoice_model->calculate_to('invoice_due', $invoice_info->invoices_id), $currency->symbol); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>

</table>

</body>
</html>
