<?php

$socket_error = false;

function send_command($cmd, $param, $host, $port) {
    global $socket_error;
    set_error_handler("myErrorHandler");

    /* Create a TCP/IP socket. */
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        log_message('debug', "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n");
    } else {
        log_message('debug', "OK.\n");
    }

    log_message('debug', "Attempting to connect to '$host' on port '$port'...");
    $result = socket_connect($socket, $host, $port);
    if ($result === false) {
        log_message('debug', "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n");
    } else {
        log_message('debug', "OK.\n");
    }

    if($socket_error == true) {
        return array(
            "result" => null,
            "error" => array("code" => 201, "text" => "could not connect to socket")
            );
    }

//    $payload = sprintf("%s %d", $cmd, $param);

    $success = false;
    $bytesWritten = socket_write($socket, $cmd . PHP_EOL, strlen($cmd . PHP_EOL));
    $bytesRead = '';
    $result = array("result" => null, "error" => null);

    $bytesRead = socket_read($socket, 128);
    log_message('debug', 'after socket open ' . $bytesRead);

    switch($cmd) {
        case Control::CONTROL_CMD_TEMP:
        case Control::CONTROL_CMD_TIME:
            if(trim($bytesRead) == "READY") {
                $bytesWritten = socket_write($socket, $param . PHP_EOL, strlen($param . PHP_EOL));
                $bytesRead = socket_read($socket, 128);
                log_message('debug', 'after ready ' . $bytesRead);
                if(trim($bytesRead) == 'ACK') {
                    // command was acknowledged
                    $success = true;
                    $result['result'] = $bytesRead;
                } else {
                    log_message('debug', 'server returned a non ACK to parameter:' + $bytesRead);
                    $result['error'] = array("code" => 203, "text" => "Server did not ACK the command");
                }
            } else {
                log_message('debug', 'server returned a non READY to command:' + $bytesRead);
                $result['error'] = array("code" => 202, "text" => "server returned a non READY to command");
            }
            break;
        case Control::CONTROL_CMD_STATUS:
            $result = $bytesRead;
    }

    socket_close($socket);  

    return $result;
}

// Fehlerbehandlungsfunktion
function myErrorHandler($fehlercode, $fehlertext, $fehlerdatei, $fehlerzeile)
{
    global $socket_error;
    $socket_error = true;
    log_message('error', "$fehlercode, $fehlertext, $fehlerdatei, $fehlerzeile");
    return true;
}
