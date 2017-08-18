CREATE TABLE `control_cache` (
  `type` char(16) DEFAULT NULL,
  `param` mediumint(8) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
;

CREATE TABLE `control_logs` (
  `type` char(16) DEFAULT NULL,
  `param` mediumint(8) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1
;

CREATE TABLE `logs` (
  `fk_sensor` char(16) NOT NULL,
  `datetime` datetime NOT NULL,
  `value` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`datetime`,`fk_sensor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
;

CREATE TABLE `sensors` (
  `description` char(32) NOT NULL DEFAULT '',
  `name` char(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
;