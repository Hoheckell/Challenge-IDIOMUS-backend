<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;
use App\Models\PlayerSkill;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;


class TeamControllerTest extends PlayerControllerBaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with some test data
        $this->seedTestData();
    }

    protected function seedTestData()
    {
        // Create some players with skills
        Player::factory()->has(
            PlayerSkill::factory()->count(3),
            'skills'
        )->count(10)->create();
    }


    public function testTeamSelectionEndpoint()
    {
        $requestPayload = [
            [
                "position" => "midfielder",
                "mainSkill" => "speed",
                "numberOfPlayers" => 1
            ],
            [
                "position" => "defender",
                "mainSkill" => "strength",
                "numberOfPlayers" => 2
            ]
        ];

        $response = $this->postJson(self::REQ_TEAM_URI, $requestPayload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'name',
                'position',
                'playerSkills' => [
                    [
                        'skill',
                        'value'
                    ]
                ]
            ]
        ]);
    }

    public function testInsufficientPlayers()
    {
        $requestPayload = [
            [
                "position" => "midfielder",
                "mainSkill" => "stamina",
                "numberOfPlayers" => 5
            ]
        ];

        $response = $this->postJson(self::REQ_TEAM_URI, $requestPayload);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Insufficient number of players for position: midfielder'
        ]);
    }
    
}
