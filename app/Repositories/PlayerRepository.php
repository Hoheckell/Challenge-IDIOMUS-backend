<?php

namespace App\Repositories;

use App\DTO\PlayerDto;
use App\Enums\PlayerPosition;
use App\Exceptions\InvalidPlayerPositionException;
use App\Exceptions\InvalidPlayerSkillException;
use App\Models\Player;
use App\Models\PlayerSkill;
use App\Repositories\Interfaces\PlayerRepositoryInterface;
use DB;

class PlayerRepository implements PlayerRepositoryInterface
{
    protected $model;

    public function __construct(Player $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        $players = [];
        $allPlayers = $this->model->all();
        foreach ($allPlayers as $player) {
            $players[] = $player->toDto();
        }
        return $players;

    }

    public function find($id)
    {
        $player = $this->model->find($id);
        if ($player) {
            return $player->toDto();
        } else {
            return false;
        }
    }

    public function create(PlayerDto $data)
    {
            $skills = [];
            $player = new Player();
            $player->name = $data->name;
            $player->setPlayerPosition($data->position->value);
            $player->save();

            foreach ($data->playerSkills as $ps) {
                $playerSkill = new PlayerSkill();
                $playerSkill->setSkill($ps['skill']);
                $playerSkill->value = $ps['value'];
                $player->skills()->save($playerSkill);
            }

            $player = Player::with('skills')->find($player->id);

            foreach ($player->skills as $skill) {
                $skills[] = $skill->toDto();
            }

            $playerDto = new PlayerDto($player->id, $player->name, PlayerPosition::from($player->position->value), $skills);

            return $playerDto;
    }

    public function update($id, PlayerDto $data)
    {
        try {
            $player = $this->model->find($id);
            if ($player) {
                $player->name = $data->name;
                $player->setPlayerPosition($data->position->value);
                $player->save();

                $playerSkills = $data->playerSkills;

                $player->skills()->delete();

                foreach ($playerSkills as $ps) {
                    $player->skills()->create([
                        'skill' => $ps['skill'],
                        'value' => $ps['value'],
                        'player_id' => $player->id,
                    ]);
                }

                return $this->model->find($player->id)->toDto();
            }
            return null;

        } catch (InvalidPlayerSkillException | InvalidPlayerPositionException $e) {
            echo "Caught exception: " . $e->getMessage();
        } catch (\Exception $e) {
            echo "Exception: " . $e->getMessage();
        }
    }

    public function delete($id)
    {
        $player = $this->model->find($id);
        if ($player) {
            return $player->delete();
        }
        return false;
    }

    public function getPlayersWithSkill($position, $skill, $number)
    {
        return $this->model->where(['position' => $position->value])
        ->whereHas('skills', function ($query) use ($skill) {
            $query->where('skill', $skill);
        })
        ->with([
            'skills' => function ($query) use ($skill) {
                $query->where('skill', $skill)->orderBy('value', 'desc');
            }
        ]) 
        ->limit($number)
        ->get();
    }

    public function selectTheBest($position, $number, $playersWithSkill)
    {
        return $this->model->where(['position' => $position])
        ->with([
            'skills' => function ($query) {
                $query->orderBy('value', 'desc');
            }
        ])
        ->orderByDesc(DB::raw('(SELECT MAX(value) FROM player_skills WHERE player_id = players.id)'))
        ->limit($number - $playersWithSkill->count())
        ->get();
    }

}
