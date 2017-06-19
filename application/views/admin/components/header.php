<?php
$user_id = $this->session->userdata('user_id');
$profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
$user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
?>
<header class="topnavbar-wrapper">
    <!-- START Top Navbar-->
    <nav role="navigation" class="navbar topnavbar">
        <!-- START navbar header-->
        <?php $display = config_item('logo_or_icon'); ?>
        <div class="navbar-header">
            <?php if ($display == 'logo' || $display == 'logo_title') { ?>
                <a href="#/" class="navbar-brand">
                    <div class="brand-logo">
                        <img style="width: 100px;max-height: 42px;"
                             src="<?= base_url() . config_item('company_logo') ?>" alt="App Logo"
                             class="img-responsive">
                    </div>
                    <div class="brand-logo-collapsed">
                        <img style="width: 48px;height: 48px;border-radius: 50px"
                             src="<?= base_url() . config_item('company_logo') ?>" alt="App Logo"
                             class="img-responsive">
                    </div>
                </a>
            <?php }
            ?>
        </div>
        <!-- END navbar header-->
        <!-- START Nav wrapper-->
        <div class="nav-wrapper">
            <!-- START Left navbar-->
            <ul class="nav navbar-nav">
                <li>
                    <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops-->
                    <a href="#" data-toggle-state="aside-collapsed" class="hidden-xs">
                        <em class="fa fa-navicon"></em>
                    </a>
                    <!-- Button to show/hide the sidebar on mobile. Visible on mobile only.-->
                    <a href="#" data-toggle-state="aside-toggled" data-no-persist="true"
                       class="visible-xs sidebar-toggle">
                        <em class="fa fa-navicon"></em>
                    </a>
                </li>
                <!-- END User avatar toggle-->
                <!-- START lock screen-->
                <li class="hidden-xs">
                    <a href="" class="text-center" style="vertical-align: middle;color: #FFFFFF;font-size: 20px;"><?php
                        if ($display == 'logo_title' || $display == 'icon_title') {
                            if (config_item('website_name') == '') {
                                echo config_item('company_name');
                            } else {
                                echo config_item('website_name');
                            }
                        }
                        ?></a>
                </li>
                <!-- END lock screen-->
            </ul>
            <!-- END Left navbar-->
            <!-- START Right Navbar-->
            <ul class="nav navbar-nav navbar-right">
                <?php if (config_item('enable_languages') == 'TRUE') { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag"></i> <?= lang('languages') ?>
                        </a>
                        <ul class="dropdown-menu animated zoomIn">

                            <?php
                            $languages = $this->db->order_by('name', 'ASC')->get('tbl_languages')->result();

                            foreach ($languages as $lang) : if ($lang->active == 1) :
                                ?>
                                <li>
                                    <a href="<?= base_url() ?>admin/dashboard/set_language/<?= $lang->name ?>"
                                       title="<?= ucwords(str_replace("_", " ", $lang->name)) ?>">
                                        <img src="<?= base_url() ?>asset/images/flags/<?= $lang->icon ?>.gif"
                                             alt="<?= ucwords(str_replace("_", " ", $lang->name)) ?>"/> <?= ucwords(str_replace("_", " ", $lang->name)) ?>
                                    </a>
                                </li>
                                <?php
                            endif;
                            endforeach;
                            ?>

                        </ul>
                    </li>
                <?php } ?>
                <!-- Fullscreen (only desktops)-->
                <li class="visible-lg">
                    <a href="#" data-toggle-fullscreen="">
                        <em class="fa fa-expand"></em>
                    </a>
                </li>
                <?php
                // check notififation status by where
                $where = array('user_id' => $this->session->userdata('user_id'), 'to' => $this->session->userdata('email'), 'notify_me' => '1', 'view_status' => '2');
                // check email notification status
                $this->admin_model->_table_name = 'tbl_inbox';
                $this->admin_model->_order_by = 'inbox_id';
                $total_email = count($this->admin_model->get_by($where, FALSE));
                $email_info = $this->admin_model->get_by($where, FALSE);
                ?>
                <!-- START Alert menu-->
                <li class="dropdown dropdown-list">
                    <a href="#" data-toggle="dropdown">
                        <em class="icon-bell"></em>
                        <div class="label label-danger"><?php
                            if (!empty($total_email)) {
                                echo $total_email;
                            } else {
                                echo '0';
                            }
                            ?></div>
                    </a>
                    <!-- START Dropdown menu-->
                    <ul class="dropdown-menu animated zoomIn">
                        <li>
                            <!-- START list group-->
                            <div class="list-group">
                                <?php if (!empty($email_info)):foreach ($email_info as $v_email): ?>
                                    <!-- list item-->
                                    <a href="<?php echo base_url() ?>admin/mailbox/index/read_inbox_mail/<?php echo $v_email->inbox_id ?>"
                                       class="list-group-item">
                                        <div class="media-box">
                                            <div class="pull-left">
                                                <em class="fa fa-envelope-o fa-2x text-info"></em>
                                            </div>
                                            <div class="media-box-body clearfix">
                                                <p style="color: #555555;"
                                                   class="m0"><?= (strlen($v_email->subject) > 25) ? substr($v_email->subject, 0, 25) . '...' : $v_email->subject; ?></p>
                                                <p class="m0 text-muted">
                                                    <small><i class="fa fa-clock-o"></i>
                                                        <?php
                                                        //$oldTime = date('h:i:s', strtotime($v_inbox_msg->send_time));
                                                        // Past time as MySQL DATETIME value
                                                        $oldtime = date('Y-m-d H:i:s', strtotime($v_email->message_time));

                                                        // Current time as MySQL DATETIME value
                                                        $csqltime = date('Y-m-d H:i:s');
                                                        // Current time as Unix timestamp
                                                        $ptime = strtotime($oldtime);
                                                        $ctime = strtotime($csqltime);

                                                        //Now calc the difference between the two
                                                        $timeDiff = floor(abs($ctime - $ptime) / 60);

                                                        //Now we need find out whether or not the time difference needs to be in
                                                        //minutes, hours, or days
                                                        if ($timeDiff < 2) {
                                                            $timeDiff = "Just now";
                                                        } elseif ($timeDiff > 2 && $timeDiff < 60) {
                                                            $timeDiff = floor(abs($timeDiff)) . " minutes ago";
                                                        } elseif ($timeDiff > 60 && $timeDiff < 120) {
                                                            $timeDiff = floor(abs($timeDiff / 60)) . " hour ago";
                                                        } elseif ($timeDiff < 1440) {
                                                            $timeDiff = floor(abs($timeDiff / 60)) . " hours ago";
                                                        } elseif ($timeDiff > 1440 && $timeDiff < 2880) {
                                                            $timeDiff = floor(abs($timeDiff / 1440)) . " day ago";
                                                        } elseif ($timeDiff > 2880) {
                                                            $timeDiff = floor(abs($timeDiff / 1440)) . " days ago";
                                                        }
                                                        echo $timeDiff;
                                                        ?>
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                                <?php endif; ?>

                                <!-- last list item -->
                                <a href="#" class="list-group-item">
                                    <small
                                        style="color: #555555;"> <?= lang('you_have') ?>  <?= lang('messages') ?></small>
                                    <span class="label label-danger pull-right"><?php
                                        if (!empty($total_email)) {
                                            echo $total_email;
                                        } else {
                                            echo '0';
                                        }
                                        ?></span>
                                </a>
                            </div>
                            <!-- END list group-->
                        </li>
                    </ul>
                    <!-- END Dropdown menu-->
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <img src="<?= base_url() . $profile_info->avatar ?>" class="img-xs user-image"
                             alt="User Image"/>
                        <span class="hidden-xs"><?= $profile_info->fullname ?></span>
                    </a>
                    <ul class="dropdown-menu animated zoomIn">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= base_url() . $profile_info->avatar ?>" class="img-circle" alt="User Image"/>
                            <p>
                                <?= $profile_info->fullname ?>
                                <small><?= lang('last_login') . ':' ?>
                                    <?php
                                    if ($user_info->last_login == '0000-00-00 00:00:00') {
                                        $login_time = "-";
                                    } else {
                                        $login_time = strftime(config_item('date_format') . " %H:%M:%S", strtotime($user_info->last_login));
                                    }
                                    echo $login_time;
                                    ?>
                                </small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="<?= base_url() ?>admin/settings/activities"><?= lang('activities') ?></a>
                            </div>
                            <div class="col-xs-4 text-center">

                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="<?= base_url() ?>locked/lock_screen"><?= lang('lock_screen') ?></a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?= base_url() ?>admin/settings/update_profile"
                                   class="btn btn-default btn-flat"><?= lang('update_profile') ?></a>
                            </div>
                            <form method="post" action="<?= base_url() ?>login/logout"
                                  class="form-horizontal">

                                <input type="hidden" name="clock_time" value="" id="time">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-default btn-flat"><?= lang('logout') ?></button>
                                </div>
                            </form>
                        </li>
                    </ul>
                </li>
                <!-- END Alert menu-->
                <!-- START Offsidebar button-->
                <li>
                    <a href="#" data-toggle-state="offsidebar-open" data-no-persist="true">
                        <em class="icon-notebook"></em>
                        <small class="label label-danger" style="top: 11%;position: absolute;right: 5%;}"><?php
                            $user = $this->session->userdata('user_id');
                            $this->db->where('user_id', $user);
                            $this->db->where('status', 0);
                            $query = $this->db->get('tbl_todo');

                            $incomplete_todo_number = $query->num_rows();
                            if ($incomplete_todo_number > 0) {
                                echo $incomplete_todo_number;
                            }
                            ?></small>
                    </a>
                </li>
                <!-- END Offsidebar menu-->
            </ul>
            <!-- END Right Navbar-->
        </div>
        <!-- END Nav wrapper-->
    </nav>
    <!-- END Top Navbar-->
</header>