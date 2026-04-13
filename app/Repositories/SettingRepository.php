<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

class SettingRepository 
{
    protected $setting;
    /**
     * SettingRepository constructor.
     *
     * @param Setting $setting
     */
    public function __construct(Setting $setting)
    {
      $this->setting=$setting;
    }

    /**
     * Get all settings grouped by their group name.
     *
     * @return 
     */
    public function getAllGrouped()
    {
        return $this->setting->all()->groupBy('group');
    }

    /**
     * Update a setting by its key.
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function updateByKey(string $key, $value)
    {
        return $this->setting->where('key', $key)->update(['value' => $value]);
    }
}
