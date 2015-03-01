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

            $this->db->select('value / 1000 as value, datetime, fk_sensor, description');
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
        $query = $this->db->query('select s.description, avg(l.value / 1000) as average from logs l inner join sensors s 
            on (s.name = l.fk_sensor) where datetime > date_sub(now(), interval 24 hour) group by fk_sensor order by fk_sensor');
        return $query->result_array();
    }

    public function get_last_day()
    {
        $query = $this->db->query('select l.value / 1000 as value, datetime from logs l inner join sensors s 
            on (s.name = l.fk_sensor) where datetime > date_sub(now(), interval 24 hour) and s.description= \'draussen\' 
            order by datetime desc');
        $resReturn = array();

        $dataset = new stdClass();
        $dataset->type = 'line';
        $dataset->dataPoints = array();
        $resultArray = $query->result_array();
        $count = count($resultArray);
        for($i = 0; $i < $count; $i++) {
            $dateTimeConvert = new DateTime($resultArray[$i]['datetime']);
            array_push($dataset->dataPoints, array('x' => $dateTimeConvert->format(DateTime::ATOM), 'y' => (float)$resultArray[$i]['value']));
        }
        array_push($resReturn, $dataset);

        $query = $this->db->query('select l.value / 1000 as value, datetime from logs l inner join sensors s 
            on (s.name = l.fk_sensor) where datetime > date_sub(now(), interval 24 hour) and s.description= \'drinnen\' 
            order by datetime desc');
        $dataset = new stdClass();
        $dataset->type = 'line';
        $dataset->dataPoints = array();
        $resultArray = $query->result_array();
        $count = count($resultArray);
        for($i = 0; $i < $count; $i++) {
            $dateTimeConvert = new DateTime($resultArray[$i]['datetime']);
            array_push($dataset->dataPoints, array('x' => $dateTimeConvert->format(DateTime::ATOM), 'y' => (float)$resultArray[$i]['value']));
        }
        array_push($resReturn, $dataset);

        return $resReturn;
    }
}