<?php

namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * SightTicketType
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
final class SightTicketType extends AbstractEnumType
{
    const TRAIN_TICKET = 'TT';
    const BUS_TICKET   = 'BT';
    const PLANE_TICKET = 'PT';

    protected static $choices = [
        self::TRAIN_TICKET => 'Train Ticket',
        self::BUS_TICKET   => 'Bus Ticket',
        self::PLANE_TICKET => 'Plane Ticket',
    ];
}
