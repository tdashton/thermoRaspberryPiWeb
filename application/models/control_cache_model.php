<?php
class Control_cache_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function update_last_control_value($type, $param) {
        $this->db->set('datetime', 'now()', false);
        $this->db->replace('control_cache', 
            array('type' => $type, 'param' => $param));
        return;
    }

    /**
     * @return array an empty array if nothing was found
     */
    public function get_last_control_values()
    {
        $this->db->select('*');
        $this->db->from('control_cache');
        $this->db->where('datetime > date_sub(now(), interval 60 second)');
        $query = $this->db->get();
        return $query->result();
    }
}
