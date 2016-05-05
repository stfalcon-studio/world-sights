<?php

namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * FriendStatusType
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
final class FriendStatusType extends AbstractEnumType
{
    const SENT     = 'SE';
    const REJECTED = 'RE';
    const ACCEPTED = 'AC';

    protected static $choices = [
        self::SENT     => 'Friend sent request',
        self::REJECTED => 'Friend rejected request',
        self::ACCEPTED => 'Friend accepted request',
    ];
}
