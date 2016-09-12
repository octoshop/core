<?php namespace Octoshop\Core\Components;

use Event;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Octoshop\Core\Models\Product;

class Products extends ComponentBase
{
    protected $componentProperties = [
        'productPage' => [
            'title' => 'octoshop.core::lang.settings.product_page',
            'description' => 'octoshop.core::lang.settings.product_page_description',
            'type' => 'dropdown',
            'default' => 'product',
        ],
    ];

    public $products;

    public function init()
    {
        Event::fire('octoshop.core.extendProductsComponent', [$this]);
    }

    public function componentDetails()
    {
        return [
            'name' => 'octoshop.core::lang.components.products',
            'description' => 'octoshop.core::lang.components.products_description',
        ];
    }

    public function defineProperties()
    {
        return $this->componentProperties;
    }

    public function addProperties($properties)
    {
        return $this->componentProperties = array_replace($this->componentProperties, $properties);
    }

    public function getProductPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->prepareVars();

        $this->products = $this->page['products'] = $this->listProducts();
    }

    public function prepareVars()
    {
        $this->productPage = $this->page['productPage'] = $this->property('productPage');
    }

    public function listProducts()
    {
        $products = new Product;

        $includeUnavailable = true;
        $filter = 'all'.($includeUnavailable ? 'Enabled' : 'Available').'WithImages';

        return $products->$filter();
    }
}
