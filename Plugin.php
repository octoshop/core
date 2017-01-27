<?php namespace Octoshop\Core;

use App;
use Backend;
use Event;
use Illuminate\Foundation\AliasLoader;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    protected $components = [];

    public function boot()
    {
        App::register('\Octoshop\Core\CartServiceProvider');
        AliasLoader::getInstance()->alias('Cart', '\Octoshop\Core\Facades\Cart');
    }

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
            'Octoshop\Core\Components\Basket' => 'shopBasket',
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
                ],
                'sideMenu' => [
                    'products' => [
                        'label'       => 'octoshop.core::lang.menu.products',
                        'url'         => Backend::url('octoshop/core/products'),
                        'icon'        => 'icon-cubes',
                        'order'       => 200,
                        'permissions' => ['octoshop.core.access_products']
                    ],
                ],
            ],
        ];
    }

    public function registerPermissions()
    {
        return [
            'octoshop.core.access_products' => [
                'tab' => 'octoshop.core::lang.plugin.name',
                'label' => 'octoshop.core::lang.permissions.products',
            ],
            'octoshop.core.access_settings' => [
                'tab' => 'octoshop.core::lang.plugin.name',
                'label' => 'octoshop.core::lang.permissions.settings',
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'currency' => [
                'label'       => 'octoshop.core::lang.settings.currency.label',
                'description' => 'octoshop.core::lang.settings.currency.description',
                'category'    => 'octoshop.core::lang.plugin.name',
                'icon'        => 'icon-gbp',
                'class'       => 'Octoshop\Core\Models\CurrencySettings',
                'order'       => 160,
                'permissions' => ['octoshop.core.access_settings'],
            ],
            'shop' => [
                'label' => 'octoshop.core::lang.settings.shop.label',
                'description' => 'octoshop.core::lang.settings.shop.description',
                'category' => 'octoshop.core::lang.plugin.name',
                'icon' => 'icon-sliders',
                'class' => 'Octoshop\Core\Models\ShopSetting',
                'order' => 150,
                'permissions' => ['octoshop.core.access_settings'],
            ],
        ];
    }
}
