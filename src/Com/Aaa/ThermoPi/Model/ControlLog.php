<?php

namespace Com\Aaa\ThermoPi\Model;

use Doctrine\Entity;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\Column;

/**
 * Class ControlLog
 * @package Com\Aaa\ThermoPi\Model
 * @Entity
 * @Table(
 *     name="control_logs"
 *
 * @todo missing index
 */
class ControlLog
{
    /*
     *  *
 | control_logs | CREATE TABLE `control_logs` (
  `type` char(16) DEFAULT NULL,
  `param` mediumint(8) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 |

 */

    /**
     * @Column(type="string", columnDefinition="CHAR(16) DEFAULT NULL")
     * `type` char(16) DEFAULT NULL,
     */
    protected $type;

    /**
     * @Column(type="integer", columnDefinition="MEDIUMINT(8) DEFAULT NULL")
     * `param` mediumint(8) DEFAULT NULL,
     */
    protected $param;

    /**
     * @Column(type="datetime", columnDefinition="DATETIME DEFAULT NULL")
     * `datetime` datetime DEFAULT NULL
     */
    protected $datetime;

}