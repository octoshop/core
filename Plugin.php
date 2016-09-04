<?php namespace Octoshop\Core;

use Backend;
use Event;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    protected $components = [];

    public function pluginDetails()
    {
        return [
            'name' => 'octoshop.core::lang.plugin.name',
            'icon' => 'icon-shopping-cart',
            'author' => 'Dave Shoreman',
            'homepage' => 'http://octoshop.co/',
            'description' => 'octoshop.core::lang.plugin.description',
        ];
    }

    public function registerComponents()
    {
        $this->components = [
            'Octoshop\Core\Components\Products' => 'shopProducts',
            'Octoshop\Core\Components\Product' => 'shopProduct',
        ];

        Event::fire('octoshop.core.extendComponents', [$this]);

        return $this->components;
    }

    public function addComponents($components)
    {
        return $this->components = array_replace($this->components, $components);
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'currency' =>  ['Octoshop\Core\Util\Currency', 'format'],
                'thumbnail' => ['Octoshop\Core\Util\Image', 'thumbnail'],
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'octoshop' => [
                'label' => 'octoshop.core::lang.plugin.menu_label',
                'url' => Backend::url('octoshop/core/products'),
                'icon' => 'icon-shopping-cart',
                'order' => 300,
                'permissions' => [
                    'octoshop.core.*',
                    'feegleweb.octoshop.*',
                ],
                'sideMenu' => [
                    'products' => [
                        'label'       => 'Products',
                        'url'         => Backend::url('octoshop/core/products'),
                        'icon'        => 'icon-cubes',
                        'order'       => 200,
                        'permissions' => ['octoshop.core.access_products']
                    ],
                ],
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'currency' => [
                'label'       => 'Currency',
                'description' => 'Set the currency code and symbol used on your shop.',
                'category'    => 'octoshop.core::lang.plugin.name',
                'icon'        => 'icon-gbp',
                'class'       => 'Octoshop\Core\Models\CurrencySettings',
                'order'       => 160,
            ],
        ];
    }
}
