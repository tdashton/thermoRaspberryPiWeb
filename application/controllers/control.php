<?php

class Control extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('control');
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/logs
     *  - or -
     *      http://example.com/index.php/logs/index
     *  - or -
     */
    public function index()
    {
        log_message('debug', 'Redirecting to index');
        redirect(base_url(), 'location', 301);
    }

    public function command() {
        $this->config->load('thermo_control', false, false);

        $error = array();

        log_message('debug', 'Redirecting to index');
        $cmd = $this->input->post('cmd');
        $param = $this->input->post('param');
        log_message('debug', 'checking for params');
        $result = array(
            "result" => null,
            "error" => 
                array(
                    "code" => 100,
                    "text" => "missing params"
                )
            );
        if($cmd != null && $param != null) {
            $host = $this->config->item("thermo_control_host");
            $port = $this->config->item("thermo_control_port");
            $result = notify_controller($cmd, $param, $host, $port);

        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }
}
