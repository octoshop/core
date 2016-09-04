<?php namespace Octoshop\Core;

trait CanBeBought
{
    /**
     * Get the identifier of the Buyable item.
     *
     * @return int|string
     */
    public function getBuyableIdentifier()
    {
        return method_exists($this, 'getKey') ? $this->getKey() : $this->id;
    }

    /**
     * Get the description or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableDescription()
    {
        foreach (['name', 'title', 'dsecription'] as $key) {
            if (property_exists($this, $prop)) {
                return $this->$prop;
            }
        }

        return null;
    }

    /**
     * Get the price of the Buyable item.
     *
     * @return float
     */
    public function getBuyablePrice()
    {
        return property_exists($this, 'price') ? $this->price : null;
    }
}
