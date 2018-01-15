<?php

namespace Despark\Cms\Fields;

class Versions extends Field
{
	public $parent;

    public function __construct($fieldName, array $options, $value = null)
    {
        $options['attributes']['placeholder'] = $options['label'];
        $options['attributes']['id'] = $fieldName;
        parent::__construct($fieldName, $options, $value);
    }

    public function afterRender() 
    {
        $model = $this->parent;
        $children = $model->children();
        $this->children = $children;
    }

}
