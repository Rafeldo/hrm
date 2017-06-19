<div class="row">    
    <div class="col-sm-3">
        <div class="panel panel-custom">
            <div class="panel-heading">                  
                <?= lang('all_invoices') ?>
            </div>
            <div class="panel-body">    
                <section class="scrollable  ">
                    <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">                                
                        <ul class="nav"><?php
                            $all_invoices_info = $this->db->where(array('client_id' => $this->session->userdata('client_id')))->get('tbl_invoices')->result();
                            if (!empty($all_invoices_info)) {
                                foreach ($all_invoices_info as $v_invoices) {
                                    if ($this->invoice_model->get_payment_status($v_invoices->invoices_id) == lang('fully_paid')) {
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
                                    <li class="    <?php
                                    if ($v_invoices->invoices_id == $this->uri->segment(3)) {
                                        echo "active";
                                    }
                                    ?>">
                                            <?php
                                            $client_info = $this->invoice_model->check_by(array('client_id' => $v_invoices->client_id), 'tbl_client');
                                            ?>
                                        <a href="<?= base_url() ?>client/invoice/manage_invoice/invoice_details/<?= $v_invoices->invoices_id ?>">
                                            <?= $client_info->name ?>
                                            <div class="pull-right">
                                                <?php $currency = $this->invoice_model->client_currency_sambol($v_invoices->client_id); ?>
                                                <?= display_money($this->invoice_model->get_invoice_cost($v_invoices->invoices_id), $currency->symbol); ?>
                                            </div> <br>
                                            <small class="block small text-muted"><?= $v_invoices->reference_no ?> <span class="label label-<?= $label ?>"><?= $invoice_status ?></span></small>
                                        </a> </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </section>                        
            </div>
        </div>
    </div>        
    <section class="col-sm-9">                
        <div class="row">    

            <!-- Timeline START -->
            <section class="panel panel-custom">
                <div class="panel-body">


                    <div  id="activity">
                        <ul class="list-group no-radius   m-t-n-xxs list-group-lg no-border">
                            <?php
                            $activities_info = $this->db->where(array('module' => 'invoice', 'module_field_id' => $invoice_info->invoices_id))->order_by('activity_date', 'desc')->get('tbl_activities')->result();

                            if (!empty($activities_info)) {
                                foreach ($activities_info as $v_activities) {

                                    $profile_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_account_details')->row();
                                    $user_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_users')->row();
                                    ?>
                                    <li class="list-group-item">
                                        <a class="recect_task pull-left mr-sm">

                                            <?php if (!empty($profile_info)) {
                                                ?>
                                                <img style="width: 30px;margin-left: 18px;
                                                     height: 29px;
                                                     border: 1px solid #aaa;" src="<?= base_url() . $profile_info->avatar ?>" class="img-circle">
                                                 <?php } ?>                                 
                                        </a>


                                        <a  class="clear">
                                            <small class="pull-right"><?= strftime(config_item('date_format') . " %H:%M:%S", strtotime($v_activities->activity_date)) ?></small>
                                            <strong class="block"><?= ucfirst($user_info->username) ?></strong>
                                            <small>
                                                <?php
                                                echo sprintf($v_activities->activity.' <strong style="color:#000"> <em>' . $v_activities->value1 . '</em>'. '<em>' . $v_activities->value2 . '</em></strong>');
                                                ?> 
                                            </small>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>

                        </ul>
                    </div>

                </div>
            </section>        
        </div>
    </section>    
</div>


<!-- end -->