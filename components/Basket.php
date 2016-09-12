<?php namespace Octoshop\Core\Components;

use Cart;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Octoshop\Core\Models\Product as ShopProduct;

class Basket extends ComponentBase
{
    public $productPage;

    public $basketItems;

    public $basketCount;

    public $basketTotal;

    public function componentDetails()
    {
        return [
            'name'        => "Basket",
            'description' => "Show the contents of and process the user's basket.",
        ];
    }

    public function defineProperties()
    {
        return [
            'productPage' => [
                'title'       => 'Product Page',
                'description' => 'Name of the product page for the product titles.',
                'type'        => 'dropdown',
                'default'     => 'product',
            ],
        ];
    }

    public function onRun()
    {
        $this->prepareVars();
    }

    public function prepareVars()
    {
        $this->setPageProp('productPage');
        $this->setPageProp('basketComponent');

        $this->refresh();

        $this->setPageProp('basketComponent');
    }

    public function onAddProduct()
    {
        $product = ShopProduct::find($id = post('id'));

        Cart::add(
            $product->id,
            $product->title,
            post('quantity', 1),
            $product->price
        )->associate($product);

        return $this->refresh();
    }

    public function onRemoveProduct()
    {
        Cart::remove(post('row_id'));

        return $this->refresh();
    }

    public function refresh()
    {
        $this->setPageProp('basketItems', Cart::content());
        $this->setPageProp('basketCount', $count = Cart::count() ?: 0);
        $this->setPageProp('basketTotal', $total = Cart::total() ?: 0);

        return [
            'count' => $count,
            'total' => $total,
        ];
    }

    protected function setPageProp($property, $value = null)
    {
        $value = $value ?: $this->property($property);

        $this->page[$property] = $value;
        $this->{$property} = $value;
    }

    public function getPagesDropdown()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }
}
