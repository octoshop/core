<?php namespace Octoshop\Core\Util;

use Octoshop\Core\Models\CurrencySettings;

class Currency
{
    public static function format($price)
    {
        $currency = CurrencySettings::instance();

        $price = number_format(
            $price,
            $currency->decimals,
            $currency->decimal_separator,
            $currency->thousand_separator
        );

        return htmlentities($currency->prefix.$price.$currency->suffix);
    }
}
