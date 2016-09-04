<?php namespace Octoshop\Core\Components;

use Cms\Classes\ComponentBase;
use Octoshop\Core\Models\Product as ShopProduct;

class Product extends ComponentBase
{
    public $product;

    public $slug;

    public $basket;

    public function componentDetails()
    {
        return [
            'name' => 'octoshop.core::lang.components.product',
            'description' => 'octoshop.core::lang.components.product_description',
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title' => 'octoshop.core::lang.settings.slug',
                'default' => '{{ :slug }}',
                'type' => 'string',
            ],
            'basket' => [
                'title' => 'octoshop.core::lang.settings.basket',
                'description' => 'octoshop.core::lang.settings.basket_description',
            ],
        ];
    }

    public function onRun()
    {
        $this->prepareVars();

        $this->product = $this->page['product'] = $this->loadProduct();
    }

    public function prepareVars()
    {
        $this->slug = $this->page['slug'] = $this->property('slug');
        $this->basket = $this->page['basket'] = $this->property('basket');
    }

    public function loadProduct()
    {
        return ShopProduct::findBySlug($this->slug)->firstEnabledWithImages();
    }
}
