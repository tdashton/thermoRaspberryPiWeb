<?php
class Logs_model extends CI_Model {

    private $sensors = array();

    public function __construct()
    {
        $this->load->database();
        $this->config->load('thermopi', false, false);
        $this->sensors = $this->config->item('sensors');
    }

    protected function getMapFunction()
    {
        return function ($row) {
            $row['value'] = $row['value'] / 1000;
            if (isset($row['datetime'])) {
                $row['datetime'] = strtotime($row['datetime']) * 1000;
            }
            return $row;
        };
    }

    public function get_current($slug = false)
    {
        if ($slug === false) {
            $this->db->select('value, datetime, fk_sensor, description');
            $this->db->from('logs');
            $this->db->join('sensors', 'sensors.name = logs.fk_sensor');
            $this->db->where('datetime > ', (new \DateTime('-90 seconds'))->format('Y-m-d H:i:s'));
            $this->db->order_by('datetime', 'DESC');
            $this->db->order_by('fk_sensor', 'DESC');
            $this->db->limit(count($this->sensors));
            $query = $this->db->get();

            return array_map(
                $this->getMapFunction(),
                $query->result_array()
            );
        }
    }

    public function get_last_day_average()
    {
        $this->db->select('description, avg(value / 1000) as average');
        $this->db->from('logs');
        $this->db->join('sensors', 'sensors.name = logs.fk_sensor');
        $this->db->where('datetime > ', (new \DateTime('-24 hours'))->format('Y-m-d H:i:s'));
        $this->db->group_by('fk_sensor');
        $this->db->order_by('fk_sensor');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_history($start, $end)
    {

        /**
         * @var $resReturn initialize the return array
         */
        $resReturn = array();
        //,
        // series: [ {
        //   name: 'one',
        //   data: [26.187, 26.25, 26.25, 26.062] 
        // }, {
        //   name: 'two',
        //   data: [24.312, 24.312, 24.375, 24.312]
        // }, {
        //   name: 'three',
        //   data: [24.937, 25, 25, 24.937]
        // }]
        foreach($this->sensors as $key => $value) {
            log_message("debug", $value);

            // get series
            $series = array();
            $series['name'] = $key;
            $series['data'] = array();

            $resultArray = $this->get_logs_for_sensor($value, $start, $end);
            for($i = 0; $i < count($resultArray); $i++) {
                array_push($series['data'], array((double)$resultArray[$i]['datetime'], (float)$resultArray[$i]['value']));
            }
            array_push($resReturn, $series);
        }
        return $resReturn;
    }

    private function get_logs_for_sensor($sensor, $start = null, $end = null)
    {
        if(isset($sensor) === false) {
            log_message('error', 'sensor method parameter was not set, expecting it');
            return array();
        }
        $this->db->select('logs.value, logs.datetime');
        $this->db->from('logs');
        $this->db->join('sensors', 'sensors.name = logs.fk_sensor');
        if($start && $end) {
            $this->db->where('datetime > ', $start);
            $this->db->where('datetime < ', $end);
        } else {
            $this->db->where('datetime > date_sub(now(), interval 24 hour)');
        }
        $this->db->where('sensors.name', $sensor);
        $this->db->order_by('datetime', 'asc');
        $query = $this->db->get();

        return array_map(
            $this->getMapFunction(),
            $query->result_array()
        );
    }
}
