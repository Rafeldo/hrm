
<aside class="aside">
    <!-- START Sidebar (left)-->
    <?php
    $user_id = $this->session->userdata('user_id');
    $profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
    $user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
    ?>
    <div class="aside-inner">
        <nav data-sidebar-anyclick-close="" class="sidebar">
            <!-- START sidebar nav-->
            <ul class="nav">
                <!-- START user info-->
                <li class="has-user-block">
                    <div id="user-block" class="block">
                        <div class="item user-block">
                            <!-- User picture-->
                            <div class="user-block-picture">
                                <div class="user-block-status">
                                    <img src="<?= base_url() . $profile_info->avatar ?>" alt="Avatar" width="60"
                                         height="60"
                                         class="img-thumbnail img-circle">
                                    <div class="circle circle-success circle-lg"></div>
                                </div>
                            </div>
                            <!-- Name and Job-->
                            <div class="user-block-info">
                                <span class="user-block-name"><?= $profile_info->fullname ?></span>
                                <span class="user-block-role"></i> <?= lang('online') ?></span>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="nav">

                <li class="<?php
                if (!empty($page)) {
                    echo $page == lang('dashboard') ? 'active' : '';
                }
                ?>">
                    <a href="<?php echo base_url(); ?>client/dashboard/">
                        <em class="icon-speedometer"></em>
                        <span><?= lang('dashboard') ?></span></a>
                </li>
                <li class="<?php
                if (!empty($page)) {
                    echo $page == lang('mailbox') ? 'active' : '';
                }
                ?>">
                    <a href="<?php echo base_url(); ?>client/mailbox/"> <em
                            class="fa fa-envelope"></em><span><?= lang('mailbox') ?></span></a>
                </li>
                <li class="<?php
                if (!empty($page)) {
                    echo $page == lang('invoice') ? 'active' : '';
                }
                ?>">
                    <a href="<?php echo base_url(); ?>client/invoice/manage_invoice"> <em
                            class="fa fa-shopping-cart"></em><span><?= lang('invoice') ?></span></a>
                </li>
                <li class="<?php
                if (!empty($page)) {
                    echo $page == lang('estimates') ? 'active' : '';
                }
                ?>">
                    <a href="<?php echo base_url(); ?>client/estimates/"> <em
                            class="fa fa-tachometer"></em><span><?= lang('estimates') ?></span></a>
                </li>
                <li class="<?php
                if (!empty($page)) {
                    echo $page == lang('payments') ? 'active' : '';
                }
                ?>">
                    <a href="<?php echo base_url(); ?>client/invoice/all_payments"> <em
                            class="fa fa-money"></em><span><?= lang('payments') ?></span></a>
                </li>

                <li class="sub-menu <?php
                if (!empty($page)) {
                    echo $page == lang('tickets') ? 'active' : '';
                }
                ?>">
                    <a data-toggle="collapse" href="#tickets"> <em
                            class="fa fa-ticket"></em><span><?= lang('tickets') ?></span></a>
                    <ul id="tickets" class="nav sidebar-subnav collapse">
                        <li class="sidebar-subnav-header"><?= lang('tickets') ?></li>
                        <li class="<?= (!empty($sub) && $sub == 1 ? 'active' : ' ') ?>">
                            <a href="<?= base_url() ?>client/tickets/answered"> <em
                                    class="fa fa-circle-o"></em><span><?= lang('answered') ?></span></a>
                        </li>
                        <li class="<?= (!empty($sub) && $sub == 2 ? 'active' : ' ') ?>">
                            <a href="<?= base_url() ?>client/tickets/open"> <em
                                    class="fa fa-circle-o"></em><span><?= lang('open') ?></span></a>
                        </li>
                        <li class="<?= (!empty($sub) && $sub == 3 ? 'active' : ' ') ?>">
                            <a href="<?= base_url() ?>client/tickets/in_progress"> <em
                                    class="fa fa-circle-o"></em><span><?= lang('in_progress') ?></span></a>
                        </li>
                        <li class="<?= (!empty($sub) && $sub == 4 ? 'active' : ' ') ?>">
                            <a href="<?= base_url() ?>client/tickets/closed"> <em
                                    class="fa fa-circle-o"></em><span><?= lang('closed') ?></span></a>
                        </li>
                        <li class="<?= (!empty($sub) && $sub == 5 ? 'active' : ' ') ?>">
                            <a href="<?= base_url() ?>client/tickets"> <em
                                    class="fa fa-ticket"></em><span><?= lang('all_tickets') ?></span></a>
                        </li>
                    </ul>
                </li>


                <li class="<?php
                if (!empty($page)) {
                    echo $page == lang('users') ? 'active' : '';
                }
                ?>">
                    <a href="<?php echo base_url(); ?>client/user/user_list"> <em
                            class="fa fa-users"></em><span><?= lang('users') ?></span></a>
                </li>
                <li class="<?php
                if (!empty($page)) {
                    echo $page == lang('settings') ? 'active' : '';
                }
                ?>">
                    <a href="<?php echo base_url(); ?>client/settings/"> <em
                            class="fa fa-cogs"></em><span><?= lang('settings') ?></span></a>
                </li>
                <li class="<?php
                if (!empty($page)) {
                    echo $page == lang('private_chat') ? 'active' : '';
                }
                ?>">
                    <a href="<?php echo base_url(); ?>client/message/"> <em
                            class="fa fa-envelope"></em><span><?= lang('private_chat') ?></span></a>
                </li>

                <?php
                $online_user = $this->db->where(array('online_status' => '1'))->get('tbl_users')->result();

                if (!empty($online_user)):
                    ?>
                    <li class="content-header"
                        style=";font-weight: bold;color: #fff;font-size: 14px;"><?= lang('online') ?></li>
                    <?php
                    foreach ($online_user as $v_online_user):
                        if ($v_online_user->user_id != $this->session->userdata('user_id')) {
                            if ($v_online_user->role_id == 1) {
                                $user = lang('admin');
                            } elseif ($v_online_user->role_id == 2) {
                                $user = lang('staff');
                            } else {
                                $user = lang('client');
                            }
                            ?>
                            <li class="">
                                <a title="<?php echo $user ?>" data-placement="top" data-toggle="tooltip" class="dker"
                                   href="<?php echo base_url(); ?>client/message/get_chat/<?php echo $v_online_user->user_id ?>">
                                    <?php echo $v_online_user->username ?>
                                    <b class="label label-success pull-right"> <em
                                            class="fa fa-dot-circle-o fa-spin"></em></b>
                                </a>
                            </li>
                            <?php
                        }
                    endforeach;
                    ?>
                <?php endif ?>
            </ul>
        </nav>
    </div>
    <!-- END Sidebar (left)-->
</aside>