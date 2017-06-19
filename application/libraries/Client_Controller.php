<?php

class Client_Controller extends MY_Controller {

    function __construct() {
        parent::__construct();
        $user_flag = $this->session->userdata('user_flag');
        if (!empty($user_flag)) {
            if ($user_flag != 2) {
                $url = $this->session->userdata('url');
                redirect($url);
            }
        } else {
            redirect('locked');
        }
    }

}
