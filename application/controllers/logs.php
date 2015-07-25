<?php
class Logs extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('logs_model');
    }

    public function index()
    {
        $data['logs'] = $this->logs_model->get_current();
        $data['averages'] = $this->logs_model->get_last_day_average();
        $this->load->view('templates/header', $data);
        $this->load->view('logs/index', $data);
        $this->load->view('templates/footer');
    }    

    public function graph()
    {
        $data = array();

        $this->load->view('templates/header', $data);
        $this->load->view('logs/graph', $data);
        $this->load->view('templates/footer');
    }    

    public function history($type='json')
    {
        $start = $this->input->post('start');
        $end = $this->input->post('end');
        if($start !== false) {
            $start = preg_replace('#/#', '-', $start);
        }
        if($end !== false) {
            $end = preg_replace('#/#', '-', $end);
        }

        if($type='json') {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($this->logs_model->get_history($start, $end)));
        }
    }

}