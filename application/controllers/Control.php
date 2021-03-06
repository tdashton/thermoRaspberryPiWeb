<?php

class Control extends CI_Controller {

    const CONTROL_CMD_TEMP = 'CMD TEMP';
    const CONTROL_CMD_TIME = 'CMD TIME';
    const CONTROL_CMD_STATUS = 'CMD STATUS';

    public function __construct()
    {
        parent::__construct();
        $this->config->load('thermopi', false, false);
        $this->load->driver('cache', array('adapter' => 'file', 'cache_path' => '/tmp/'));
        $this->load->helper('control');
        $this->load->model('control_logs_model');
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

    protected function getCacheKey()
    {
        return 'thermoPiCache-' . md5(self::CONTROL_CMD_STATUS);
    }

    public function read()
    {
        $data = $this->cache->get($this->getCacheKey());
        $src = 'cache';
        if(!$data) {
            $data = $this->getCurrentControlValues();
            $this->cache->save($this->getCacheKey(), $data, 60);
            $src = 'controller';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('source' => $src, 'result' => $data)));
    }

    public function nonce()
    {
        session_start();
        $this->setSessionNonce(rand(0, getrandmax()));
        $nonce = $this->getSessionNonce();
        log_message('debug', 'nonce generated');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array("nonce" => (string)$nonce)));
    }

    public function command()
    {
        session_start();
        $this->config->load('thermopi', false, false);

        $error = array();

        $nonce = $this->getSessionNonce();
        $sharedSecret = $this->config->item("api_shared_secret");
        $cmd = $this->input->post('cmd');
        $param = $this->input->post('param');
        $signature = $this->input->post('signature');

        // log_message('debug', $cmd . $param . $nonce);
        $hash = hash_hmac("sha1", $cmd . $param . $nonce, $sharedSecret);
        log_message('debug', "client: $signature vs. server: $hash");

        $result = array(
            "result" => null,
            "error" => 
                array(
                    "code" => 100,
                    "text" => "missing params"
                )
            );
        if($nonce == false) {
            $result['error'] = array(
                    "code" => 102,
                    "text" => "no nonce generated"
                );
        } elseif($signature != $hash) {
            $result['error'] = array(
                    "code" => 101,
                    "text" => "bad signature"
                );
        } else {
            log_message('debug', 'checking for params');
            if($cmd != null && $param != null) {
                $host = $this->config->item("thermo_control_host");
                $port = $this->config->item("thermo_control_port");
                $result = send_command($cmd, $param, $host, $port);
                $this->cache->delete($this->getCacheKey());
                unset($_SESSION['nonce']);
                if (in_array($cmd, array(self::CONTROL_CMD_TEMP, self::CONTROL_CMD_TIME))) {
                    $this->control_logs_model->insert_control_value($cmd, $param);
                }
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    /**
     * returns the most frequently used settings per type
     */
    public function logs($type = null)
    {
        if (!is_null($type)) {
            $types = [urldecode($type)];
        } else {
            $types = ['CMD TIME', 'CMD TEMP'];
        }
        $result = [];
        foreach($types as $type) {
            $result = array_merge(
                $result,
                array_filter(
                    $this->control_logs_model->get_recent_values($type),
                    function ($element) {
                        return $element->countx > 2;
                    }
                )
            );
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['result' => $result]));
    }

    /**
     * reads control values from the attached contoller
     */
    private function getCurrentControlValues()
    {
        $this->config->load('thermopi', false, false);
        $host = $this->config->item("thermo_control_host");
        $port = $this->config->item("thermo_control_port");
        $ret = send_command('CMD STATUS', NULL, $host, $port);

        $currentValues = explode("\n", trim($ret));

        array_walk($currentValues, function(&$value) {
            list($key, $value) = explode(':', $value);
            $value = array(
                'type' => "CMD $key",
                'param' => $value,
                'datetime' => date('Y-m-d H:i:s'),
                );
            });
        return $currentValues;
    }

    /**
     * check for a nonce in the session, if available return it if not return false.
     */
    private function getSessionNonce() {
        if(isset($_SESSION['nonce']) == false) {
            return false;
        }
        return $_SESSION['nonce'];
    }

    /**
     * sets the nonce in the session if it has not already been set.
     */
    private function setSessionNonce($param) {
        if(isset($_SESSION['nonce']) == false) {
            $_SESSION['nonce'] = $param;
        }
    }
}
