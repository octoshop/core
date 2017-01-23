<?php namespace Octoshop\Core\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Products Back-end Controller
 */
class Products extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['octoshop.core.access_products'];

    public $bodyClass = 'compact-container';

    protected $assetsPath = '/plugins/octoshop/core/assets';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Octoshop.Core', 'octoshop', 'products');

        $this->addCss($this->assetsPath.'/css/product-form.css');
    }
}
