<?php

declare(strict_types=1);

namespace PHPGui\Driver\SDL\Internal;

interface Init
{
    public const TIMER = 0x0000_0001;

    public const AUDIO = 0x0000_0010;

    public const VIDEO = 0x0000_0020;

    public const JOYSTICK = 0x0000_0200;

    public const HAPTIC = 0x0000_1000;

    public const GAME_CONTROLLER = 0x0000_2000;

    public const EVENTS = 0x0000_4000;

    public const SENSOR = 0x0000_8000;

    public const NO_PARACHUTE = 0x0010_0000;

    public const EVERYTHING = (
        self::TIMER |
        self::AUDIO |
        self::VIDEO |
        self::EVENTS |
        self::JOYSTICK |
        self::HAPTIC |
        self::GAME_CONTROLLER |
        self::SENSOR
    );
}
