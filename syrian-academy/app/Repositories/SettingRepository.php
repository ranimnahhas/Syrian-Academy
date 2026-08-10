<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

class SettingRepository extends BaseRepository
{
    public function __construct(Setting $setting)
    {
        parent::__construct($setting);
    }

    public function getByGroup(string $group): Collection
    {
        return $this->model
            ->where('group', $group)
            ->orderBy('key')
            ->get();
    }

    public function getByKey(string $key): ?Setting
    {
        return $this->model->where('key', $key)->first();
    }

    public function getAllGrouped(): Collection
    {
        return $this->model->orderBy('group')->orderBy('key')->get();
    }
}