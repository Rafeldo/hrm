<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}



class MY_Install
{
    public function __construct()
    {
        $CI = &get_instance();
        $CI->load->database();
        if ($CI->db->database == '') {
            header('location:install/');
        } else {

            //query from installer tbl
            $installer = mysql_query('SELECT installer_flag FROM installer');
            $item = mysql_fetch_assoc($installer);
            $flag = $item['installer_flag'];
            // if installer_flag = 0
            if ($flag == 0) {
                // make it 1
                mysql_query('UPDATE installer SET installer_flag=1 WHERE id=1');
                if (is_dir('install')) {
                    header('location:install/success.php');
                }
            }
            //run this code
            //else nothing
        }
    }
}
