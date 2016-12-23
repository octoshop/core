<?php namespace Octoshop\Core\Components;

use Cart;
use Cms\Classes\Page;
use October\Rain\Exception\ValidationException;
use Octoshop\Core\Models\Product as ShopProduct;

class Basket extends ComponentBase
{
    public $basketItems;

    public $basketCount;

    public $basketTotal;

    public $basketContainer;

    public $basketPartial;

    public $condense;

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
            'basketContainer' => [
                'title'       => 'Basket Container',
                'description' => 'CSS selector of the basket container element to update.',
                'default'     => '#basket',
            ],
            'basketPartial' => [
                'title'       => 'Basket Partial',
                'description' => 'Partial to use when adding products to basket',
                'default'     => 'basket/default',
            ],
            'condense' => [
                'title' => 'Condensed view',
                'description' => 'Renders the default table with the table-condensed class.',
                'type' => 'string',
            ],
        ];
    }

    public function onRun()
    {
        $this->prepareVars();
    }

    public function prepareVars()
    {
        $this->setPageProp('condense');

        $this->refresh();
    }

    public function refresh()
    {
        $this->setPageProp('basketContainer');
        $this->setPageProp('basketPartial');
        $this->setPageProp('basketItems', Cart::content());
        $this->setPageProp('basketCount', $count = Cart::count() ?: 0);
        $this->setPageProp('basketTotal', $total = Cart::total() ?: 0);

        return [
            'count' => $count,
            'total' => $total,
        ];
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

    public function onSetProductQty()
    {
        Cart::update(post('row_id'), post('quantity'));

        return $this->refresh();
    }

    public function onRemoveProduct()
    {
        Cart::remove(post('row_id'));

        return $this->refresh();
    }

    public function onEmptyBasket()
    {
        Cart::destroy();

        return $this->refresh();
    }

    public function onGoToCheckout()
    {
        $this->prepareVars();

        $basketErrors = Cart::validate();

        if (count($basketErrors) > 0) {
            throw new ValidationException($basketErrors);
        }
    }
}
