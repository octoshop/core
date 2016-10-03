<?php namespace Octoshop\Core\Components;

use Cms\Classes\Page;
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

    protected $preparedVars = [];

    protected $productFilters;

    public $products;

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
        $this->componentProperties = array_replace($this->componentProperties, $properties);

        return $this->properties = $this->validateProperties([]);
    }

    public function getProductPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->prepareVars();
    }

    public function prepareVars()
    {
        $this->registerVar('productPage', $this->property('productPage'));

        foreach ($this->preparedVars as $var) {
            $value = is_callable($var->value) ? call_user_func($var->value) : $var->value;

            $this->setPageProp($var->name, $value);
        }

        $this->setPageProp('products', $this->listProducts());
    }

    public function registerVar($var, $value)
    {
        array_push($this->preparedVars, (object) [
            'name' => $var,
            'value' => $value,
        ]);
    }

    public function listProducts()
    {
        $products = new Product;

        foreach ($this->productFilters as $property => $method) {
            $products = $products->$method($this->property($property));
        }

        $includeUnavailable = true;
        $filter = 'all'.($includeUnavailable ? 'Enabled' : 'Available').'WithImages';

        return $products->$filter();
    }

    public function registerFilter($property, $scope)
    {
        $this->productFilters[$property] = $scope;
    }
}
