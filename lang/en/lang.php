<?php

return [
    'plugin' => [
        'name' => 'Octoshop Core',
        'menu_label' => 'Shop',
        'description' => 'A bespoke, modular eCommerce solution fit for any purpose.',
    ],
    'components' => [
        'product' => 'Product',
        'product_description' => 'Display a single product.',
        'products' => 'Product List',
        'products_description' => 'Displays a list of products on the page.',
    ],
    'settings' => [
        'product_page' => 'Product',
        'product_page_description' => 'Name of the product page file for the product links.',
        'slug' => 'Slug',
        'basket' => 'Basket container element',
        'basket_description' => 'CSS identifier to update when adding products to cart.',
    ],
];
