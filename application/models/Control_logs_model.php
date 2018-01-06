<?php
class Control_logs_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    /**
     *
     *
     */
    public function insert_control_value($type, $param) {
        $this->db->set('datetime', 'now()', false);
        $this->db->replace('control_logs', 
            array('type' => $type, 'param' => $param));
        return;
    }

    /**
     *
     *
     */
    public function log_control_value($type, $param) {
        $this->db->set('datetime', 'now()', false);
        $this->db->insert('control_logs',
            array('type' => $type, 'param' => $param));
        return;
    }

    public function get_recent_values($type)
    {
        $this->db->select('type, param, count(*) as countx');
        $this->db->from('control_logs');
        $this->db->where('type = ', $type);
        $this->db->group_by('param');
        $this->db->order_by('countx desc');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * @return array an empty array if nothing was found
     */
    public function get_last_control_values()
    {
        $this->db->select('*');
        $this->db->from('control_logs');
        $this->db->where('datetime > date_sub(now(), interval 60 second)');
        $query = $this->db->get();
        return $query->result();
    }
}
