<?php
class Logs_model extends CI_Model {

    const NAME_SENSOR_INSIDE = '10-000802b5535b';
    const NAME_SENSOR_OUTSIDE = '10-000802bcf635';

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
            $this->db->where('datetime > date_sub(now(), interval 1 minute)');
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

    public function get_history($start, $end)
    {
        $this->db->select('logs.value / 1000 as value, UNIX_TIMESTAMP(logs.datetime) * 1000 as datetime');
        $this->db->from('logs');
        $this->db->join('sensors', 'sensors.name = logs.fk_sensor');
        if($start && $end) {
            $this->db->where('datetime > ', $start);
            $this->db->where('datetime < ', $end);
        } else {
            $this->db->where('datetime > date_sub(now(), interval 24 hour)');
        }
        $this->db->where('sensors.name', self::NAME_SENSOR_OUTSIDE);
        $this->db->order_by('datetime', 'desc');
        $resReturn = array();

        $dataset = new stdClass();
        $dataset->type = 'line';
        $dataset->xValueType = "dateTime";
        // $dataset->showInLegend = true;
        // $dataset->name = "Draussen";
        $dataset->dataPoints = array();

        $query = $this->db->get();
        $resultArray = $query->result_array();
        $count = count($resultArray);
        for($i = 0; $i < $count; $i++) {
            array_push($dataset->dataPoints, array('x' => (double)$resultArray[$i]['datetime'], 'y' => (float)$resultArray[$i]['value']));
        }
        array_push($resReturn, $dataset);

        $this->db->select('logs.value / 1000 as value, UNIX_TIMESTAMP(logs.datetime) * 1000 as datetime');
        $this->db->from('logs');
        $this->db->join('sensors', 'sensors.name = logs.fk_sensor');
        if($start && $end) {
            $this->db->where('datetime > ', $start);
            $this->db->where('datetime < ', $end);
        } else {
            $this->db->where('datetime > date_sub(now(), interval 24 hour)');
        }
        $this->db->where('sensors.name', self::NAME_SENSOR_INSIDE);
        $this->db->order_by('datetime', 'desc');

        $dataset = new stdClass();
        $dataset->type = 'line';
        $dataset->xValueType = "dateTime";
        // $dataset->showInLegend = true;
        // $dataset->name = 'Drinnen';
        $dataset->dataPoints = array();

        $query = $this->db->get();
        $resultArray = $query->result_array();
        $count = count($resultArray);
        for($i = 0; $i < $count; $i++) {
            array_push($dataset->dataPoints, array('x' => (double)$resultArray[$i]['datetime'], 'y' => (float)$resultArray[$i]['value']));
        }
        array_push($resReturn, $dataset);

        return $resReturn;
    }
}