<?php namespace Octoshop\Core\Models;

use Model;

class ShopSetting extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'octoshop_core';

    public $settingsFields = 'fields.yaml';

    public $attachOne = [
        'default_product_image' => ['System\Models\File'],
    ];

    protected $defaults = [];

    /**
     * Set default settings. October doesn't grab them from fields.yaml
     */
    public function initSettingsData()
    {
        foreach ($this->defaults as $default => $value) {
            $this->$default = $value;
        }
    }

    public function registerDefaults(array $defaults)
    {
        return $this->defaults = array_merge($this->defaults, $defaults);
    }
}
