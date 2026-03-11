<?php

namespace App\Services;

use App\Repositories\SettingRepository;
use Illuminate\Database\Eloquent\Collection;

class SettingService
{
    /**
     * @var SettingRepository
     */
    protected $settingRepository;

    /**
     * SettingService constructor.
     *
     * @param SettingRepository $settingRepository
     */
    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * Get all settings grouped by group.
     *
     * @return Collection
     */
    public function getGroupedSettings(): Collection
    {
        return $this->settingRepository->getAllGrouped();
    }

    /**
     * Update multiple settings at once.
     *
     * @param array $settings
     * @return void
     */
    public function updateSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->settingRepository->updateByKey($key, $value);
        }
    }
}
