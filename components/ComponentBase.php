<?php namespace Octoshop\Core\Components;

use Cms\Classes\ComponentBase as BaseComponent;

abstract class ComponentBase extends BaseComponent
{
    /**
     * Make a property accessible from both the page and component scope
     * @param string    $property
     * @param mixed     $value
     */
    protected function setPageProp($property, $value = null)
    {
        $value = $value ?: $this->property($property);

        $this->page[$property] = $value;
        $this->{$property} = $value;
    }
}
