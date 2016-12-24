<?php
class Control_logs_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function insert_control_value($type, $param) {
        $this->db->set('datetime', 'now()', false);
        $this->db->replace('control_logs', 
            array('type' => $type, 'param' => $param));
        return;
    }
}
