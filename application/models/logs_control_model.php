<?php
class Logs_control_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function update_last_control_value($type, $param) {
        $this->db->replace('logs_control', 
            array('type' => $type, 'datetime' => date('Y-m-d H:i:s'), 'param' => $param));
        return;
    }

    public function get_last_control_values()
    {
        $this->db->select('*');
        $this->db->from('logs_control');
        $query = $this->db->get();
        return $query->result();
    }
}
