<style>
    .active{
        background: #C8CAC9;
        color: #000;
    }
</style>
<div class="row">
    <div class="col-sm-3">
        <div class="panel panel-custom">
            <div class="panel-heading">                      
                <?= lang('all_users') ?>
            </div>                        
            <div class="panel-body">        
                <section class="scrollable  ">
                    <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                        <?php
                        $all_user_info = $this->db->where(array('activated' => 1))->get('tbl_users')->result();
                        if (!empty($all_user_info)): foreach ($all_user_info as $v_user) :

                            $account_info = $this->message_model->check_by(array('user_id' => $v_user->user_id), 'tbl_account_details');
                            if (!empty($account_info) && $account_info->user_id != $this->session->userdata('user_id')) {
                                ?>
                                <ul class="nav"><?php
                                    if ($v_user->role_id == 1) {
                                        $user = lang('admin');
                                    } elseif ($v_user->role_id == 3) {
                                        $user = lang('staff');
                                    } else {
                                        $user = lang('client');
                                    }
                                    ?>
                                    <li class="<?php
                                    if ($v_user->user_id == $contactUser->user_id) {
                                        echo "active";
                                    }
                                    ?>">
                                        <a href="<?php echo base_url(); ?>admin/message/get_chat/<?php echo $v_user->user_id ?>">
                                            <?= $account_info->fullname ?>
                                            <small><?= $user ?></small>

                                        </a></li>
                                </ul>
                                <?php
                            };
                        endforeach;
                        endif;
                        ?>
                    </div></section>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <!-- DIRECT CHAT PRIMARY -->
        <div class="panel panel-custom direct-chat direct-chat-primary">
            <div class="panel-heading">
                <?php
                $user_profile = $this->message_model->check_by(array('user_id' => $contactUser->user_id), 'tbl_account_details');
                $my_profile = $this->message_model->check_by(array('user_id' => $this->session->userdata('user_id')), 'tbl_account_details');
                $my_info = $this->message_model->check_by(array('user_id' => $this->session->userdata('user_id')), 'tbl_users');
                if (!empty($user_profile)) {
                    ?>
                    <h3 class="panel-title"><?= $user_profile->fullname ?></h3>
                <?php } ?>
            </div><!-- /.box-header -->
            <div class="panel-body chat" id="container" style="max-height: 450px;overflow-y: scroll">
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages chat-slim-scroll" id="content">
                    <!-- Message. Default to the left -->
                    <?php foreach ($messages as $message):
                        if ($message->receive_user_id == $this->session->userdata('user_id')): ?>
                            <div class="direct-chat-msg">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-left"><?= $contactUser->username ?></span>
                            <span
                                class="direct-chat-timestamp pull-right"><?php echo date('d-m-Y \a\t h:i:A', strtotime($message->message_time)) ?></span>
                                </div>
                                <!-- /.direct-chat-info -->
                                <img class="direct-chat-img" src="<?= base_url() . $user_profile->avatar ?>"
                                     alt="message user image"/><!-- /.direct-chat-img -->
                                <div class="direct-chat-text">
                                    <?php echo $message->message ?>
                                </div>
                                <!-- /.direct-chat-text -->
                            </div><!-- /.direct-chat-msg -->
                        <?php elseif ($message->send_user_id == $this->session->userdata('user_id')): ?>
                            <div class="direct-chat-msg right">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-right"><?= $my_info->username ?></span>
                            <span
                                class="direct-chat-timestamp pull-left"><?php echo date('d-m-Y \a\t h:i:A', strtotime($message->message_time)) ?></span>
                                </div>

                                <img class="direct-chat-img" src="<?= base_url() . $my_profile->avatar ?>"
                                     alt="message user image"/><!-- /.direct-chat-img -->
                                <div class="direct-chat-text">
                                    <?php echo $message->message ?>
                                </div>
                                <!-- /.direct-chat-text -->
                            </div><!-- /.direct-chat-msg -->
                        <?php endif;endforeach;
                    ?>
                </div><!--/.direct-chat-messages-->

            </div>
            <div class="panel-footer">
                <form action="<?= base_url() ?>client/message/send_message" method="post">
                    <div class="input-group">
                        <input type="text" name="message" placeholder="Type Message ..." class="form-control" />
                        <input type="hidden" name="receive_user_id" value="<?= $contactUser->user_id ?>"
                               class="form-control"/>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary btn-flat">Send</button>
                        </span>
                    </div>
                </form>
            </div><!-- /.box-footer-->
        </div><!--/.direct-chat -->
    </div><!-- /.col -->
</div>
<script type="text/javascript">
    function onNewContent() {
        $("#container").scrollTop($("#content").height());
    }
    //test
    $(document).ready(function () {
        onNewContent();
    });
</script>