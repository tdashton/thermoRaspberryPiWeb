<?php

$socket_error = false;

function notify_controller($cmd, $param, $host, $port) {
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
        return "could not connect to socket";
    }

    $payload = sprintf("%s %d", $cmd, $param);

    $bytesWritten = socket_write($socket, $payload, strlen($payload));
    $bytesRead = '';

    while($out = socket_read($socket, 1024)) {
        $bytesRead .= $out;
    }

    log_message('debug', $bytesRead);
    $success = false;
    if(trim($bytesRead) == 'ACK') {
        $success = true;
    }

    socket_close($socket);  

    return $bytesRead;  
}

// Fehlerbehandlungsfunktion
function myErrorHandler($fehlercode, $fehlertext, $fehlerdatei, $fehlerzeile)
{
    global $socket_error;
    $socket_error = true;
    log_message('error', "$fehlercode, $fehlertext, $fehlerdatei, $fehlerzeile");
    return true;
}
