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

    public function nonce()
    {
        $nonce = $this->session->userdata('session_id');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array("nonce" => $nonce)));
    }

    public function command() {
        $this->config->load('thermo_control', false, false);

        $error = array();

        $nonce = $this->session->userdata('session_id');
        $sharedSecret = $this->config->item("api_shared_secret");
        $cmd = $this->input->post('cmd');
        $param = $this->input->post('param');
        $signature = $this->input->post('signature');

        // log_message('debug', $cmd . $param . $sharedSecret . $nonce);
        $hash = md5($cmd . $param . $sharedSecret . $nonce);
        log_message('debug', "client: $signature vs. server: $hash");

        $result = array(
            "result" => null,
            "error" => 
                array(
                    "code" => 100,
                    "text" => "missing params"
                )
            );
        if($signature != $hash) {
            $result['error'] = array(
                    "code" => 101,
                    "text" => "bad signature"
                );
        } else {
            log_message('debug', 'checking for params');
            if($cmd != null && $param != null) {
                $host = $this->config->item("thermo_control_host");
                $port = $this->config->item("thermo_control_port");
                $result = notify_controller($cmd, $param, $host, $port);
            }
        }


        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }
}
