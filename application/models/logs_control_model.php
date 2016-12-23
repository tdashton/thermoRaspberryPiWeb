<?php
class Logs_control_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function log_control_value($type, $param) {
        $this->db->set('datetime', 'now()', false);
        $this->db->insert('logs_control',
            array('type' => $type, 'param' => $param));
        return;
    }

    /**
     * @return array an empty array if nothing was found
     */
    public function get_last_control_values()
    {
        $this->db->select('*');
        $this->db->from('logs_control');
        $this->db->where('datetime > date_sub(now(), interval 60 second)');
        $query = $this->db->get();
        return $query->result();
    }
}
