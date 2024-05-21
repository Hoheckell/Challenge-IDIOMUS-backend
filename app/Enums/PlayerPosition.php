<?php

namespace App\Enums;

enum PlayerPosition: string
{ 
    case DEFENDER = 'defender';
    case MIDFIELDER = 'midfielder';
    case FORWARD = 'forward';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toString($enumValue) {
        switch ($enumValue) {
            case self::DEFENDER:
                return 'defender';
            case self::MIDFIELDER:
                return 'midfielder';
            case self::FORWARD:
                return 'forward';
            default:
                return 'Unknown';
        }
    }
}