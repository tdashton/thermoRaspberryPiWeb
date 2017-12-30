# thermoRaspberryPiWeb

## Inital Setup

### Prerequisities

* PHP enabled webserver (tested with PHP 5.5.24, caveat: might not work with 7 depending on which db driver you use)
* Code Igniter (version 2.2.6 included in this repository)
* SQL database
* Network access to host running the controller scripts (same subnet / host recommended)

### Config

Create the appropriate config files (from the .example):

```
application/config/config.php (base_url and encryption_key)
application/config/thermo_control.php
application/config/database.php
```

To see how to configure them, have a look at the files in the <em>config</em> directory, both of the files exist with a <em>.example</em> extension. For more information also see the Code Igniter documentation.

## Use

Once installed a few webpages are served, one with current readings from the database (fed from the sensors), and one with a JavaScript graph of the sensors' readings over time.

### Websites (HTML)

The following endpoints return HTML websites:

* GET URL_ROOT/logs/index
* GET URL_ROOT/logs/graph

The following REST endpoints return JSON data:

* GET URL_ROOT/logs/history/json/current
* GET URL_ROOT/logs/history/json/?start=DATE&end=DATE
* GET URL_ROOT/control/read
* POST URL_ROOT/control/command
	POST Param 'cmd' - the command
	POST Param 'param' - the value for the command (empty in some cases)
	POST Param 'signature' - signature : md5($cmd . $param . $sharedSecret . $nonce)
