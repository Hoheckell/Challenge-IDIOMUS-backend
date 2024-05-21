<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW. 
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Models;

use App\DTO\PlayerSkillDto;
use App\Enums\PlayerSkill as EPlayerSkill;
use App\Exceptions\InvalidPlayerSkillException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerSkill extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'skill',
        'value'
    ];

    protected $casts = [
        'skill' => EPlayerSkill::class
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function toDto()
    {
        return new PlayerSkillDto($this->id, EPlayerSkill::from($this->skill->value), $this->value, $this->player_id);
    }


    public function setSkill(string $value)
    {
        if (EPlayerSkill::tryFrom($value) == null) {
            throw new InvalidPlayerSkillException($value, 400);
        }

        $this->skill = EPlayerSkill::from($value);
    }
    
}
