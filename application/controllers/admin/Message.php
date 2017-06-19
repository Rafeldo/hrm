<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admistrator
 *
 * @author pc mart ltd
 */
class Message extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('message_model');
    }

    public function index()
    {
        $data = $this->full_conversation();

        $data['title'] = lang('private_chat');
        $data['page'] = 'message';

        $data['subview'] = $this->load->view('admin/chat', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function get_chat()
    {
        $data = $this->full_conversation($this->uri->segment(4));
        $data['title'] = config_item('company_name');
        $data['page'] = 'message';
        $data['subview'] = $this->load->view('admin/chat', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    function full_conversation($contactUser = null)
    {
        $converMess = $this->db->where('send_user_id', $this->session->userdata('user_id'))
            ->or_where('receive_user_id', $this->session->userdata('user_id'))
            ->group_by('send_user_id')
            ->group_by('receive_user_id')
            ->order_by('message_time', 'asc')
            ->get('tbl_private_message_send')->result();
        if (!empty($converMess)) {
            $converUsers = [];
            $uniqueUsers = [];
            // conversation messages
            foreach ($converMess as $mess) {
                if (!in_array($mess->send_user_id, $uniqueUsers) && ($this->session->userdata('user_id') != $mess->send_user_id)) {
                    $uniqueUsers[] = $mess->send_user_id;
                    $converUsers[] = $this->message_model->check_by(array('user_id' => $mess->send_user_id), 'tbl_users');
                }
                if (!in_array($mess->receive_user_id, $uniqueUsers) && ($this->session->userdata('user_id') != $mess->receive_user_id)) {
                    $uniqueUsers[] = $mess->receive_user_id;
                    $converUsers[] = $this->message_model->check_by(array('user_id' => $mess->receive_user_id), 'tbl_users');
                }
            }

            $messages = [];
            // check conversation users exists or not
            if (count($converUsers) > 0) {
                if ($contactUser) {
                    $se_re_id = [$this->session->userdata('user_id'), $contactUser];
                    $data['contactUser'] = $this->message_model->check_by(array('user_id' => $contactUser), 'tbl_users');;
                } else {
                    $se_re_id = [$this->session->userdata('user_id'), $converUsers[0]->user_id];
                    $data['contactUser'] = $converUsers[0];
                }
                $messages = $this->db->where_in('send_user_id', $se_re_id)
                    ->where_in('receive_user_id', $se_re_id)
                    ->order_by('message_time', 'asc')
                    ->get('tbl_private_message_send')->result();
            } else {
                return 0;
            }
            $data['messages'] = $messages;
            $data['converUsers'] = $converUsers;

        } else {
            $data['messages'] = array();
            $data['converUsers'] = array();
            if (!empty($contactUser)) {
                $where = array('user_id' => $contactUser);
                $user = $this->db->where($where)->get('tbl_users')->result();
            } else {
                $user = $this->db->get('tbl_users')->result();
            }
            $data['contactUser'] = $user[0];
        }
        return $data;
    }

    public function send_message()
    {
        $data = $this->message_model->array_from_post(array('message', 'receive_user_id'));
        $data['send_user_id'] = $this->session->userdata('user_id');

        if (!empty($data['receive_user_id']) && $data['send_user_id'] != $data['receive_user_id']) {
            $this->message_model->_table_name = 'tbl_private_message_send';
            $this->message_model->_primary_key = 'private_message_send_id';
            $this->message_model->save($data);
        }
        redirect('admin/message/get_chat/' . $data['receive_user_id']);
    }

}
