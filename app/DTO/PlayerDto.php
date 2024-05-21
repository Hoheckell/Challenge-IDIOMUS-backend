<?php

namespace App\DTO;
use App\Enums\PlayerPosition;

class PlayerDto
{
    public ?int $id;
    public string $name;
    public PlayerPosition $position; 

    /**
     * @var PlayerSkillDto[]
     */
    public ?array $playerSkills; 

    public function __construct(?int $id, string $name, PlayerPosition $position, ?array $playerSkills)
    {
        $this->id = $id;
        $this->name = $name;
        $this->position = $position;
        $this->playerSkills = $playerSkills;
    }
}