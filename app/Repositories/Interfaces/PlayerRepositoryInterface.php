<?php

namespace App\Repositories\Interfaces;
use App\DTO\PlayerDto;
use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;
use Illuminate\Database\Eloquent\Collection;

interface PlayerRepositoryInterface
{
    public function all();
    public function find($id);
    public function getPlayersWithSkill( PlayerPosition $position, PlayerSkill $skill, int $number);
    public function selectTheBest( PlayerPosition $position, int $number, Collection $playersWithSkill);
    public function create(PlayerDto $data);
    public function update($id, PlayerDto $data);
    public function delete($id);
}
