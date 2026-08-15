<?php

namespace App\Services;

use App\Repositories\SettingRepository;

class SettingService
{
    public function __construct(
        private SettingRepository $settingRepository
    ) {}

    public function getAll()
    {
        return $this->settingRepository->getAllGrouped();
    }

    public function getByGroup(string $group)
    {
        return $this->settingRepository->getByGroup($group);
    }

    public function update(string $key, string $value)
{
    $setting = $this->settingRepository->getByKey($key);

    if (!$setting) {
        // إنشاء إعداد جديد
        return $this->settingRepository->create([
            'key'   => $key,
            'value' => $value,
            'type'  => 'url',
            'group' => 'social',
            'label' => $key,
        ]);
    }

    $setting->update(['value' => $value]);
    return $setting;
}
public function delete(string $key): ?bool
{
    $setting = $this->settingRepository->getByKey($key);

    if (!$setting) {
        return null;
    }

    $setting->delete();
    return true;
}
}