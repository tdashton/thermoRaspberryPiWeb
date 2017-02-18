<?php
class Logs_model extends CI_Model {

    private $sensors = array();

    public function __construct()
    {
        $this->load->database();
        $this->config->load('thermopi', false, false);
        $this->sensors = $this->config->item('sensors');
    }

    public function get_current($slug = FALSE)
    {
        if ($slug === FALSE) {
            $this->db->select('value / 1000 as value, datetime, fk_sensor, description');
            $this->db->from('logs');
            $this->db->join('sensors', 'sensors.name = logs.fk_sensor');
            $this->db->where('datetime > date_sub(now(), interval 90 second)');
            $this->db->order_by('datetime', 'DESC');
            $this->db->order_by('fk_sensor', 'DESC');
            $this->db->limit(count($this->sensors));
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

        /**
         * @var $resReturn initialize the return array
         */
        $resReturn = array();

        foreach($this->sensors as $key => $value) {
            log_message("debug", $value);

            // get datasets
            $dataset = new stdClass();
            $dataset->type = 'line';
            $dataset->xValueType = "dateTime";
            // $dataset->showInLegend = true;
            // $dataset->name = "$key";
            $dataset->dataPoints = array();

            $resultArray = $this->get_logs_for_sensor($value, $start, $end);
            $count = count($resultArray);
            for($i = 0; $i < $count; $i++) {
                array_push($dataset->dataPoints, array('x' => (double)$resultArray[$i]['datetime'], 'y' => (float)$resultArray[$i]['value']));
            }
            array_push($resReturn, $dataset);
        }

        return $resReturn;
    }

    private function get_logs_for_sensor($sensor, $start = null, $end = null)
    {
        if(isset($sensor) === false) {
            log_message('error', 'sensor method parameter was not set, expecting it');
            return array();
        }
        $this->db->select('logs.value / 1000 as value, UNIX_TIMESTAMP(logs.datetime) * 1000 as datetime');
        $this->db->from('logs');
        $this->db->join('sensors', 'sensors.name = logs.fk_sensor');
        if($start && $end) {
            $this->db->where('datetime > ', $start);
            $this->db->where('datetime < ', $end);
        } else {
            $this->db->where('datetime > date_sub(now(), interval 24 hour)');
        }
        $this->db->where('sensors.name', $sensor);
        $this->db->order_by('datetime', 'desc');
        $query = $this->db->get();
        $resultArray = $query->result_array();
        return $resultArray;
    }

}