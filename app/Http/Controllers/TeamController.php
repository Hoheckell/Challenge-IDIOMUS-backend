<?php

namespace App\Http\Controllers;

use App\DTO\TeamParameterDto;
use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;
use App\Services\TeamService;
use Illuminate\Http\Request;

class /* The `TeamController` in this PHP code is a controller class that handles the processing of
team requirements. It contains a `process` method that takes a request object, retrieves the
requirements from the request, and then iterates over each requirement to find the best
players for the specified position and skill. */
TeamController extends Controller
{
    protected $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function process(Request $request)
    {
        try {
            $requirements = [];
            $teamData = $request->all();
            foreach($teamData as $data) {
                $requirements[] = new TeamParameterDto($data['numberOfPlayers'], PlayerSkill::from($data['mainSkill']), PlayerPosition::from($data['position']));
            }
            $team = $this->teamService->processTeamSelection($requirements);

            return response()->json($team);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
