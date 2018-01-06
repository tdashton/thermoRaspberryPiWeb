<?php
class Logs extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('logs_model');
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/logs
     *  - or -
     *      http://example.com/index.php/logs/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/logs/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $this->config->load('thermopi');
        $data['logs'] = $this->logs_model->get_current();
        $data['averages'] = $this->logs_model->get_last_day_average();
        $data['locationName'] = $this->getLocationName();

        $this->load->view('templates/header', $data);
        $this->load->view('logs/index', $data);
        $this->load->view('templates/footer');
    }

    public function graph()
    {
        $this->config->load('thermopi');
        $data = array(
            'startTimestampMillis' => time() * 1000,
            'xAxisTitleText' => 'Grad',
            'locationName' => $this->getLocationName(),
        );

        $this->load->view('templates/header', $data);
        $this->load->view('logs/graph', $data);
        $this->load->view('templates/footer');
    }    

    public function history($outputFormat = 'json', $timePeriod = 'post')
    {
        $current = false;
        if($timePeriod == 'post') {
            $start = $this->input->get('start');
            $end = $this->input->get('end');
            if($start !== false) {
                $start = strftime('%Y/%m/%d %H:%M', $start / 1000);
            }
            if($end !== false) {
                $end = strftime('%Y/%m/%d %H:%M', $end / 1000);
            }
        } else {
            $current = true;
        }

        if($outputFormat == 'json') {
            $data = new StdClass();
            if($current) {
                $data->data = $this->logs_model->get_current();
            } else {
                $data->data = $this->logs_model->get_history($start, $end);
                
            }
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
    }

    private function getLocationName()
    {
        $location_name = $this->input->get('location_name');
        log_message("debug", $location_name);

        if(empty($location_name) === true) {
            $location_name = $this->config->item('location_name');
        }
        return $location_name;
    }
}
