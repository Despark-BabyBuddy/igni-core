<?php

namespace Despark\Cms\Fields;

class Cloud extends Field
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
        //$foreign_model = $this->fieldName;
        //$attribute = $this->options['title'];
        //dd($model->$foreign_model);
        //$this->value = $model->$foreign_model->$attribute;
    }

}
