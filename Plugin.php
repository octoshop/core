<?php namespace Octoshop\Core;

use Backend;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
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
}
