<?php

namespace App\Repositories;

use App\Models\PlayerSkill;
use App\Repositories\Interfaces\PlayerSkillRepositoryInterface;

class PlayerSkillRepository implements PlayerSkillRepositoryInterface
{
    protected $model;

    public function __construct(PlayerSkill $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {        
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->model->find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }
        return null;
    }

    public function delete($id)
    {
        $user = $this->model->find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}
