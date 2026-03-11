<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

class SettingRepository extends BaseRepository
{
    /**
     * SettingRepository constructor.
     *
     * @param Setting $model
     */
    public function __construct(Setting $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all settings grouped by their group name.
     *
     * @return Collection
     */
    public function getAllGrouped(): Collection
    {
        return $this->model->all()->groupBy('group');
    }

    /**
     * Update a setting by its key.
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function updateByKey(string $key, $value): bool
    {
        return $this->model->where('key', $key)->update(['value' => $value]);
    }
}
