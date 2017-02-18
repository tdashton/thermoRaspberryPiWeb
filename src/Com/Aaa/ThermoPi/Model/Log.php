<?php

namespace Com\Aaa\ThermoPi\Model;

use Doctrine\Entity;
use Doctrine\DBAL\Schema\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Schema\Column;

/**
 * Class Log
 * @package Com\Aaa\ThermoPi\Model
 * @Entity
 * @Table(
 *     name="logs"
 * )
 *
 *
 */
class Log
{

    /*
     *  *
 *
 | logs  | CREATE TABLE `logs` (
  `fk_sensor` char(16) NOT NULL,
  `datetime` datetime NOT NULL,
  `value` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`datetime`,`fk_sensor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 |

 */

    /**
     * @Id
     * @Column(type="string", columnDefinition="CHAR(16) DEFAULT NULL")
     * `fk_sensor` char(16) NOT NULL,
     */
    protected $fkSensor;

    /**
     * @Id
     * @Column(type="datetime", columnDefinition="DATETIME NOT NULL")
     * `datetime` datetime NOT NULL,
     */
    protected $datetime;

    /**
     * @Column(type="integer", columnDefinition="MEDIUMINT(8) DEFAULT NULL")
     * `value` mediumint(8) DEFAULT NULL,
     */
    protected $value;

}