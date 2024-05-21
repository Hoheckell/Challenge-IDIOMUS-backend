<?php

namespace App\Services;

use App\DTO\TeamParameterDto;
use App\Repositories\PlayerRepository;

class TeamService
{
    protected $playerRepository;

    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    /**
     * Processes the team selection based on given requirements.
     *
     * @param TeamParameterDto[] $requirements An array of requirements for the team selection.
     * 
     * @return array An array of selected players that meet the given requirements.
     * 
     * @throws \Exception If there are not enough players available for any required position.
     */
    public function processTeamSelection(array $requirements)
    {
        $team = [];

        foreach ($requirements as $requirement) {
            $position = $requirement->position;
            $mainSkill = $requirement->mainSkill;
            $numberOfPlayers = $requirement->numberOfPlayers;

            $players = $this->getBestPlayersForPositionAndSkill($position, $mainSkill, $numberOfPlayers);

            if (count($players) < $numberOfPlayers) {
                throw new \Exception("Insufficient number of players for position: $position->value");
            }

            $team = array_merge($team, $players);
        }

        return $team;
    }

    private function getBestPlayersForPositionAndSkill($position, $skill, $number)
    {
        $playersWithSkill = $this->playerRepository->getPlayersWithSkill($position, $skill, $number);

        if ($playersWithSkill->count() < $number) {
            $remainingPlayers = $this->playerRepository->selectTheBest($position, $number, $playersWithSkill);
            $playersWithSkill = $playersWithSkill->merge($remainingPlayers);
        }

        $response = array_map(function ($player) {
            return [
                'name' => $player['name'],
                'position' => $player['position'],
                'playerSkills' => array_map(function ($skill) {
                    return [
                        'skill' => $skill['skill'],
                        'value' => $skill['value']
                    ];
                }, $player['skills'])
            ];
        }, array_slice($playersWithSkill->toArray(), 0, $number));
        return $response;        
    }
}