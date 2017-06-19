<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Restore_Demo extends MY_Controller
{

    function index()
    {
        $this->load->dbforge();
        $tables = $this->db->list_tables();

        foreach ($tables as $table) {
            $this->dbforge->drop_table($table);
        }
        $db_name = $this->db->database;
        $db_username = $this->db->username;
        $db_password = $this->db->password;
        $db_host = $this->db->hostname;
        $this->create_tables($db_name, $db_username, $db_password, $db_host);
    }

    function create_tables($db_name, $db_username, $db_password, $db_host)
    {
        // Connect to the database
        $mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
        // Check for errors
        if (mysqli_connect_errno())
            return false;

        // Open the default SQL file
        $query = file_get_contents('install/assets/install.sql');
        // Execute a multi query
        $mysqli->multi_query($query);

        // Close the connection
        $mysqli->close();

        return true;
    }


}

