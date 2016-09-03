<?php namespace Octoshop\Core\Models;

use Model;

class CurrencySettings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'octoshop_currency';

    public $settingsFields = 'fields.yaml';

    protected $attributeNames = [
        'decimals' => 'decimal places',
    ];

    protected $rules = [
        'decimals' => ['required', 'numeric', 'between:0,4'],
    ];

    /**
     * Set default settings from fields.yaml
     */
    public function initSettingsData()
    {
        foreach ($this->getFieldConfig()->fields as $field => $data) {
            $this->{$field} = isset($data['default']) ? $data['default'] : '';
        }
    }
}
