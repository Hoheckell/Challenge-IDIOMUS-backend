<?php

namespace App\DTO;
use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;

class TeamParameterDto
{
    public int $numberOfPlayers;
    public PlayerSkill $mainSkill;
    public PlayerPosition $position;  

    public function __construct(?int $numberOfPlayers, PlayerSkill $mainSkill, PlayerPosition $position)
    {
        $this->numberOfPlayers = $numberOfPlayers;
        $this->mainSkill = $mainSkill;
        $this->position = $position;
    }
}