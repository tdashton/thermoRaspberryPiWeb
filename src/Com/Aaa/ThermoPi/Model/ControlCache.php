<?php

namespace Com\Aaa\ThermoPi\Model;


/**
 * Class ControlCache
 * @package Com\Aaa\ThermoPi\Model
 * @Entity
 * @Table(
 *     name="control_cache",
 *     uniqueConstraints={
 *         {@UniqueConstraint(name="type",columns={"type"})}
 *     }
 *
 * )
 *
 */
class ControlCache
{

 /*
  *
| control_cache | CREATE TABLE `control_cache` (
`type` char(16) DEFAULT NULL,
`param` mediumint(8) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 |

 */
    /**
     * @Column(type="string", columnDefinition="CHAR(16) DEFAULT NULL")
     *   `type` char(16) DEFAULT NULL,
     */
    protected $type;

    /**
     * @Column(type="integer", columnDefinition="MEDIUMINT(8) DEFAULT NULL")
     * `param` mediumint(8) DEFAULT NULL,
     */
    protected $param;

    /**
     * @Column(type="datetime", columnDefinition="DATETIME DEFAULT NULL")
     * `datetime` datetime DEFAULT NULL,
     */
    protected $datetime;

}