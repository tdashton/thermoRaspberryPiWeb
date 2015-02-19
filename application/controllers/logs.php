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

}