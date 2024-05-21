<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW. 
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\DTO\PlayerDto;
use App\Enums\PlayerPosition;
use App\Exceptions\InvalidPlayerPositionException;
use App\Exceptions\InvalidPlayerSkillException;
use App\Repositories\Interfaces\PlayerRepositoryInterface;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    protected $playerRepository;

    public function __construct(PlayerRepositoryInterface $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function index()
    {
        $players = $this->playerRepository->all();
        return response()->json($players);
    }

    public function show($id)
    {
        $player = $this->playerRepository->find($id);
        if ($player) {
            return response()->json($player);
        }
        return response()->json(['message' => 'Player not found'], 404);
    }

    public function store(Request $request)
    {
        try{
            $playerData = $request->only(['name', 'position', 'playerSkills']);

            if (PlayerPosition::tryFrom($playerData['position']) !== null) {
                $playerDto = new PlayerDto(null, $playerData['name'], PlayerPosition::from($playerData['position']), $playerData['playerSkills']);
                $player = $this->playerRepository->create($playerDto);
                return response()->json($player, 201); 
            } else {
                throw new InvalidPlayerPositionException($playerData['position']);
            }
        } catch (InvalidPlayerPositionException | InvalidPlayerSkillException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $playerData = $request->only(['name', 'position', 'playerSkills']);
            $playerDto = new PlayerDto(null, $playerData['name'], PlayerPosition::from($playerData['position']), $playerData['playerSkills']);
            $player = $this->playerRepository->update($id, $playerDto);
            if ($player) {
                return response()->json($player);
            }
            return response()->json(['message' => 'Player not found'], 404);
        } catch (InvalidPlayerPositionException | InvalidPlayerSkillException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    public function destroy($id)
    {
        $deleted = $this->playerRepository->delete($id);
        if ($deleted) {
            return response()->json(['message' => 'Player deleted']);
        }
        return response()->json(['message' => 'Player not found'], 404);
    }
}
