<?php
class Logs_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_current($slug = FALSE)
    {
        if ($slug === FALSE)
        {

            $this->db->select('*');
            $this->db->from('logs');
            $this->db->join('sensors', 'sensors.name = logs.fk_sensor');
            $this->db->order_by('datetime', 'DESC');
            $this->db->order_by('fk_sensor', 'DESC');
            $this->db->limit(2);
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function get_last_day_average()
    {
        $query = $this->db->query('select s.description, avg(l.value) as average from logs l inner join sensors s on (s.name = l.fk_sensor) where datetime > date_sub(now(), interval 24 hour) group by fk_sensor order by fk_sensor');
        return $query->result_array();
    }
}