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
        log_message('info', 'The purpose of some variable is to provide some value.');
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