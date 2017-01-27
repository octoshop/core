<?php namespace Octoshop\Core\Components;

use Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Octoshop\Core\Models\Product as ShopProduct;

class Product extends ComponentBase
{
    public $product;

    public $slug;

    public $basket;

    public function componentDetails()
    {
        return [
            'name' => 'octoshop.core::lang.components.product.name',
            'description' => 'octoshop.core::lang.components.product.description',
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title' => 'octoshop.core::lang.components.product.slug',
                'default' => '{{ :slug }}',
                'type' => 'string',
            ],
            'basket' => [
                'title' => 'octoshop.core::lang.components.product.basket',
                'description' => 'octoshop.core::lang.components.product.basket_description',
            ],
            'isPrimary' => [
                'title' => 'octoshop.core::lang.components.product.isPrimary',
                'type' => 'checkbox',
            ],
        ];
    }

    public function onRun()
    {
        $this->prepareVars();

        try {
            $product = $this->loadProduct();

            $this->setPageProp('product', $product);
        } catch (ModelNotFoundException $e) {
            $this->setStatusCode(404);

            return $this->controller->run(404);
        }
    }

    public function prepareVars()
    {
        $this->setPageProp('slug');
        $this->setPageProp('basket');
    }

    public function loadProduct()
    {
        return ShopProduct::findBySlug($this->slug)->firstEnabledWithImages();
    }
}
