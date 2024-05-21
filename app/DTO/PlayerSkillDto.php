<?php

namespace App\DTO;
use App\Enums\PlayerSkill;

class PlayerSkillDto
{
    public ?int $id;
    public PlayerSkill $skill;
    public string $value; 
    public ?int $playerId; 

    public function __construct(?int $id, PlayerSkill $skill, string $value, ?int $playerId)
    {
        $this->id = $id;
        $this->skill = $skill;
        $this->value = $value;
        $this->playerId = $playerId;
    }
}